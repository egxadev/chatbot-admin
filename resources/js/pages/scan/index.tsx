import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import Pusher from 'pusher-js';
import { QRCodeSVG } from 'qrcode.react';
import { useState } from 'react';

export default function ScanIndex() {
    const { breadcrumbs } = usePage<{
        breadcrumbs: BreadcrumbItem[];
    }>().props;

    const [qrCodeData, setQrCodeData] = useState<string>('');

    const pusher = new Pusher(import.meta.env.VITE_REVERB_APP_KEY, {
        cluster: '',
        enabledTransports: ['ws', 'wss'],
        forceTLS: false,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
    });

    pusher.subscribe('qr-code');

    pusher.bind('qr.received', (data: { qrCode: string }) => {
        setQrCodeData(data.qrCode);
    });
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={breadcrumbs[0].title} />

            <div className="px-4 py-6">
                <div className="overflow-hidden bg-muted dark:bg-neutral-900 sm:rounded-lg">
                    <div className="p-6 text-gray-900 dark:text-neutral-200">
                        <div className="mt-4">
                            <h2 className="mb-2 text-lg font-semibold">Generated QR Code:</h2>
                            <div className="flex justify-center">
                                {qrCodeData ? (
                                    <QRCodeSVG value={qrCodeData} size={256} level="H" includeMargin={true} className="rounded-lg bg-white p-2" />
                                ) : (
                                    <div className="text-center">CONNECTED</div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
