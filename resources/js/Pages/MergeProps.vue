<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    items: Array,
    page: Number,
    hasMore: Boolean,
})

const loading = ref(false)

function loadMore() {
    loading.value = true
    router.reload({
        data: { page: props.page + 1 },
        only: ['items', 'page', 'hasMore'],
        onFinish: () => { loading.value = false },
    })
}

const serverCode = `$page = (int) $request->input('page', 1);
$perPage = 5;
$allItems = Post::orderBy('id')->get();
$items = $allItems->slice(($page - 1) * $perPage, $perPage)->values();

return Inertia::render('MergeProps', [
    // Inertia::merge() appends new items to existing ones
    'items' => Inertia::merge(
        $items->map(fn ($p) => [
            'id' => $p->id,
            'title' => $p->title,
            'views' => $p->views,
        ])->toArray()
    ),
    'page' => $page,
    'hasMore' => $page * $perPage < $allItems->count(),
]);`
</script>

<template>
    <AppLayout>
        <Head title="Merge Props" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Merge Props</h1>
            <p class="text-gray-600 mt-1">Append new data to existing props without replacing — perfect for "Load More" patterns.</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Data Management" color="yellow" />
            </div>
        </div>

        <CodeBlock title="Inertia::merge() — server-side" :code="serverCode" lang="php" />

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Items ({{ items.length }} loaded, page {{ page }})</h2>
            </div>

            <div class="space-y-3">
                <div
                    v-for="(item, index) in items"
                    :key="item.id"
                    class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors"
                >
                    <span class="text-lg font-bold text-gray-300 w-8">{{ index + 1 }}</span>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ item.title }}</div>
                    </div>
                    <span class="text-sm text-gray-500">{{ item.views.toLocaleString() }} views</span>
                </div>
            </div>

            <div class="mt-6 text-center">
                <button
                    v-if="hasMore"
                    @click="loadMore"
                    :disabled="loading"
                    class="bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white px-8 py-3 rounded-lg font-medium transition-colors"
                >
                    {{ loading ? 'Loading...' : 'Load More (Merge Props)' }}
                </button>
                <p v-else class="text-gray-500">All items loaded!</p>
            </div>
        </div>
    </AppLayout>
</template>
