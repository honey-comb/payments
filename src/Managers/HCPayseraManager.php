<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */
declare(strict_types = 1);

namespace HoneyComb\Payments\Managers;

use HoneyComb\Payments\Contracts\HCPaymentManagerContract;
use HoneyComb\Payments\DTO\HCPaymentUserDTO;
use HoneyComb\Payments\Enum\HCPaymentDriverEnum;
use HoneyComb\Payments\Enum\HCPaymentStatusEnum;
use HoneyComb\Payments\Events\HCPaymentCompleted;
use HoneyComb\Payments\Models\HCPayment;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use HoneyComb\Starter\Exceptions\HCException;
use Illuminate\Http\Response;
use WebToPay;

/**
 * Class HCPayseraManager
 * @package HoneyComb\Payments\Managers
 */
class HCPayseraManager extends HCPaymentManager implements HCPaymentManagerContract
{
    /**
     * HCPayseraManager constructor.
     * @param HCPaymentRepository $paymentRepository
     */
    public function __construct(HCPaymentRepository $paymentRepository)
    {
        parent::__construct($paymentRepository);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function driver(): string
    {
        return HCPaymentDriverEnum::paysera()->id();
    }

    /**
     * @param HCPayment $payment
     * @param HCPaymentUserDTO $paymentUserDTO
     * @return string
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \WebToPayException
     */
    public function pay(HCPayment $payment, HCPaymentUserDTO $paymentUserDTO): string
    {
        $data = [
            'cancelurl' => route('payments.drivers.paysera.cancel', $payment->id),
            'accepturl' => route('payments.drivers.paysera.accept', $payment->id),
            'callbackurl' => route('payments.drivers.paysera.callback.get'),

            'projectid' => config('payments.drivers.paysera.project_id'),
            'sign_password' => config('payments.drivers.paysera.sign_password'),

            'orderid' => $payment->order_number,
            'amount' => $this->getCents($payment->amount),
            'currency' => $payment->currency,
            'country' => config('payments.country'),
            'lang' => config('payments.drivers.paysera.language'),
            'payment' => $payment->method,

            'test' => config('payments.drivers.paysera.test'),

            'p_email' => $paymentUserDTO->getEmail(),
            'p_firstname' => $paymentUserDTO->getFirstName(),
            'p_lastname' => $paymentUserDTO->getLastName(),
            'p_street' => $paymentUserDTO->getStreet(),
            'p_city' => $paymentUserDTO->getCity(),
            'p_state' => $paymentUserDTO->getState(),
            'p_zip' => $paymentUserDTO->getZip(),
            'p_countrycode' => $paymentUserDTO->getCountryCode(),
        ];

        return $this->getRedirectUrl($data);
    }

    /**
     * @param array $params
     * @return Response|null
     * @throws HCException
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \ReflectionException
     * @throws \WebToPayException
     */
    public function callback(array $params): ?Response
    {
        $response = $this->parseParams($params);

        if ($response['status'] != 1) {
            logger()->info('Paysera payment status is not equal to 1');

            return null;
        }

        $payment = $this->paymentRepository->findByOrderNumber($response['orderid']);

        if (is_null($payment)) {
            throw new HCException('Payment not found! Order number - ' . $response['orderid']);
        }

        if ($payment->isCanceled()) {
            throw new HCException('Trying to confirm canceled payment - ' . $response['orderid']);
        }

        if ($payment->isCompleted()) {
            return response('OK', 200);
        }

        $this->validateCallback($payment, $response);

        $payment->update([
            'status' => HCPaymentStatusEnum::completed()->id(),
        ]);

        event(new HCPaymentCompleted($payment));

        return response('OK', 200);
    }

    /**
     * @param float|null $amount
     * @param string|null $countryCode
     * @param array $paymentGroupsNames
     * @return array
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \WebToPayException
     */
    public function getPaymentMethods(
        float $amount = null,
        string $countryCode = null,
        array $paymentGroupsNames = []
    ): array {
        if (is_null($countryCode)) {
            $countryCode = strtolower(config('payments.drivers.paysera.country_code'));
        }

        $paymentMethods = WebToPay::getPaymentMethodList(config('payments.drivers.paysera.project_id'))
            ->setDefaultLanguage($countryCode)
            ->getCountry($countryCode);

        if ($paymentMethods && $amount) {
            $paymentMethods = $paymentMethods->filterForAmount($amount, config('payments.drivers.paysera.currency'));
        }

        if (!$paymentMethods) {
            return [];
        }

        $paymentMethods = $paymentMethods->getGroups();

        $paymentMethods = array_map(function (\WebToPay_PaymentMethodGroup $group) {
            return [
                'title' => $group->getTitle(),
                'methods' => array_map(function (\WebToPay_PaymentMethod $method) use ($group) {
                    return [
                        'key' => $method->getKey(),
                        'group' => $group->getKey(),
                        'title' => $method->getTitle(),
                        'currency' => $method->getBaseCurrency(),
                        'logo' => $method->getLogoUrl(),
                        'minAmount' => $method->getMinAmountAsString(),
                        'maxAmount' => $method->getMaxAmountAsString(),
                        'isIban' => $method->isIban() ? true : false,
                    ];
                }, $group->getPaymentMethods()),
            ];
        }, $paymentMethods);

        if (!empty($paymentGroupsNames)) {
            $paymentMethods = array_filter($paymentMethods, function ($value, $key) use ($paymentGroupsNames) {
                return in_array($key, $paymentGroupsNames);
            }, ARRAY_FILTER_USE_BOTH);
        }

        return $paymentMethods;
    }


    /**
     * @param array $request
     * @return array
     * @throws HCException
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \WebToPayException
     */
    private function parseParams(array $request): array
    {
        $response = WebToPay::validateAndParseData(
            $request,
            config('payments.drivers.paysera.project_id'),
            config('payments.drivers.paysera.sign_password')
        );

        if (config('payments.drivers.paysera.test') == 0) {
            if ($response['test'] !== '0') {
                throw new HCException(trans('HCPayments::payments.messages.testing_enabled'));
            }
        }

        return $response;
    }

    /**
     * @param HCPayment $payment
     * @param array $response
     * @throws HCException
     */
    private function validateCallback(HCPayment $payment, array $response): void
    {
        if ($this->getCents((float)$response['amount']) < $this->getCents($payment->amount)) {
            $errorMessage = trans('HCPayments::payments.message.bad_amount', ['amount' => $response['amount']]);

            logger()->error($errorMessage);

            throw new HCException($errorMessage);
        }
    }

    /**
     * @param array $parameters
     * @return string
     * @throws \WebToPayException
     */
    private function getRedirectUrl(array $parameters): string
    {
        return WebToPay::getPaymentUrl() . '?' . http_build_query(WebToPay::buildRequest($parameters));
    }
}
