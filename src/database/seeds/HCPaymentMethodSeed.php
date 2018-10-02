<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Payments\Database\Seeds;

use App\Enum\HCPaymentMethodEnum;
use HoneyComb\Payments\Repositories\HCPaymentMethodRepository;
use Illuminate\Database\Seeder;

/**
 * Class HCPaymentMethodSeed
 * @package HoneyComb\Core\Database\Seeds
 */
class HCPaymentMethodSeed extends Seeder
{
    /**
     * @var HCPayment
     */
    private $repository;

    /**
     * HCUserRolesSeed constructor.
     * @param HCPaymentMethodRepository $repository
     */
    public function __construct(HCPaymentMethodRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \ReflectionException
     */
    public function run(): void
    {
        $list = [
            [
                'id' => HCPaymentMethodEnum::paysera()->id(),
                'label' => HCPaymentMethodEnum::paysera()->name(),
                'is_active' => true,
            ],
            [
                'id' => HCPaymentMethodEnum::paypal()->id(),
                'label' => HCPaymentMethodEnum::paypal()->name(),
                'is_active' => false,
            ],
        ];

        foreach ($list as $method) {
            $this->repository->updateOrCreate([
                'id' => $method['id'],
            ], [
                'label' => $method['label'],
                'is_active' => $method['is_active'],
            ]);
        }
    }
}
