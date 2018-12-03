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

namespace HoneyComb\Payments\Managers;

use HoneyComb\Payments\Contracts\HCPaymentManagerContract;
use HoneyComb\Payments\DTO\HCPaymentDTO;
use HoneyComb\Payments\DTO\HCPaymentUserDTO;
use HoneyComb\Payments\Models\HCPayment;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use HoneyComb\Starter\Exceptions\HCException;
use Illuminate\Http\Response;

/**
 * Class HCPaymentManager
 * @package HoneyComb\Payments\Managers
 */
abstract class HCPaymentManager implements HCPaymentManagerContract
{
    /**
     * @var HCPaymentRepository
     */
    protected $paymentRepository;

    /**
     * @return string
     */
    abstract public function driver(): string;

    /**
     * HCPaymentManager constructor.
     * @param HCPaymentRepository $paymentRepository
     */
    public function __construct(HCPaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param HCPayment $payment
     * @param HCPaymentUserDTO $paymentUserDTO
     * @return string
     */
    abstract public function pay(HCPayment $payment, HCPaymentUserDTO $paymentUserDTO): string;

    /**
     * @param array $request
     * @return Response|null
     */
    abstract public function callback(array $request): ?Response;

    /**
     * @param HCPaymentDTO $dto
     * @return HCPayment
     * @throws HCException
     */
    public function create(HCPaymentDTO $dto): HCPayment
    {
        $dto->setDriver($this->driver());

        $payment = $this->paymentRepository->findOneBy(['order_number' => $dto->getOrderNumber()]);

        if ($payment) {
            throw new HCException(trans('HCPayments::payments.message.order_already_exist'));
        }

        return $this->paymentRepository->create($dto->toArray());
    }

    /**
     * @param HCPaymentDTO $paymentDTO
     * @param HCPaymentUserDTO $paymentUserDTO
     * @return string
     * @throws HCException
     */
    public function createAndPay(HCPaymentDTO $paymentDTO, HCPaymentUserDTO $paymentUserDTO): string
    {
        return $this->pay(
            $this->create($paymentDTO),
            $paymentUserDTO
        );
    }

    /**
     * @param $amount
     * @return int
     */
    protected function getCents(float $amount): int
    {
        return intval(number_format($amount, 2, '', ''));
    }
}
