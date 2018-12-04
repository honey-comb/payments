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

namespace HoneyComb\Payments\Http\Controllers;

use HoneyComb\Payments\Enum\HCPaymentDriverEnum;
use HoneyComb\Payments\Services\HCPaymentService;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/**
 * Class HCPayseraController
 * @package HoneyComb\Payments\Http\Controllers
 */
class HCPayseraController extends Controller
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
     * HCPayseraPaymentsController constructor.
     * @param Connection $connection
     * @param HCPaymentService $paymentService
     */
    public function __construct(Connection $connection, HCPaymentService $paymentService)
    {
        $this->connection = $connection;
        $this->paymentService = $paymentService;
    }

    /**
     * @param string $paymentId
     * @return View|RedirectResponse
     * @throws \ReflectionException
     */
    public function accept(string $paymentId)
    {
        // confirm order when paysera payments are in test mode
        if (config('payments.drivers.paysera.test')) {
            $this->paymentService->confirm($paymentId);
        }

        return app(config('payments.drivers.paysera.responseClass'))->acceptResponse($paymentId);
    }

    /**
     * @param string $paymentId
     * @return mixed
     * @throws \ReflectionException
     */
    public function cancel(string $paymentId)
    {
        $this->paymentService->cancel($paymentId);

        return app(config('payments.drivers.paysera.responseClass'))->cancelResponse($paymentId);
    }

    /**
     * @param Request $request
     * @return Response|null
     * @throws \Throwable
     */
    public function callback(Request $request): ?Response
    {
        $this->connection->beginTransaction();

        try {
            $response = $this->paymentService->driver(
                HCPaymentDriverEnum::paysera()->id()
            )->callback($request->all());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            throw $exception;
        }

        return $response;
    }
}
