<?php
/**
 * @copyright 2017 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Payments\Services;

use App\Enum\HCPaymentStatusEnum;
use HoneyComb\Payments\Models\HCPayment;
use HoneyComb\Payments\Repositories\HCPaymentRepository;

/**
 * Class HCPaymentService
 * @package HoneyComb\Payments\Services
 */
class HCPaymentService
{
    /**
     * @var HCPaymentRepository
     */
    private $repository;

    /**
     * HCPaymentService constructor.
     * @param HCPaymentRepository $repository
     */
    public function __construct(
        HCPaymentRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @return HCPaymentRepository
     */
    public function getRepository(): HCPaymentRepository
    {
        return $this->repository;
    }

    /**
     * @param float $amount
     * @param string $currency
     * @param string $reasonId
     * @param string $methodId
     * @param string $orderData
     * @param string|null $paymentType
     * @return HCPayment
     * @throws \ReflectionException
     */
    public function createPayment(
        float $amount,
        string $currency,
        string $reasonId,
        string $methodId,
        string $orderData,
        string $paymentType = null
    ): HCPayment {
        return $this->repository
            ->create([
                'status' => HCPaymentStatusEnum::pending()->id(),
                'amount' => $amount,
                'currency' => $currency,
                'method_id' => $methodId,
                'reason_id' => $reasonId,
                'payment_type' => $paymentType,
                'order_data' => $orderData,
            ]);
    }

    /**
     * @param string $paymentId
     * @param array $data
     * @return HCPayment
     */
    public function updatePayment(string $paymentId, array $data = []): HCPayment
    {
        $payment = $this->repository->findOrFail($paymentId);

        $payment->update($data);

        return $payment;
    }

    /**
     * @param string $paymentId
     */
    public function deletePayment(string $paymentId): void
    {
        $this->repository->makeQuery()
            ->where(['id' => $paymentId])
            ->delete();
    }
}
