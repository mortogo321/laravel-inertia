<script setup>
defineProps({
    title: String,
    lang: { type: String, default: 'php' },
    code: String,
})
</script>

<template>
    <div class="rounded-lg overflow-hidden border border-gray-200 my-4">
        <div class="bg-gray-800 px-4 py-2 flex justify-between items-center">
            <span class="text-gray-300 text-sm font-mono">{{ title }}</span>
            <span class="text-gray-500 text-xs">{{ lang }}</span>
        </div>
        <pre class="bg-gray-900 text-gray-100 p-4 overflow-x-auto text-sm leading-relaxed"><code>{{ code }}</code></pre>
    </div>
</template>
