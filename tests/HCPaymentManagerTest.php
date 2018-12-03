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

namespace Tests;

use HoneyComb\Payments\Contracts\HCPaymentManagerContract;
use HoneyComb\Payments\DTO\HCPaymentUserDTO;
use HoneyComb\Payments\Managers\HCPaymentManager;
use HoneyComb\Payments\Models\HCPayment;
use Illuminate\Http\Response;

/**
 * Class HCPaymentManagerTest
 * @package Tests
 */
class HCPaymentManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_must_return_ok(): void
    {
        $class = $this->getTestClassInstance();

        $this->assertSame(5000, $class->getCents(50.00));
        $this->assertSame(5999, $class->getCents(59.99));
        $this->assertSame(12313, $class->getCents(123.129));

        $this->assertTrue(true);
    }

    /**
     * @return HCTestPaymentManager
     */
    private function getTestClassInstance(): HCTestPaymentManager
    {
        return $this->app->make(HCTestPaymentManager::class);
    }
}

class HCTestPaymentManager extends HCPaymentManager implements HCPaymentManagerContract
{
    public function driver(): string
    {
        return 'custom';
    }

    public function pay(HCPayment $payment, HCPaymentUserDTO $paymentUserDTO): string
    {
        return 'redirect_url';
    }

    public function callback(array $request): ?Response
    {
        return response('OK', 200);
    }

    public function getCents(float $amount): int
    {
        return parent::getCents($amount);
    }
}
