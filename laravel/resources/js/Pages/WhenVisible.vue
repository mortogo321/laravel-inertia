<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { WhenVisible } from '@inertiajs/vue3'

defineProps({
    intro: String,
    // These are loaded when their section scrolls into view:
    popularPosts: Array,
    recentComments: Array,
    tagCloud: Array,
})

const serverCode = `return Inertia::render('WhenVisible', [
    'intro' => 'Scroll down to trigger lazy loading...',

    // These use Inertia::optional() — only sent when explicitly requested
    'popularPosts' => Inertia::optional(
        fn () => Post::orderByDesc('views')->take(5)->get()
    ),
    'recentComments' => Inertia::optional(
        fn () => Comment::with('post')->latest()->take(5)->get()
    ),
    'tagCloud' => Inertia::optional(
        fn () => Tag::withCount('posts')->get()
    ),
]);`
</script>

<template>
    <AppLayout>
        <Head title="WhenVisible" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">WhenVisible (Lazy Load on Scroll)</h1>
            <p class="text-gray-600 mt-1">Data loads only when its container scrolls into the viewport. Scroll down to see it!</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Performance" color="green" />
                <FeatureBadge label="Lazy Loading" color="blue" />
            </div>
        </div>

        <CodeBlock title="Inertia::optional() + WhenVisible component" :code="serverCode" lang="php" />

        <!-- Spacer to force scroll -->
        <div class="bg-gradient-to-b from-blue-50 to-white rounded-xl border p-12 text-center mb-8">
            <p class="text-xl text-gray-600">{{ intro }}</p>
            <p class="text-gray-400 mt-2">The sections below use WhenVisible to lazy-load their data</p>
            <svg class="w-8 h-8 mx-auto mt-4 text-gray-400 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>

        <!-- Spacer -->
        <div class="h-64 flex items-center justify-center text-gray-400 text-sm">
            Keep scrolling...
        </div>

        <!-- WhenVisible: Popular Posts -->
        <div class="mb-8">
        <WhenVisible data="popularPosts">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Popular Posts</h2>
                    <div class="animate-pulse space-y-3">
                        <div v-for="i in 5" :key="i" class="h-12 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Popular Posts (loaded on scroll!)</h2>
                <div v-for="(post, i) in popularPosts" :key="post.id" class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50">
                    <span class="text-2xl font-bold text-gray-300">#{{ i + 1 }}</span>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ post.title }}</div>
                        <div class="text-sm text-gray-500">{{ post.views.toLocaleString() }} views</div>
                    </div>
                </div>
            </div>
        </WhenVisible>
        </div>

        <div class="h-32"></div>

        <!-- WhenVisible: Recent Comments -->
        <div class="mb-8">
        <WhenVisible data="recentComments">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Comments</h2>
                    <div class="animate-pulse space-y-3">
                        <div v-for="i in 5" :key="i" class="h-16 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Comments (loaded on scroll!)</h2>
                <div v-for="comment in recentComments" :key="comment.id" class="bg-gray-50 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-medium text-sm text-gray-900">{{ comment.author }}</span>
                        <span class="text-xs text-gray-500">on: {{ comment.post?.title }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ comment.body }}</p>
                </div>
            </div>
        </WhenVisible>
        </div>

        <div class="h-32"></div>

        <!-- WhenVisible: Tag Cloud -->
        <WhenVisible data="tagCloud">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tag Cloud</h2>
                    <div class="animate-pulse flex flex-wrap gap-2">
                        <div v-for="i in 8" :key="i" class="h-8 w-20 bg-gray-200 rounded-full"></div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tag Cloud (loaded on scroll!)</h2>
                <div class="flex flex-wrap gap-3">
                    <span
                        v-for="tag in tagCloud"
                        :key="tag.id"
                        class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 font-medium"
                        :style="{ fontSize: Math.max(0.75, 0.75 + tag.posts_count * 0.15) + 'rem' }"
                    >
                        {{ tag.name }} ({{ tag.posts_count }})
                    </span>
                </div>
            </div>
        </WhenVisible>
    </AppLayout>
</template>
