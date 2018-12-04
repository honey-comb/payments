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

namespace HoneyComb\Payments\DTO;

use HoneyComb\Starter\DTO\HCBaseDTO;

/**
 * Class HCPaymentDTO
 * @package HoneyComb\Payments\DTO
 */
class HCPaymentDTO extends HCBaseDTO
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $status;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $orderNumber;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $order = [];

    /**
     * @var string|null
     */
    private $ownerableId;

    /**
     * @var string|null
     */
    private $ownerableType;

    /**
     * @var string|null
     */
    private $invoiceId;

    /**
     * HCPaymentDTO constructor.
     * @param string $orderNumber
     * @param string $status
     * @param float $amount
     */
    public function __construct(string $orderNumber, float $amount, string $status)
    {
        $this->orderNumber = $orderNumber;
        $this->amount = $amount;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     * @return HCPaymentDTO
     */
    public function setDriver(string $driver): HCPaymentDTO
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return HCPaymentDTO
     */
    public function setStatus(string $status): HCPaymentDTO
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return HCPaymentDTO
     */
    public function setAmount(float $amount): HCPaymentDTO
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        if ($this->currency) {
            return $this->currency;
        }

        return (string)config('payments.currency');
    }

    /**
     * @param string $currency
     * @return HCPaymentDTO
     */
    public function setCurrency(string $currency): HCPaymentDTO
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return HCPaymentDTO
     */
    public function setOrderNumber(string $orderNumber): HCPaymentDTO
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     * @return HCPaymentDTO
     */
    public function setReason(string $reason): HCPaymentDTO
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return HCPaymentDTO
     */
    public function setMethod(string $method): HCPaymentDTO
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder(): ?array
    {
        if ($this->order) {
            return $this->order;
        }

        return null;
    }

    /**
     * @param array $order
     * @return HCPaymentDTO
     */
    public function setOrder(array $order): HCPaymentDTO
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOwnerableId(): ?string
    {
        return $this->ownerable_id;
    }

    /**
     * @param string|null $ownerableId
     * @return HCPaymentDTO
     */
    public function setOwnerableId(string $ownerableId = null): HCPaymentDTO
    {
        $this->ownerableId = $ownerableId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOwnerableType(): ?string
    {
        return $this->ownerableType;
    }

    /**
     * @param string|null $ownerableType
     * @return HCPaymentDTO
     */
    public function setOwnerableType(string $ownerableType = null): HCPaymentDTO
    {
        $this->ownerableType = $ownerableType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInvoiceId(): ?string
    {
        return $this->invoice_id;
    }

    /**
     * @param string|null $invoiceId
     * @return HCPaymentDTO
     */
    public function setInvoiceId(string $invoiceId = null): HCPaymentDTO
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'driver' => $this->getDriver(),
            'status' => $this->getStatus(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'order_number' => $this->getOrderNumber(),
            'reason' => $this->getReason(),
            'method' => $this->getMethod(),
            'order' => $this->getOrder(),
            'ownerable_id' => $this->getOwnerableId(),
            'ownerable_type' => $this->getOwnerableType(),
            'invoice_id' => $this->getInvoiceId(),
        ];
    }
}
