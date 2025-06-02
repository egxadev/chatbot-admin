<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\QrCodeReceived;
use App\Traits\ResponseFormatter;
class WhatsappController extends Controller
{
    /**
     * Display QR code scanning page.
     * 
     * @return \Inertia\Response
     */
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

    /**
     * Handle QR code received from request.
     * Decrypts QR code if status is 'qr' and broadcasts via event.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function QrCodeReceived(Request $request)
    {
        try {
            if ($request->input('status') == 'qr') {
                $encryptedQR = $request->input('qr_code');
                $decryptedQR = decryptData($encryptedQR);
            } else {
                $decryptedQR = null;
            }

            QrCodeReceived::dispatch($decryptedQR);

            return response()->json(ResponseFormatter::successResponse('QR code received.'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(ResponseFormatter::errorResponse('Failed to process QR code'));
        }
    }
}
