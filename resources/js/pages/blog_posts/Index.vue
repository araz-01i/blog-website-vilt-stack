<script setup lang="ts">
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Search, SquarePen, Trash2 } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch, defineProps } from 'vue';

const props = defineProps({
    blog_posts: Object,
    filters: Object,
});

const searchText = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

// Debounced search function
let timeout;
const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(
            route('blog-posts.index'),
            {
                search: searchText.value,
                status: statusFilter.value,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);
};

// Delete post function
const deletePost = (postId) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('blog-posts.destroy', postId), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Your post has been deleted.',
                        icon: 'success',
                    });
                },
                onError: () => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong while deleting the post.',
                        icon: 'error',
                    });
                },
            });
        }
    });
};

// Watch for changes in search or status filter
watch(searchText, debouncedSearch);
watch(statusFilter, debouncedSearch);
</script>

<template>
    <Head title="Blog Posts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white">Blog Posts</h1>
                    </div>

                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('blog-posts.create')"
                        class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 px-3 py-2 text-sm font-medium text-white shadow-sm transition-all hover:from-blue-700 hover:to-blue-600 focus:ring-4 focus:ring-blue-300/50 focus:outline-none sm:px-4 sm:py-2.5 dark:focus:ring-blue-800/50"
                    >
                        <Plus class="size-5" />
                        <span class="hidden sm:inline">New Post</span>
                    </Link>
                </div>
            </div>

            <div
                class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:p-4 dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="relative w-full sm:max-w-xs">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <Search class="size-5 text-gray-400 sm:h-5 sm:w-5" />
                    </div>
                    <Input v-model="searchText" class="w-full pl-8 text-sm sm:pl-10 sm:text-base" placeholder="Search posts..." />
                </div>

                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <div class="">
                        <select
                            v-model="statusFilter"
                            id="status"
                            class="block rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        >
                            <option value="">All Statuses</option>
                            <option value="Published">Published</option>
                            <option value="Unpublished">Unpublished</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Table (Responsive) -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                scope="col"
                                class="px-4 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase sm:px-6 sm:py-3 dark:text-gray-300"
                            >
                                Title
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase sm:table-cell sm:px-6 sm:py-3 dark:text-gray-300"
                            >
                                Status
                            </th>
                            <th scope="col" class="relative px-4 py-2 sm:px-6 sm:py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        <tr v-for="post in blog_posts.data" :key="post.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 whitespace-nowrap sm:px-6 sm:py-4">{{ post.title }}</td>
                            <td class="px-3 py-3 text-xs whitespace-nowrap sm:table-cell sm:px-6 sm:py-4 sm:text-sm">
                                <span
                                    :class="{
                                        'badge badge-success': post.status === 'Published',
                                        'badge badge-warning': post.status === 'Unpublished',
                                    }"
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                >
                                    {{ post.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs font-medium whitespace-nowrap sm:px-6 sm:py-4 sm:text-sm">
                                <div class="flex justify-center gap-1 sm:gap-2">
                                    <Link
                                        :href="route('blog-posts.edit', post.id)"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        <SquarePen class="size-5" />
                                    </Link>
                                    <button
                                        @click="deletePost(post.id)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        <Trash2 class="size-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="blog_posts.data.length === 0">
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">No posts found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="blog_posts.links && blog_posts.links.length > 3" class="mt-4 flex items-center justify-between">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="blog_posts.prev_page_url"
                        :href="blog_posts.prev_page_url"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="blog_posts.next_page_url"
                        :href="blog_posts.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ blog_posts.from }}</span>
                            to
                            <span class="font-medium">{{ blog_posts.to }}</span>
                            of
                            <span class="font-medium">{{ blog_posts.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link
                                v-for="(link, i) in blog_posts.links"
                                :key="i"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-medium text-white focus:z-20'
                                        : 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20',
                                    !link.url ? 'cursor-not-allowed opacity-50' : '',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === blog_posts.links.length - 1 ? 'rounded-r-md' : '',
                                ]"
                            >
                                <span v-if="link.label === '&laquo; Previous'">&laquo; Previous</span>
                                <span v-else-if="link.label === 'Next &raquo;'">Next &raquo;</span>
                                <span v-else v-html="link.label.replace(/(<([^>]+)>)/gi, '')"></span>
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
