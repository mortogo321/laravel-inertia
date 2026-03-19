<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    post: Object,
})
</script>

<template>
    <AppLayout>
        <Head :title="post.title" />

        <div class="mb-6">
            <Link href="/posts" class="text-red-600 hover:text-red-800 text-sm">&larr; Back to Posts</Link>
        </div>

        <article class="bg-white rounded-xl shadow-sm border p-8">
            <div class="flex items-center gap-3 mb-4">
                <FeatureBadge
                    :label="post.status"
                    :color="post.status === 'published' ? 'green' : post.status === 'draft' ? 'yellow' : 'red'"
                />
                <span class="text-sm text-gray-500">{{ post.views.toLocaleString() }} views</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ post.title }}</h1>

            <div class="flex flex-wrap gap-2 mb-6">
                <FeatureBadge v-for="tag in post.tags" :key="tag.id" :label="tag.name" color="blue" />
            </div>

            <div class="prose max-w-none text-gray-700 whitespace-pre-line">{{ post.body }}</div>

            <!-- Comments -->
            <div class="mt-10 border-t pt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    Comments ({{ post.comments.length }})
                </h2>
                <div v-for="comment in post.comments" :key="comment.id" class="bg-gray-50 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-900">{{ comment.author }}</span>
                        <span class="text-xs text-gray-500">{{ comment.created_at }}</span>
                    </div>
                    <p class="text-gray-700 text-sm">{{ comment.body }}</p>
                </div>
            </div>
        </article>
    </AppLayout>
</template>
