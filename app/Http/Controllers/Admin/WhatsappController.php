<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\QrCodeReceived;

class WhatsappController extends Controller
{
    public function scanQrCode()
    {
        $breadcrumbs = [
            [
                'title' => 'Scan QR Code',
                'href' => route('scan.index')
            ]
        ];

        return inertia('scan/index', array_merge(
            ['breadcrumbs' => $breadcrumbs],
        ));
    }

    public function QrCodeReceived(Request $request)
    {
        try {
            $encryptedQR = $request->input('qr_code');
            $decryptedQR = decryptData($encryptedQR);

            \Log::info($decryptedQR);

            // Broadcast the QR code to all clients
            broadcast(new QrCodeReceived($decryptedQR))->toOthers();

            return response()->json(['message' => 'QR code received and decrypted successfully']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to process QR code'], 500);
        }
    }
}
