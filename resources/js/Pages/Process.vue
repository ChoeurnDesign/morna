<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import * as Lucide from 'lucide-vue-next';
import { Motion } from '@motionone/vue';

const props = defineProps({
    steps: {
        type: Array,
        default: () => [],
    },
});

const getIconComponent = (iconName) => {
    if (!iconName) {
        return Lucide.Droplet;
    }
    // Handle the common icon names used in the admin form (e.g., 'TreePine')
    // which map to Lucide components (e.g., Lucide.TreePine)
    return Lucide[iconName] || Lucide.Droplet;
};

/**
 * Returns the full public URL for an image path stored in the database.
 * @param {string} path The path stored in the DB (e.g., 'processes/image.jpg').
 * @returns {string} The full URL (e.g., '/storage/processes/image.jpg').
 */
const getImagePathUrl = (path) => {
    if (!path || typeof path !== 'string') {
        return null;
    }
    // We assume images are stored in the Laravel storage disk and linked publicly.
    // The path stored in the DB (e.g., 'processes/xyz.jpg') needs to be prefixed with '/storage/'.
    return `/storage/${path}`;
};
</script>

<template>
    <GuestLayout title="Farm to Bottle">
        <div class="px-6 sm:px-14 lg:px-20 py-8">
            <!-- Intro section -->
            <Motion
                class="mb-10 sm:mb-12 space-y-3 sm:space-y-4 max-w-4xl mx-auto text-center"
                :initial="{ opacity: 0, y: 20 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.55, easing: 'ease-out' }"
            >
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                    From Farm to Bottle
                </h1>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                    A simple view of how Morna Mulberry moves from fresh mulberries at
                    the farm to a ready-to-drink bottle in your hands, ensuring quality at every step.
                </p>
            </Motion>

            <!-- Steps grid -->
            <section class="max-w-6xl mx-auto">
                <div
                    v-if="steps.length"
                    class="grid gap-8 sm:gap-10 md:grid-cols-2 lg:grid-cols-3"
                >
                    <Motion
                        v-for="(step, index) in steps"
                        :key="step.id"
                        class="rounded-3xl border border-gray-100 bg-white shadow-xl shadow-purple-50/20 p-6 sm:p-7 flex flex-col gap-4 transform transition duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:shadow-purple-100/30"
                        :initial="{ opacity: 0, y: 30 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{
                            duration: 0.5,
                            delay: 0.08 * index,
                            easing: 'ease-out',
                        }"
                    >
                        <!-- Icon and Title Header -->
                        <div class="flex items-center gap-4">
                            <div
                                class="h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0 border border-purple-100"
                            >
                                <component
                                    :is="getIconComponent(step.icon_name)"
                                    class="w-6 h-6 text-purple-700"
                                />
                            </div>
                            <div>
                                <p class="text-[12px] sm:text-xs uppercase font-bold tracking-widest text-purple-600">
                                    Step {{ step.step_number }}
                                </p>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">
                                    {{ step.title }}
                                </h2>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed">
                            {{ step.description }}
                        </p>

                        <!-- Conditional Image Display -->
                        <div
                            v-if="step.image_path"
                            class="mt-2 rounded-2xl overflow-hidden bg-gray-100 aspect-video border border-gray-200"
                        >
                            <!-- Use the helper function to get the correct URL -->
                            <img
                                :src="getImagePathUrl(step.image_path)"
                                :alt="step.title"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        
                    </Motion>
                </div>

                <!-- marketing / privacy-friendly placeholder when no steps -->
                <Motion
                    v-else
                    class="mt-12 rounded-3xl border border-dashed border-gray-200 bg-white p-8 sm:p-10 flex flex-col items-center text-center shadow-inner"
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.4, easing: 'ease-out' }"
                >
                    <div class="w-full max-w-2xl">
                        <p class="text-xl sm:text-2xl text-gray-800 font-extrabold mb-3">
                            We focus on quality — not secrets.
                        </p>

                        <p class="text-sm sm:text-base text-gray-600 mb-6 leading-relaxed">
                            Our mulberries are carefully selected and bottled with care. While we keep some
                            technical details private to protect our recipe and process, we are transparent about
                            our sourcing, safety, and sustainability commitments. Check our documentation for more information.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a 
                                href="/about" 
                                class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-purple-600 text-white font-semibold text-sm transition-colors hover:bg-purple-700 shadow-md"
                            >
                                Learn more about us
                            </a>
                            <a 
                                href="/contact" 
                                class="inline-block px-4 py-2 text-sm text-gray-700 font-medium hover:text-purple-600 transition-colors"
                            >
                                Contact us for details &rarr;
                            </a>
                        </div>
                    </div>
                </Motion>
            </section>
        </div>
    </GuestLayout>
</template>