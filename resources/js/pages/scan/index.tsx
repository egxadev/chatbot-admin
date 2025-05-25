import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import Echo from 'laravel-echo';
import { useEffect, useState } from 'react';

export default function ScanIndex() {
    const { breadcrumbs } = usePage<{
        breadcrumbs: BreadcrumbItem[];
    }>().props;

    const [qrCode, setQrCode] = useState<string>('');

    useEffect(() => {
        // Subscribe to the QR code channel
        const echo = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        echo.channel('qr-code').listen('QrCodeReceived', (e: { qrCode: string }) => {
            setQrCode(e.qrCode);
        });

        return () => {
            echo.leave('qr-code');
        };
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={breadcrumbs[0].title} />

            <div className="px-4 py-6">
                <div className="bg-muted overflow-hidden sm:rounded-lg">
                    <div className="p-6 text-gray-900 dark:text-neutral-200">
                        <div>
                            <h2 className="mb-2 text-lg font-semibold">QR Code Data:</h2>
                            <pre className="rounded bg-gray-100 p-4">{qrCode || 'Waiting for QR code...'}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">{/* scan qr code */}</div>
        </AppLayout>
    );
}
