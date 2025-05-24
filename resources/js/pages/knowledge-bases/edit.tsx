import Heading from '@/components/heading';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, PageProps } from '@/types';
import { KnowledgeBase } from '@/types/knowledge-base';
import { Head } from '@inertiajs/react';
import { KnowledgeBaseForm } from './partials/form';

export default function KnowledgeBaseEdit({
    breadcrumbs,
    knowledgeBase,
}: PageProps<{
    breadcrumbs: BreadcrumbItem[];
    knowledgeBase: KnowledgeBase;
}>) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={breadcrumbs[0].title} />

            <div className="w-full p-4">
                <div className="mx-auto space-y-6">
                    <Heading title="Knowledge Base" description="Update a knowledge base" />

                    <KnowledgeBaseForm mode="edit" knowledgeBase={knowledgeBase} className="max-w-xl" />
                </div>
            </div>
        </AppLayout>
    );
}
