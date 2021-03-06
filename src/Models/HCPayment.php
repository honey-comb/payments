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

namespace HoneyComb\Payments\Models;

use HoneyComb\Payments\Enum\HCPaymentStatusEnum;
use HoneyComb\Starter\Models\HCUuidSoftModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class HCPayment
 * @package HoneyComb\Payments\Models
 */
class HCPayment extends HCUuidSoftModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'driver',
        'status',
        'amount',
        'currency',
        'order_number',
        'order',
        'reason',
        'method',
        'invoice_id',
        'ownerable_type',
        'ownerable_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'order' => 'array',
        'amount' => 'double',
    ];

    /**
     * @return MorphTo
     */
    public function ownable(): MorphTo
    {
        return $this->morphTo('ownerable');
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function isPending(): bool
    {
        return $this->status == HCPaymentStatusEnum::pending()->id();
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function isCompleted(): bool
    {
        return $this->status == HCPaymentStatusEnum::completed()->id();
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function isCanceled(): bool
    {
        return $this->status == HCPaymentStatusEnum::canceled()->id();
    }
}
