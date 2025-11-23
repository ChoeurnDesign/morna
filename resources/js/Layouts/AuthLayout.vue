<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-purple-50 to-white flex flex-col">
        <!-- Small brand bar (no nav, no sponsors) -->
        <header class="px-4 sm:px-6 lg:px-8 pt-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2">
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white text-sm"
                    >
                        M
                    </span>
                    <span class="font-medium text-gray-800 text-sm sm:text-base">
                        Morna Mulbeery
                    </span>
                </Link>
            </div>
        </header>

        <!-- Centered auth card -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6">
            <div class="w-full max-w-md">
                <div
                    class="rounded-2xl bg-white/90 shadow-md border border-purple-50 px-6 py-6 sm:px-8 sm:py-8"
                >
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
