<?php

namespace HoneyComb\Payments\Events;

use HoneyComb\Payments\Models\HCPayment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class HCPaymentCreated
 * @package HoneyComb\Payments\Events
 */
class HCPaymentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var HCPayment
     */
    public $payment;

    /**
     * HCPaymentCreated constructor.
     * @param HCPayment $payment
     */
    public function __construct(HCPayment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
