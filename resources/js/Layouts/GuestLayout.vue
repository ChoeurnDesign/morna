<script setup>
import PublicHeader from '@/Layouts/Partials/PublicHeader.vue';
import PublicFooter from '@/Layouts/Partials/PublicFooter.vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    siteSettings: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-purple-50 to-white">
        <!-- Page width wrapper -->
        <div class="w-full max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">
            <PublicHeader :site-settings="siteSettings" />

            <main class="flex-1 w-full">
                <div class="py-8 sm:py-8">
                    <slot />
                </div>
            </main>

            <PublicFooter :site-settings="siteSettings" />
        </div>
    </div>
</template>