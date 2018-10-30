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

namespace HoneyComb\Payments\Paysera;

use HoneyComb\Payments\Contracts\PayseraResponseContract;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class HCPayseraResponse
 * @package HoneyComb\Payments\Paysera
 */
class HCPayseraResponse implements PayseraResponseContract
{
    /**
     * @var HCPaymentRepository
     */
    private $paymentRepository;

    /**
     * HCPayseraResponse constructor.
     * @param HCPaymentRepository $paymentRepository
     */
    public function __construct(HCPaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param string $paymentId
     * @return View
     */
    public function acceptResponse(string $paymentId): View
    {
        $payment = $this->paymentRepository->find($paymentId);

        return view('HCPayments::accept', ['payment' => $payment]);
    }

    /**
     * @param string $paymentId
     * @return View|RedirectResponse
     */
    public function cancelResponse(string $paymentId)
    {
        $payment = $this->paymentRepository->find($paymentId);

        return view('HCPayments::cancel', ['payment' => $payment]);
    }
}
