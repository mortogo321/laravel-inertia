<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'
import { usePoll } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
    notifications: Array,
    serverTime: String,
    randomMetric: Number,
})

const { start, stop, polling } = usePoll(2000, {
    only: ['notifications', 'serverTime', 'randomMetric'],
    autoStart: false,
})

const pollActive = computed(() => polling.value)

const serverCode = `return Inertia::render('Polling', [
    'notifications' => Notification::latest()->take(10)->get(),
    'serverTime' => now()->format('H:i:s'),
    'randomMetric' => rand(1, 100),
]);

// Client-side:
// const { start, stop, polling } = usePoll(2000, {
//     only: ['notifications', 'serverTime', 'randomMetric'],
//     autoStart: false,
// })`
</script>

<template>
    <AppLayout>
        <Head title="Polling" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Polling</h1>
            <p class="text-gray-600 mt-1">Auto-refresh specific props at intervals. Great for dashboards and live feeds.</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Real-time" color="orange" />
            </div>
        </div>

        <CodeBlock title="usePoll() — selective prop polling" :code="serverCode" lang="js" />

        <!-- Controls -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center gap-4">
                <button
                    @click="pollActive ? stop() : start()"
                    class="px-6 py-2 rounded-lg font-medium transition-colors"
                    :class="pollActive ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white'"
                >
                    {{ pollActive ? 'Stop Polling' : 'Start Polling (2s)' }}
                </button>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span v-if="pollActive" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3" :class="pollActive ? 'bg-green-500' : 'bg-gray-300'"></span>
                    </span>
                    <span class="text-sm text-gray-600">{{ pollActive ? 'Polling active' : 'Polling stopped' }}</span>
                </div>
            </div>
        </div>

        <!-- Live Data -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Server Time</h3>
                <div class="text-3xl font-mono font-bold text-gray-900">{{ serverTime }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Random Metric</h3>
                <div class="text-3xl font-bold text-gray-900">{{ randomMetric }}%</div>
                <div class="mt-2 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all duration-500" :style="{ width: randomMetric + '%' }"></div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Notifications</h3>
            <div class="space-y-3">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="flex items-start gap-3 p-3 rounded-lg"
                    :class="{
                        'bg-blue-50': notification.type === 'info',
                        'bg-green-50': notification.type === 'success',
                        'bg-yellow-50': notification.type === 'warning',
                        'bg-red-50': notification.type === 'error',
                    }"
                >
                    <span class="flex-shrink-0 w-2 h-2 rounded-full mt-2" :class="{
                        'bg-blue-500': notification.type === 'info',
                        'bg-green-500': notification.type === 'success',
                        'bg-yellow-500': notification.type === 'warning',
                        'bg-red-500': notification.type === 'error',
                    }"></span>
                    <div>
                        <div class="font-medium text-sm text-gray-900">{{ notification.title }}</div>
                        <div class="text-sm text-gray-600">{{ notification.message }}</div>
                    </div>
                    <FeatureBadge :label="notification.type" :color="notification.type === 'info' ? 'blue' : notification.type === 'success' ? 'green' : notification.type === 'warning' ? 'yellow' : 'red'" class="ml-auto flex-shrink-0" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
