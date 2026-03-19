<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { InfiniteScroll } from '@inertiajs/vue3'

defineProps({
    posts: Object,
})

const serverCode = `return Inertia::render('InfiniteScroll', [
    // Inertia::scroll() auto-configures merge + pagination metadata
    'posts' => Inertia::scroll(fn () => Post::latest()->paginate(10)),
]);`
</script>

<template>
    <AppLayout>
        <Head title="Infinite Scroll" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Infinite Scroll</h1>
            <p class="text-gray-600 mt-1">Automatically loads more content as you scroll down — powered by InfiniteScroll component + Merge Props.</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Merge Props" color="yellow" />
                <FeatureBadge label="UX" color="blue" />
            </div>
        </div>

        <CodeBlock title="Server-side — Inertia::scroll()" :code="serverCode" lang="php" />

        <div class="text-sm text-gray-500 mb-4">
            Showing {{ posts.data.length }} of {{ posts.total }} posts — scroll down to load more
        </div>

        <InfiniteScroll data="posts" class="space-y-4">
            <div
                v-for="post in posts.data"
                :key="post.id"
                class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ post.title }}</h3>
                        <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ post.body }}</p>
                        <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                            <span>{{ post.views.toLocaleString() }} views</span>
                            <FeatureBadge
                                :label="post.status"
                                :color="post.status === 'published' ? 'green' : post.status === 'draft' ? 'yellow' : 'red'"
                            />
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 ml-4">#{{ post.id }}</span>
                </div>
            </div>

            <template #loading>
                <div class="flex justify-center py-8">
                    <div class="flex items-center gap-3 text-gray-500">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Loading more posts...
                    </div>
                </div>
            </template>
        </InfiniteScroll>
    </AppLayout>
</template>
