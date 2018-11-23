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

use Cache;
use HoneyComb\Payments\Enum\HCPaymentStatusEnum;
use HoneyComb\Payments\Events\HCPaymentCanceled;
use HoneyComb\Payments\Events\HCPaymentCompleted;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use HoneyComb\Payments\Services\HCMakePayseraPaymentService;
use Illuminate\Database\Connection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/**
 * Class HCPayseraPaymentsController
 * @package HoneyComb\Payments\http\controllers
 */
class HCPayseraPaymentsController extends Controller
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCPaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var HCMakePayseraPaymentService
     */
    protected $payseraPaymentService;

    /**
     * HCPayseraPaymentsController constructor.
     * @param Connection $connection
     * @param HCPaymentRepository $paymentRepository
     * @param HCMakePayseraPaymentService $payseraPaymentService
     */
    public function __construct(
        Connection $connection,
        HCPaymentRepository $paymentRepository,
        HCMakePayseraPaymentService $payseraPaymentService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->connection = $connection;
        $this->payseraPaymentService = $payseraPaymentService;
    }

    /**
     * @param string $paymentId
     * @return View|RedirectResponse
     * @throws \ReflectionException
     */
    public function cancel(string $paymentId)
    {
        $payment = $this->paymentRepository->find($paymentId);

        if ($payment && $payment->status === HCPaymentStatusEnum::pending()->id()) {
            $payment->update([
                'status' => HCPaymentStatusEnum::canceled()->id(),
            ]);

            event(new HCPaymentCanceled($payment));
        }

        return app(config('payments.paysera.responseClass'))->cancelResponse($paymentId);
    }

    /**
     * @param string $paymentId
     * @return View|RedirectResponse
     */
    public function accept(string $paymentId)
    {
        return app(config('payments.paysera.responseClass'))->acceptResponse($paymentId);
    }

    /**
     * @param Request $request
     * @return Response|null
     * @throws \Exception
     */
    public function callback(Request $request): ?Response
    {
        $this->connection->beginTransaction();

        try {
            $response = $this->payseraPaymentService->parseParams($request->all());

            if ($response['status'] == 1) {
                $payment = $this->paymentRepository->findByOrderNumber($response['orderid']);

                if (is_null($payment)) {
                    throw new \Exception('Payment not found! Order number - ' . $response['orderid']);
                }

                if ($payment->isCanceled()) {
                    throw new \Exception('Trying to confirm canceled payment - ' . $response['orderid']);
                }

                if ($payment->isCompleted()) {
                    return response('OK', 200);
                }

                $this->payseraPaymentService->validateCallback($payment, $response);

                $payment->update([
                    'status' => HCPaymentStatusEnum::completed()->id(),
                    'configuration_value' => $response,
                ]);

                $this->connection->commit();

                event(new HCPaymentCompleted($payment));

                return response('OK', 200);
            }
        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            throw new \Exception($exception->getMessage());
        }

        logger()->info($response);

        // return null if paysera response is not equal to 1
        return null;
    }
}
