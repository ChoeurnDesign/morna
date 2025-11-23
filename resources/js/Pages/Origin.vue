<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Motion } from '@motionone/vue';

const props = defineProps({
    origin: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <GuestLayout title="Mulberry Origin">
        <!-- ADD HORIZONTAL PADDING WRAPPER -->
        <div class="px-10 sm:px-14 lg:px-20">
            <!-- When origin exists -->
            <Motion
                v-if="origin"
                class="space-y-6 sm:space-y-8"
                :initial="{ opacity: 0, y: 24 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.6, easing: 'ease-out' }"
            >
                <Motion
                    class="space-y-2 sm:space-y-3"
                    :initial="{ opacity: 0, y: 12 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.45, delay: 0.1, easing: 'ease-out' }"
                >
                    <p class="text-[11px] sm:text-xs font-semibold tracking-wide text-purple-700 uppercase">
                        Mulberry origin
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        {{ origin.name }}
                    </h1>
                    <p class="text-sm text-gray-600">
                        <span v-if="origin.province">
                            Province:
                            <span class="font-medium">{{ origin.province }}</span>
                        </span>
                        <span v-if="origin.location_text">
                            <span v-if="origin.province"> · </span>
                            {{ origin.location_text }}
                        </span>
                    </p>
                </Motion>

                <Motion
                    class="grid gap-6 lg:gap-8 lg:grid-cols-2 lg:items-start"
                    :initial="{ opacity: 0, y: 16 }"
                    :animate="{ opacity: 1, y: 0 }"
                    :transition="{ duration: 0.5, delay: 0.18, easing: 'ease-out' }"
                >
                    <!-- Text block -->
                    <section class="space-y-3 sm:space-y-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                            The place behind the drink
                        </h2>
                        <p class="text-sm sm:text-base text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ origin.description }}
                        </p>
                    </section>

                    <!-- Images -->
                    <section class="space-y-4">
                        <div
                            v-if="origin.main_image_path"
                            class="rounded-3xl overflow-hidden bg-gray-100 aspect-[4/3]"
                        >
                            <img
                                :src="origin.main_image_path"
                                :alt="origin.name"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <div
                            v-if="origin.map_image_path"
                            class="rounded-3xl overflow-hidden bg-gray-100 aspect-[4/3]"
                        >
                            <img
                                :src="origin.map_image_path"
                                :alt="`Map of ${origin.name}`"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </section>
                </Motion>
            </Motion>

            <!-- Empty state -->
            <Motion
                v-else
                class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-5 sm:p-6 text-center text-xs sm:text-sm text-gray-500"
                :initial="{ opacity: 0, y: 16 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.4, easing: 'ease-out' }"
            >
                No origin data available yet. Add an active origin record in the admin
                panel to show your mulberry source story.
            </Motion>
        </div>
    </GuestLayout>
</template>