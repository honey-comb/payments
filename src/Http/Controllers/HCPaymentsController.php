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

use App\Enum\HCPaymentStatusEnum;
use Cache;
use HoneyComb\Payments\Events\HCPaymentCanceled;
use HoneyComb\Payments\Events\HCPaymentCompleted;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use HoneyComb\Payments\Services\HCMakePayseraPaymentService;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class HCPaymentsController
 * @package HoneyComb\Payments\http\controllers
 */
class HCPaymentsController extends Controller
{
    /**
     * @var HCPaymentRepository
     */
    private $paymentRepository;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var HCMakePayseraPaymentService
     */
    private $payseraPaymentService;

    /**
     * HCPaymentsController constructor.
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
     * @throws \ReflectionException
     */
    public function cancel(string $paymentId): void
    {
        $payment = $this->paymentRepository->findOrFail($paymentId);

        if ($payment->status === HCPaymentStatusEnum::pending()->id()) {
            $payment->update([
                'status' => HCPaymentStatusEnum::canceled()->id(),
            ]);

            event(new HCPaymentCanceled($payment));
        }
    }

    /**
     * @param string $paymentId
     * @throws \ReflectionException
     */
    public function accept(string $paymentId): void
    {
        $payment = $this->paymentRepository->findOrFail($paymentId);

        if ($payment->status === HCPaymentStatusEnum::pending()->id()) {
            $payment->update([
                'status' => HCPaymentStatusEnum::completed()->id(),
            ]);

            event(new HCPaymentCompleted($payment));
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function callback(Request $request): void
    {
        $this->connection->beginTransaction();

        try {
            $response = $this->payseraPaymentService->parseParams($request->all());

            if ($response['status'] == 1) {
                $payment = $this->paymentRepository->findOrFail($response['orderid']);

                $payment->update([
                    'configuration_value' => $response
                ]);

                if ($payment->status === HCPaymentStatusEnum::completed()->id()) {
                    return response('OK', 200);
                }

                $this->payseraPaymentService->validateCallback($payment, $response);

                $this->connection->commit();

                return response('OK', 200);
            }
        } catch (\Exception $e) {
            $this->connection->rollback();

            logger()->error($e->getMessage(), $e->getTrace());
        }
    }
}
