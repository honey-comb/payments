<?php

namespace HoneyComb\Payments\Contracts;

use HoneyComb\Payments\Models\HCPayment;

/**
 * Interface PaymentContract
 * @package App\Contracts
 */
interface PaymentContract
{
    /**
     * @param float|null $amount
     * @param string|null $country
     * @param array $paymentGroupsNames
     * @return array
     */
    public function getPaymentMethods(
        float $amount = null,
        string $country = null,
        array $paymentGroupsNames = []
    ): array;

    /**
     * @param float $amount
     * @param string $currency
     * @param string $orderNumber
     * @param string $reasonId
     * @param string|null $paymentType
     * @return string
     */
    public function makePayment(
        float $amount,
        string $currency,
        string $orderNumber,
        string $reasonId,
        string $paymentType = null
    ): string;

    /**
     * @param string $amount
     * @param int $numberCountAfterComma
     * @return int
     */
    public function convertAmountToCents(string $amount, int $numberCountAfterComma = 0): int;

    /**
     * @param HCPayment $payment
     * @param array $response
     */
    public function validateCallback(HCPayment $payment, array $response): void;

    /**
     * @param array $request
     * @return array
     */
    public function parseParams(array $request): array;
}