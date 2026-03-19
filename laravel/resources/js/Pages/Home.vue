<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: Object,
})

const features = [
    {
        title: 'Deferred Props',
        description: 'Load heavy data after initial page render for blazing fast perceived performance.',
        href: '/deferred',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Performance', color: 'green' }],
    },
    {
        title: 'Infinite Scroll',
        description: 'Automatic pagination loading as users scroll — no page reloads.',
        href: '/infinite-scroll',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'UX', color: 'blue' }],
    },
    {
        title: 'Polling',
        description: 'Auto-refresh data at intervals — perfect for dashboards & real-time feeds.',
        href: '/polling',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Real-time', color: 'orange' }],
    },
    {
        title: 'WhenVisible',
        description: 'Lazy-load data only when the component scrolls into the viewport.',
        href: '/when-visible',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Performance', color: 'green' }],
    },
    {
        title: 'Merge Props',
        description: 'Append, prepend, or deep-merge new data into existing props without replacing.',
        href: '/merge-props',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Data', color: 'yellow' }],
    },
    {
        title: 'Prefetching + CRUD',
        description: 'Link prefetching on hover/mount + full CRUD with forms and validation.',
        href: '/posts',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Laravel', color: 'red' }],
    },
    {
        title: 'History Encryption',
        description: 'Encrypt sensitive page data in browser history — cleared on logout.',
        href: '/history',
        badges: [{ label: 'Inertia v2+', color: 'purple' }, { label: 'Security', color: 'red' }],
    },
]
</script>

<template>
    <AppLayout>
        <Head title="Home" />

        <!-- Hero -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Laravel 13 + Inertia.js v3 Beta
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                A comprehensive demo showcasing the latest features of Laravel 13 and Inertia.js v3
                with Vue 3, Tailwind CSS 4, and Vite.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <div class="bg-white rounded-xl p-6 shadow-sm border text-center">
                <div class="text-3xl font-bold text-gray-900">{{ stats.posts }}</div>
                <div class="text-sm text-gray-500 mt-1">Posts</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border text-center">
                <div class="text-3xl font-bold text-gray-900">{{ stats.comments }}</div>
                <div class="text-sm text-gray-500 mt-1">Comments</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border text-center">
                <div class="text-3xl font-bold text-gray-900">{{ stats.tags }}</div>
                <div class="text-sm text-gray-500 mt-1">Tags</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border text-center">
                <div class="text-3xl font-bold text-gray-900">{{ stats.notifications }}</div>
                <div class="text-sm text-gray-500 mt-1">Notifications</div>
            </div>
        </div>

        <!-- Feature Grid -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Feature Demos</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Link
                v-for="feature in features"
                :key="feature.href"
                :href="feature.href"
                prefetch
                class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md hover:border-gray-300 transition-all group"
            >
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors mb-2">
                    {{ feature.title }}
                </h3>
                <p class="text-gray-600 text-sm mb-4">{{ feature.description }}</p>
                <div class="flex flex-wrap gap-2">
                    <FeatureBadge v-for="badge in feature.badges" :key="badge.label" v-bind="badge" />
                </div>
            </Link>
        </div>
    </AppLayout>
</template>
