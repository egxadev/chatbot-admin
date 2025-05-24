import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Textarea } from '@/components/ui/textarea';
import { PageProps } from '@/types';
import { KnowledgeBase } from '@/types/knowledge-base';
import { zodResolver } from '@hookform/resolvers/zod';
import { router, usePage } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

const formSchema = z.object({
    title: z.string().min(2, { message: 'Title must be at least 2 characters.' }),
    content: z.string().min(2, { message: 'Content must be at least 2 characters.' }),
    status: z.number(),
});

export function KnowledgeBaseForm({
    mode,
    knowledgeBase,
    className,
}: PageProps<{
    mode: 'create' | 'edit';
    knowledgeBase?: KnowledgeBase;
    className: string;
}>) {
    const { errors } = usePage().props;

    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: knowledgeBase?.title,
            content: knowledgeBase?.content,
            status: knowledgeBase?.status,
        },
    });

    function onSubmit(values: z.infer<typeof formSchema>) {
        const isCreateMode = mode === 'create';
        const url = isCreateMode ? '/knowledge-bases' : `/knowledge-bases/${knowledgeBase?.id}`;

        if (isCreateMode) {
            router.post(url, values, {
                onSuccess: () => {
                    console.log('Knowledge base created successfully.');
                },
                onError: () => {
                    console.error('Failed to create knowledge base.');
                },
            });
        } else {
            router.put(url, values, {
                onSuccess: () => {
                    console.log('Knowledge base updated successfully.');
                },
                onError: () => {
                    console.error('Failed to update knowledge base.');
                },
            });
        }
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className={`${className} space-y-8`}>
                <FormField
                    control={form.control}
                    name="title"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Title</FormLabel>
                            <FormControl>
                                <Input {...field} />
                            </FormControl>
                            <FormMessage>{errors.title}</FormMessage>
                        </FormItem>
                    )}
                />

                <FormField
                    control={form.control}
                    name="content"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Content</FormLabel>
                            <FormControl>
                                <Textarea {...field} />
                            </FormControl>
                            <FormMessage>{errors.content}</FormMessage>
                        </FormItem>
                    )}
                />

                {/* radio status active and inactive */}
                <FormField
                    control={form.control}
                    name="status"
                    render={({ field }) => (
                        <FormItem>
                            <FormLabel>Status</FormLabel>
                            <FormControl className="flex items-center space-x-2">
                                <RadioGroup defaultValue={field.value?.toString()} onValueChange={(value) => field.onChange(Number(value))}>
                                    <div className="flex items-center space-x-2">
                                        <RadioGroupItem value="1" id="1" />
                                        <Label htmlFor="1">Active</Label>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <RadioGroupItem value="0" id="0" />
                                        <Label htmlFor="0">Inactive</Label>
                                    </div>
                                </RadioGroup>
                            </FormControl>
                            <FormMessage>{errors.status}</FormMessage>
                        </FormItem>
                    )}
                />
                <Button type="submit">Submit</Button>
            </form>
        </Form>
    );
}
