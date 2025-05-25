<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QrCodeReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $qrCode;

    public function __construct($qrCode)
    {
        $this->qrCode = $qrCode;
    }

    public function broadcastWith()
    {
        return ['qrCode' => $this->qrCode];
    }

    public function broadcastOn()
    {
        return new Channel('qr-code');
    }

    public function broadcastAs()
    {
        return 'qr.received';
    }
}
