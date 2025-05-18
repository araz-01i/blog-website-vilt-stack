<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Props for blog post and authors list
const props = defineProps({
    blog_post: {
        type: Object,
        required: true,
    },
    authors: {
        type: Array,
        default: () => [],
    },
    breadcrumbs: {
        type: Array,
        default: undefined,
    },
});

// Compute breadcrumbs after props are available
const breadcrumbs = props.breadcrumbs ?? [
    { name: 'Blog Posts', href: route('blog-posts.index') },
    { name: 'Edit', href: route('blog-posts.edit', props.blog_post), current: true },
];

const form = useForm({
    title: props.blog_post.title,
    content: props.blog_post.content,
    status: props.blog_post.status,
    author_id: props.blog_post.author_id.toString(),
});

const submit = () => {
    form.put(route('blog-posts.update', props.blog_post.id), {
        onSuccess: () => {
            // Optional: Add any success handling here
        },
    });
};
</script>

<template>
    <Head :title="`Edit ${blog_post.title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-lg bg-gray-50 p-4 sm:gap-6 sm:p-6 dark:bg-gray-800">
            <!-- Header Section -->
            <div class="flex flex-col gap-2 border-b border-gray-200 pb-4 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white">Edit Blog Post</h1>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left column for main content -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Title field -->
                        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-900">
                            <div class="grid gap-2">
                                <Label for="title" class="text-base font-medium sm:text-lg">Blog Title</Label>
                                <Input
                                    id="title"
                                    type="text"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    v-model="form.title"
                                    placeholder="Enter an engaging title"
                                    class="text-sm sm:text-base"
                                />
                                <InputError :message="form.errors.title" />
                            </div>
                        </div>

                        <!-- Content field -->
                        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-900">
                            <div class="grid gap-2">
                                <Label for="content" class="text-base font-medium sm:text-lg">Content</Label>
                                <textarea
                                    id="content"
                                    required
                                    :tabindex="2"
                                    v-model="form.content"
                                    placeholder="Write your blog post content here..."
                                    rows="5"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 focus:outline-none sm:text-base dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                ></textarea>
                                <InputError :message="form.errors.content" />
                            </div>
                        </div>
                    </div>

                    <!-- Right column for metadata -->
                    <div class="space-y-6">
                        <!-- Publishing options card -->
                        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-900">
                            <h2 class="mb-4 text-lg font-medium text-gray-900 sm:text-xl dark:text-white">Publishing Options</h2>

                            <!-- Status field -->
                            <div class="mb-4 grid gap-2">
                                <Label for="status" class="text-sm font-medium sm:text-base">Post Status</Label>
                                <select
                                    v-model="form.status"
                                    id="status"
                                    class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 focus:outline-none sm:text-base dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                >
                                    <option value="Published">Published</option>
                                    <option value="Unpublished">Unpublished</option>
                                </select>
                                <InputError :message="form.errors.status" />
                            </div>

                            <!-- Author field -->
                            <div class="grid gap-2">
                                <Label for="author_id" class="text-sm font-medium sm:text-base">Author</Label>
                                <select
                                    v-model="form.author_id"
                                    id="author_id"
                                    class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 focus:outline-none sm:text-base dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                    required
                                >
                                    <option value="" disabled>Select an author</option>
                                    <option v-for="author in authors" :key="author.id" :value="author.id.toString()">
                                        {{ author.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.author_id" />
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-900">
                            <div class="flex flex-col gap-3">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:outline-none active:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 sm:text-base"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Updating...' : 'Update Blog Post' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
