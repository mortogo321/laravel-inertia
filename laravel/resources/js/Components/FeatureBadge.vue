<script setup>
defineProps({
    label: String,
    color: { type: String, default: 'blue' },
})

const colors = {
    blue: 'bg-blue-100 text-blue-800',
    green: 'bg-green-100 text-green-800',
    purple: 'bg-purple-100 text-purple-800',
    orange: 'bg-orange-100 text-orange-800',
    red: 'bg-red-100 text-red-800',
    yellow: 'bg-yellow-100 text-yellow-800',
}
</script>

<template>
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="colors[color]">
        {{ label }}
    </span>
</template>
