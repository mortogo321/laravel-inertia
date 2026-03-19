<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    posts: Object,
    filters: Object,
})

const search = ref(props.filters?.search || '')
let timeout = null

function onSearch() {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        router.get('/posts', { search: search.value }, { preserveState: true, replace: true })
    }, 300)
}

function deletePost(id) {
    if (confirm('Are you sure?')) {
        router.delete(`/posts/${id}`)
    }
}

const serverCode = `// PostController@index
return Inertia::render('Posts/Index', [
    'posts' => Post::query()
        ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
        ->withCount('comments')
        ->latest()
        ->paginate(10)
        ->withQueryString(),
    'filters' => $request->only('search'),
]);`
</script>

<template>
    <AppLayout>
        <Head title="Posts" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Posts</h1>
                <div class="flex gap-2 mt-2">
                    <FeatureBadge label="Prefetching" color="purple" />
                    <FeatureBadge label="CRUD" color="red" />
                    <FeatureBadge label="Pagination" color="blue" />
                </div>
            </div>
            <Link href="/posts/create" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Create Post
            </Link>
        </div>

        <CodeBlock title="Server-side Controller" code="" lang="php">
            <template #default>{{ serverCode }}</template>
        </CodeBlock>

        <!-- Search -->
        <div class="mb-6">
            <input
                v-model="search"
                @input="onSearch"
                type="text"
                placeholder="Search posts... (debounced, preserveState)"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none"
            />
        </div>

        <!-- Posts Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <Link :href="`/posts/${post.id}`" prefetch class="text-gray-900 font-medium hover:text-red-600 transition-colors">
                                {{ post.title }}
                            </Link>
                        </td>
                        <td class="px-6 py-4">
                            <FeatureBadge
                                :label="post.status"
                                :color="post.status === 'published' ? 'green' : post.status === 'draft' ? 'yellow' : 'red'"
                            />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ post.comments_count }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ post.views.toLocaleString() }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <Link :href="`/posts/${post.id}/edit`" prefetch class="text-blue-600 hover:text-blue-800 text-sm">Edit</Link>
                            <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center gap-1">
            <Link
                v-for="link in posts.links"
                :key="link.label"
                :href="link.url || '#'"
                prefetch
                class="px-3 py-2 text-sm rounded-lg border transition-colors"
                :class="link.active ? 'bg-red-500 text-white border-red-500' : link.url ? 'bg-white text-gray-700 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                v-html="link.label"
            />
        </div>

        <div class="mt-4 text-center text-sm text-gray-500">
            All links have <code class="bg-gray-100 px-1 rounded">prefetch</code> — hover over them to see data prefetched!
        </div>
    </AppLayout>
</template>
