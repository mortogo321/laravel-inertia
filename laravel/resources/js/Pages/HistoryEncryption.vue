<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import FeatureBadge from '@/Components/FeatureBadge.vue'
import CodeBlock from '@/Components/CodeBlock.vue'

defineProps({
    sensitiveData: Object,
})

const serverCode = `// Enable history encryption for this request
Inertia::encryptHistory();

return Inertia::render('HistoryEncryption', [
    'sensitiveData' => [
        'apiKey' => 'sk-demo-' . Str::random(32),
        'secretToken' => Str::random(64),
        'userEmail' => 'admin@example.com',
    ],
]);

// Or globally in middleware:
// Inertia::encryptHistory();

// Clear history (e.g., on logout):
// Inertia::clearHistory();`
</script>

<template>
    <AppLayout>
        <Head title="History Encryption" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">History Encryption</h1>
            <p class="text-gray-600 mt-1">Encrypts page data in browser history so it can't be read after navigating away. Cleared on logout.</p>
            <div class="flex gap-2 mt-2">
                <FeatureBadge label="Inertia v2+" color="purple" />
                <FeatureBadge label="Security" color="red" />
            </div>
        </div>

        <CodeBlock title="encryptHistory() + clearHistory()" :code="serverCode" lang="php" />

        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Encrypted Sensitive Data</h2>
            <p class="text-sm text-gray-600 mb-4">
                This data is encrypted in the browser's history state. If you navigate away and press Back,
                the data can't be read from the history without the encryption key.
            </p>

            <div class="space-y-4">
                <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                    <label class="text-xs font-medium text-red-600 uppercase tracking-wider">API Key</label>
                    <div class="font-mono text-sm text-gray-900 mt-1 break-all">{{ sensitiveData.apiKey }}</div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                    <label class="text-xs font-medium text-orange-600 uppercase tracking-wider">Secret Token</label>
                    <div class="font-mono text-sm text-gray-900 mt-1 break-all">{{ sensitiveData.secretToken }}</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                    <label class="text-xs font-medium text-yellow-600 uppercase tracking-wider">User Email</label>
                    <div class="font-mono text-sm text-gray-900 mt-1">{{ sensitiveData.userEmail }}</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h3 class="font-semibold text-blue-900 mb-2">How it works</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>1. Page data is encrypted before being stored in <code class="bg-blue-100 px-1 rounded">window.history.state</code></li>
                <li>2. On navigation, the data is decrypted and rendered normally</li>
                <li>3. On logout, call <code class="bg-blue-100 px-1 rounded">Inertia::clearHistory()</code> to wipe all encrypted state</li>
                <li>4. Can be enabled per-page or globally via middleware</li>
            </ul>
        </div>
    </AppLayout>
</template>
