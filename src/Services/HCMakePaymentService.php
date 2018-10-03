<?php

declare(strict_types = 1);

namespace HoneyComb\Payments\Services;

use HoneyComb\Payments\Contracts\PaymentContract;
use HoneyComb\Payments\Models\HCPayment;
use Illuminate\Database\Connection;

abstract class HCMakePaymentService implements PaymentContract
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCPaymentService
     */
    protected $paymentService;

    /**
     * HCPaymentService constructor.
     * @param Connection $connection
     * @param HCPaymentService $paymentService
     */
    public function __construct(Connection $connection, HCPaymentService $paymentService)
    {
        $this->connection = $connection;
        $this->paymentService = $paymentService;
    }

    /**
     * @param float|null $amount
     * @param string|null $country
     * @param array $paymentGroupsNames
     * @return array
     */
    abstract public function getPaymentMethods(
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
    abstract public function makePayment(
        float $amount,
        string $currency,
        string $orderNumber,
        string $reasonId,
        string $paymentType = null
    ): string;

    /**
     * @param $amount
     * @return int
     */
    public function convertAmountToCents(float $amount): int
    {
        $amount = (float)str_replace(",", ".", (string)$amount);

        return intval(number_format($amount, 2, '', ''));
    }

    /**
     * @param HCPayment $payment
     * @param array $response
     */
    abstract public function validateCallback(HCPayment $payment, array $response): void;

    /**
     * @param array $request
     * @return array
     */
    abstract public function parseParams(array $request): array;
}