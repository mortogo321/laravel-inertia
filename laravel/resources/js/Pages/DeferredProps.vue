<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { Deferred } from '@inertiajs/vue3'

defineProps({
    post: Object,
    // These are deferred — loaded after initial render
    comments: Array,
    analytics: Object,
    relatedPosts: Array,
})

const serverCode = `return Inertia::render('DeferredProps', [
    'post' => Post::first(),

    // Deferred: loaded after initial page render
    'comments' => Inertia::defer(fn () => Comment::latest()->take(5)->get()),

    // Group deferred props together
    'analytics' => Inertia::defer(fn () => [
        'views_today' => rand(100, 5000),
        'avg_time' => rand(30, 300) . 's',
        'bounce_rate' => rand(20, 80) . '%',
    ]),

    // Different group — loaded independently
    'relatedPosts' => Inertia::defer(
        fn () => Post::inRandomOrder()->take(3)->get(),
        'sidebar'
    ),
]);`
</script>

<template>
    <AppLayout>
        <Head title="Deferred Props" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Deferred Props</h1>
            <p class="text-gray-600 mt-1">Heavy data loads after the initial page render for instant perceived performance.</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Performance" color="green" />
            </div>
        </div>

        <CodeBlock title="Server-side (Laravel Controller)" :code="serverCode" lang="php" />

        <!-- Post loads instantly -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Instant: Post Data</h2>
            <p class="text-sm text-gray-500 mb-3">This loads with the initial page render.</p>
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium">{{ post.title }}</h3>
                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ post.body }}</p>
            </div>
        </div>

        <!-- Deferred: Comments -->
        <Deferred data="comments">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred: Comments</h2>
                    <div class="space-y-3">
                        <div v-for="i in 3" :key="i" class="animate-pulse">
                            <div class="h-4 bg-gray-200 rounded w-1/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred: Comments (loaded!)</h2>
                <div v-for="comment in comments" :key="comment.id" class="bg-gray-50 rounded-lg p-3 mb-2">
                    <span class="font-medium text-sm">{{ comment.author }}</span>
                    <p class="text-sm text-gray-600">{{ comment.body }}</p>
                </div>
            </div>
        </Deferred>

        <!-- Deferred: Analytics -->
        <Deferred data="analytics">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred: Analytics</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="i in 3" :key="i" class="animate-pulse">
                            <div class="h-8 bg-gray-200 rounded mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred: Analytics (loaded!)</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-700">{{ analytics.views_today }}</div>
                        <div class="text-sm text-blue-600">Views Today</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-700">{{ analytics.avg_time }}</div>
                        <div class="text-sm text-green-600">Avg Time</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <div class="text-2xl font-bold text-orange-700">{{ analytics.bounce_rate }}</div>
                        <div class="text-sm text-orange-600">Bounce Rate</div>
                    </div>
                </div>
            </div>
        </Deferred>

        <!-- Deferred: Related Posts (different group) -->
        <Deferred data="relatedPosts">
            <template #fallback>
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred (sidebar group): Related Posts</h2>
                    <div class="animate-pulse space-y-3">
                        <div v-for="i in 3" :key="i" class="h-12 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </template>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Deferred (sidebar group): Related Posts (loaded!)</h2>
                <div v-for="rp in relatedPosts" :key="rp.id" class="bg-gray-50 rounded-lg p-3 mb-2">
                    <span class="font-medium text-sm">{{ rp.title }}</span>
                    <span class="text-xs text-gray-500 ml-2">{{ rp.views }} views</span>
                </div>
            </div>
        </Deferred>
    </AppLayout>
</template>
