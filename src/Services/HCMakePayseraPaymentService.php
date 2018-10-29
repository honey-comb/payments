<?php

declare(strict_types = 1);

namespace HoneyComb\Payments\Services;

use HoneyComb\Payments\Enum\HCPaymentMethodEnum;
use HoneyComb\Payments\Events\HCPaymentCreated;
use HoneyComb\Payments\Models\HCPayment;
use WebToPay;

/**
 * Class HCMakePayseraPaymentService
 * @package HoneyComb\Payments\Services
 */
class HCMakePayseraPaymentService extends HCMakePaymentService
{
    /**
     * @param float|null $amount
     * @param string|null $countryCode
     * @param array $paymentGroupsNames
     * @return array
     * @throws \WebToPayException
     */
    public function getPaymentMethods(
        float $amount = null,
        string $countryCode = null,
        array $paymentGroupsNames = []
    ): array {
        $projectId = intval(config('payments.paysera.project_id'));
        $currency = config('payments.paysera.currency');

        if (is_null($countryCode)) {
            $countryCode = strtolower(config('payments.paysera.country_code'));
        }

        if ($paymentGroupsNames == null) {
            $paymentGroupsNames = config('payments.paysera.payment_groups');
        }

        $paymentMethods = WebToPay::getPaymentMethodList($projectId)
            ->setDefaultLanguage($countryCode)
            ->getCountry($countryCode);

        if ($paymentMethods && $amount) {
            $paymentMethods = $paymentMethods->filterForAmount($amount, $currency);
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
     * @param float $amount
     * @param string $currency
     * @param string $orderNumber
     * @param string $reasonId
     * @param string|null $paymentType
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function makePayment(
        float $amount,
        string $currency,
        string $orderNumber,
        string $reasonId,
        string $paymentType = null,
        array $options = []
    ): string {
        $projectId = intval(config('payments.paysera.project_id'));
        $signPassword = config('payments.paysera.sign_password');

        $country = config('payments.paysera.country_code');
        $lang = config('payments.paysera.lang');
        $testMode = config('payments.paysera.test');

        $currency = ($currency) ? $currency : config('payments.paysera.currency');

        $this->connection->beginTransaction();

        try {
            $methodId = HCPaymentMethodEnum::paysera()->id();

            $payment = $this->paymentService
                ->createPayment($amount, $currency, $reasonId, $methodId, $orderNumber, $paymentType);

            event(new HCPaymentCreated($payment));

            $paymentData = [
                'projectid' => $projectId,
                'sign_password' => $signPassword,

                'orderid' => $orderNumber,
                'amount' => $this->convertAmountToCents($amount),
                'country' => $country,
                'currency' => $currency,
                'lang' => $lang,
                'payment' => $paymentType,

                'test' => $testMode,
            ];

            logger()->info(json_encode($paymentData));

            $paymentData = array_merge($paymentData, [
                'cancelurl' => route('payments.paysera.cancel', $payment->id),
                'accepturl' => route('payments.paysera.accept', $payment->id),
                'callbackurl' => route('payments.paysera.callback.get'),

                'p_email' => array_get($options, 'email', ''),
                'p_firstname' => array_get($options, 'first_name', ''),
                'p_lastname' => array_get($options, 'last_name', ''),
                'p_street' => array_get($options, 'street_address', ''),
                'p_city' => array_get($options, 'city', ''),
                'p_zip' => array_get($options, 'postal_code', ''),
            ]);

            $this->connection->commit();

            return $this->getRedirectUrl($paymentData);
        } catch (WebToPayException $exception) {
            $this->connection->rollBack();

            report($exception);
        }

        return '';
    }

    /**
     * @param array $request
     * @return array
     * @throws \Exception
     */
    public function parseParams(array $request): array
    {
        $projectId = intval(config('payments.paysera.project_id'));
        $signPassword = config('payments.paysera.sign_password');
        $testMode = config('payments.paysera.test');

        $response = WebToPay::validateAndParseData(
            $request,
            $projectId,
            $signPassword
        );

        if ($testMode == 0) {
            if ($response['test'] !== '0') {
                throw new \Exception(trans('HCPayments::payments.messages.testing_enabled'));
            }
        }

        return $response;
    }

    /**
     * @param HCPayment $payment
     * @param array $response
     * @throws \Exception
     */
    public function validateCallback(HCPayment $payment, array $response): void
    {
        if ($this->convertAmountToCents((float) $response['payamount']) < $this->convertAmountToCents((float) $payment->amount, 2)) {
            $errorMessage = trans('HCPayments::payments.message.bad_amount', ['amount' => $response['amount']]);

            logger()->error($errorMessage);

            throw new \Exception($errorMessage);
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
