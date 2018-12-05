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
use OpayGateway;

/**
 * Class HCOpayManager
 * @package HoneyComb\Payments\Managers
 */
class HCOpayManager extends HCPaymentManager implements HCPaymentManagerContract
{
    public const OPAY_REDIRECT_URL = 'https://gateway.opay.lt?tid=';

    public const TRANSACTION_CREATE_URL = 'https://gateway.opay.lt/api/createtransaction/';

    /**
     * @var OpayGateway
     */
    private $opay;

    /**
     * HCOpayManager constructor.
     * @param HCPaymentRepository $paymentRepository
     */
    public function __construct(HCPaymentRepository $paymentRepository)
    {
        parent::__construct($paymentRepository);

        $this->opay = new OpayGateway();
        $this->opay->setSignaturePassword(config('payments.drivers.opay.signature'));
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function driver(): string
    {
        return HCPaymentDriverEnum::opay()->id();
    }

    /**
     * @param HCPayment $payment
     * @param HCPaymentUserDTO $paymentUserDTO
     * @return string
     * @throws \OpayGatewayException
     * @throws HCException
     */
    public function pay(HCPayment $payment, HCPaymentUserDTO $paymentUserDTO): string
    {
        $data = [
            'redirect_url' => route('payments.opay.accept', $payment->id),
            'web_service_url' => route('payments.opay.callback.get'),

            'website_id' => config('payments.drivers.opay.website_id'),
            'standard' => config('payments.drivers.opay.standard'),
            'redirect_on_success' => config('payments.drivers.opay.redirect_on_success'),

            'order_nr' => $payment->order_number,
            'country' => config('payments.country'),
            'language' => config('payments.drivers.opay.language'),
            'amount' => $this->getCents($payment->amount),
            'currency' => config('payments.currency'),
            'test' => config('payments.drivers.opay.test') ? config('payments.drivers.opay.user_id') : null,

            'c_email' => $paymentUserDTO->getEmail(),
            'c_mobile_nr' => $paymentUserDTO->getPhone(),
        ];

        return $this->getRedirectUrl($data);
    }

    /**
     * @param array $params
     * @return Response|null
     * @throws HCException
     * @throws \OpayGatewayException
     * @throws \ReflectionException
     */
    public function callback(array $params): ?Response
    {
        $response = $this->parseParams($params);

        if ($response['status'] != 1) {
            logger()->info('Opay payment status is not equal to 1');

            return null;
        }

        $payment = $this->paymentRepository->findByOrderNumber($response['order_nr']);

        if (is_null($payment)) {
            throw new HCException('Payment not found! Order number - ' . $response['order_nr']);
        }

        if ($payment->isCanceled()) {
            throw new HCException('Trying to confirm canceled payment - ' . $response['order_nr']);
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
     * @param array $request
     * @return array
     * @throws \OpayGatewayException
     */
    private function parseParams(array $request): array
    {
        return $this->opay->convertEncodedStringToArrayOfParameters($request['encoded']);
    }

    /**
     * @param HCPayment $payment
     * @param array $response
     * @throws HCException
     */
    private function validateCallback(HCPayment $payment, array $response): void
    {
        if ($this->getCents((float)$response['p_amount']) < $this->getCents($payment->amount)) {
            logger()->error($response);

            throw new HCException(
                trans('HCPayments::payments.message.bad_amount', ['amount' => $response['p_amount']])
            );
        }
    }

    /**
     * @param array $data
     * @return string
     * @throws HCException
     * @throws \OpayGatewayException
     */
    private function getRedirectUrl(array $data): string
    {
        // get transaction response
        $response = $this->opay->webServiceRequest(
            self::TRANSACTION_CREATE_URL,
            $this->opay->signArrayOfParameters($data)
        );

        if (!$transactionId = array_get($response, 'response.result.transaction_id')) {
            logger()->error($response);

            throw new HCException(trans('HCPayments::payments.message.opay_payment_error'));
        }

        return self::OPAY_REDIRECT_URL . $transactionId;
    }
}
