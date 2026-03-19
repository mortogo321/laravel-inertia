<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()
const flash = computed(() => page.props.flash)
const showMobileMenu = ref(false)

const navLinks = [
    { href: '/', label: 'Home' },
    { href: '/posts', label: 'Posts (CRUD + Prefetch)' },
    { href: '/deferred', label: 'Deferred Props' },
    { href: '/infinite-scroll', label: 'Infinite Scroll' },
    { href: '/polling', label: 'Polling' },
    { href: '/when-visible', label: 'WhenVisible' },
    { href: '/merge-props', label: 'Merge Props' },
    { href: '/history', label: 'History Encryption' },
]
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-bold bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent">
                                Laravel + Inertia v3
                            </span>
                        </Link>
                    </div>

                    <!-- Desktop nav -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="link.href"
                            prefetch
                            class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            :class="$page.url.startsWith(link.href) && (link.href !== '/' || $page.url === '/')
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                        >
                            {{ link.label }}
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden flex items-center">
                        <button @click="showMobileMenu = !showMobileMenu" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile nav -->
            <div v-if="showMobileMenu" class="lg:hidden border-t">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="block px-3 py-2 rounded-md text-base font-medium"
                        :class="$page.url.startsWith(link.href) && (link.href !== '/' || $page.url === '/')
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                        @click="showMobileMenu = false"
                    >
                        {{ link.label }}
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="flash?.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ flash.success }}
            </div>
        </div>
        <div v-if="flash?.error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ flash.error }}
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Laravel {{ $page.props.laravelVersion }} / PHP {{ $page.props.phpVersion }}</span>
                    <span>Inertia.js v3 Beta + Vue 3</span>
                </div>
            </div>
        </footer>
    </div>
</template>
