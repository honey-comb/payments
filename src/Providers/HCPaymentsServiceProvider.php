<?php
/**
 * @copyright 2018 interactivesolutions
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

namespace HoneyComb\Payments\Providers;

use HoneyComb\Payments\Repositories\HCPaymentMethodRepository;
use HoneyComb\Payments\Repositories\HCPaymentReasonRepository;
use HoneyComb\Payments\Repositories\HCPaymentRepository;
use HoneyComb\Payments\Services\HCMakePaymentService;
use HoneyComb\Payments\Services\HCMakePayseraPaymentService;
use HoneyComb\Starter\Providers\HCBaseServiceProvider;

/**
 * Class HCPaymentsServiceProvider
 * @package HoneyComb\Payments\Providers
 */
class HCPaymentsServiceProvider extends HCBaseServiceProvider
{
    /**
     * @var string
     */
    protected $homeDirectory = __DIR__;

    /**
     * Console commands
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Controller namespace
     *
     * @var string
     */
    protected $namespace = '\HoneyComb\Payments\Http\Controllers';

    /**
     *
     */
    public function register(): void
    {
        $this->registerLibraries();

        $this->registerConfig();
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     *
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(HCPaymentRepository::class);
        $this->app->singleton(HCPaymentMethodRepository::class);
        $this->app->singleton(HCPaymentReasonRepository::class);
    }

    /**
     *
     */
    private function registerServices(): void
    {
        $this->app->singleton(HCMakePaymentService::class);
        $this->app->singleton(HCMakePayseraPaymentService::class);
    }

    /**
     *
     */
    private function registerLibraries(): void
    {
        require_once(__DIR__ . './../libraries/paysera/WebToPay.php');
    }

    /**
     *
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . './../config/payments.php', 'payments'
        );
    }
}
