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

use HoneyComb\Payments\Contracts\HCPaymentManagerContract;
use HoneyComb\Payments\Enum\HCPaymentStatusEnum;
use HoneyComb\Payments\Events\HCPaymentCanceled;
use HoneyComb\Payments\Events\HCPaymentCompleted;
use HoneyComb\Payments\Managers\HCOpayManager;
use HoneyComb\Payments\Managers\HCPayseraManager;
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
    public function __construct(HCPaymentRepository $repository)
    {
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
     * @param string $paymentId
     * @return HCPayment
     * @throws \ReflectionException
     */
    public function confirm(string $paymentId): HCPayment
    {
        /** @var HCPayment $payment */
        $payment = $this->getRepository()->findOrFail($paymentId);

        if ($payment->isPending()) {
            $payment->update([
                'status' => HCPaymentStatusEnum::completed()->id(),
            ]);

            event(new HCPaymentCompleted($payment));
        }

        return $payment;
    }

    /**
     * @param string $paymentId
     * @return HCPayment
     * @throws \ReflectionException
     */
    public function cancel(string $paymentId): HCPayment
    {
        /** @var HCPayment $payment */
        $payment = $this->getRepository()->findOrFail($paymentId);

        if ($payment->isPending()) {
            $payment->update([
                'status' => HCPaymentStatusEnum::canceled()->id(),
            ]);

            event(new HCPaymentCanceled($payment));
        }

        return $payment;
    }

    /**
     * @param string $driver
     * @return HCPaymentManagerContract
     * @throws HCException
     */
    public function driver(string $driver): HCPaymentManagerContract
    {
        return app($this->getDriverClass($driver));
    }

    /**
     * @param string $driver
     * @return string
     */
    protected function getDriverClass(string $driver): string
    {
        $drivers = [
            'paysera' => HCPayseraManager::class,
            'opay' => HCOpayManager::class,
        ];

        $drivers = array_merge($drivers, config('payments.additional_drivers', []));

        if (!array_has($drivers, $driver)) {
            throw new HCException(trans('HCPayments::payments.message.driver_not_implemented', ['driver' => $driver]));
        }

        return $drivers[$driver];
    }
}
