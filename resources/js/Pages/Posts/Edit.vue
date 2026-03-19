<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    post: Object,
    tags: Array,
})

const form = useForm({
    title: props.post.title,
    body: props.post.body,
    status: props.post.status,
    tags: props.post.tags.map(t => t.id),
})

function submit() {
    form.put(`/posts/${props.post.id}`)
}
</script>

<template>
    <AppLayout>
        <Head :title="`Edit: ${post.title}`" />

        <div class="mb-6">
            <Link href="/posts" class="text-red-600 hover:text-red-800 text-sm">&larr; Back to Posts</Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-8 max-w-2xl">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Post</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input v-model="form.title" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none" />
                    <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea v-model="form.body" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none" />
                    <p v-if="form.errors.body" class="text-red-500 text-sm mt-1">{{ form.errors.body }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select v-model="form.status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <label v-for="tag in tags" :key="tag.id" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border cursor-pointer transition-colors" :class="form.tags.includes(tag.id) ? 'bg-red-50 border-red-300 text-red-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            <input type="checkbox" :value="tag.id" v-model="form.tags" class="sr-only" />
                            <span class="text-sm">{{ tag.name }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" :disabled="form.processing" class="bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        {{ form.processing ? 'Updating...' : 'Update Post' }}
                    </button>
                    <Link href="/posts" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
