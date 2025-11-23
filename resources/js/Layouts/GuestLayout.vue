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
        <PublicHeader :site-settings="siteSettings" />
        
        <main class="flex-1 w-full">
            <!-- Remove px-* classes here, let individual pages handle padding -->
            <div class="mx-auto py-8 sm:py-10">
                <slot />
            </div>
        </main>

        <PublicFooter :site-settings="siteSettings" />
    </div>
</template>