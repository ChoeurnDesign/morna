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
        <!-- Header is full-width; it will handle its own inner max-width -->
        <PublicHeader :site-settings="siteSettings" />

        <main class="flex-1 w-full">
            <!-- Content width wrapper -->
            <div class="w-full max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-8 sm:py-8">
                <slot />
            </div>
        </main>

        <!-- Footer is full-width; it will handle its own inner max-width -->
        <PublicFooter :site-settings="siteSettings" />
    </div>
</template>