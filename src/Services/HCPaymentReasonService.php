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

use HoneyComb\Payments\Models\HCPaymentReason;
use HoneyComb\Payments\Repositories\HCPaymentReasonRepository;

/**
 * Class HCPaymentReasonService
 * @package HoneyComb\Payments\Services
 */
class HCPaymentReasonService
{
    /**
     * @var HCPaymentReasonRepository
     */
    private $repository;

    /**
     * HCPaymentReasonService constructor.
     * @param HCPaymentReasonRepository $repository
     */
    public function __construct(
        HCPaymentReasonRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @return HCPaymentReasonRepository
     */
    public function getRepository(): HCPaymentReasonRepository
    {
        return $this->repository;
    }

    /**
     * @param string $reasonId
     * @param string $translationKey
     * @param string|null $translationKeyDescription
     * @return HCPaymentReason
     */
    public function createReason(
        string $reasonId,
        string $translationKey,
        string $translationKeyDescription = null
    ): HCPaymentReason {
        return $this->repository
            ->create([
                'id' => $reasonId,
                'translation_key' => $translationKey,
                'translation_key_description' => $translationKeyDescription,
            ]);
    }

    /**
     * @param string $reasonId
     * @param string $translationKey
     * @param string|null $translationKeyDescription
     * @return HCPaymentReason
     */
    public function updateReason(
        string $reasonId,
        string $translationKey,
        string $translationKeyDescription = null
    ): HCPaymentReason {
        $paymentReason = $this->repository->findOrFail($reasonId);

        $paymentReason->update([
            'translation_key' => $translationKey,
            'translation_key_description' => $translationKeyDescription,
        ]);

        return $paymentReason;
    }

    /**
     * @param string $reasonId
     */
    public function deleteReason(string $reasonId): void
    {
        $this->repository->makeQuery()
            ->where(['id' => $reasonId])
            ->delete();
    }
}
