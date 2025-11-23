<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Motion } from '@motionone/vue';
import { computed } from 'vue';

const props = defineProps({
    page: {
        type: Object,
        default: null,
    },
    teamMembers: {
        type: Array,
        default: () => [],
    },
});

// Default content when no page data exists
const pageContent = computed(() => {
    if (props.page) {
        return props.page;
    }
    
    // Default content
    return {
        title: 'About Morna Mulberry',
        subtitle: 'A student-led mulberry drink brand from Cambodia, focused on real ingredients and thoughtful production.',
        story_text: 'Morna Mulberry began as a student initiative passionate about creating healthy, natural beverages using Cambodia\'s rich mulberry resources. Our journey started in university labs and has grown into a brand dedicated to quality and sustainability.\n\nWe work directly with local farmers to source the freshest mulberries, ensuring both quality products and community support.',
        mission_text: 'To provide healthy, natural mulberry beverages while supporting local farmers and promoting sustainable agriculture practices in Cambodia.',
        vision_text: 'To become Cambodia\'s leading natural beverage brand, known for quality, authenticity, and positive community impact.',
        banner_image_path: null,
    };
});

// Default team members if none exist
const teamMembersList = computed(() => {
    if (props.teamMembers.length > 0) {
        return props.teamMembers;
    }
    
    // Default team members
    return [
        {
            id: 1,
            name: 'Sophal Chen',
            role: 'Founder & CEO', // Changed from 'position'
            bio: 'Passionate about sustainable agriculture and healthy beverages. Leads the overall vision and strategy.', // Changed from 'description'
            image_path: null,
            sort_order: 1,
        },
        {
            id: 2,
            name: 'Ratha Kim',
            role: 'Product Manager', // Changed from 'position'
            bio: 'Focuses on product development and quality control to ensure the best mulberry drink experience.', // Changed from 'description'
            image_path: null,
            sort_order: 2,
        },
        {
            id: 3,
            name: 'Sreyneath Lim',
            role: 'Marketing Lead', // Changed from 'position'
            bio: 'Creative mind behind our brand story and customer engagement strategies.', // Changed from 'description'
            image_path: null,
            sort_order: 3,
        }
    ];
});
</script>

<template>
    <GuestLayout title="About">
        <!-- ADD HORIZONTAL PADDING HERE -->
        <div class="px-10 sm:px-14 lg:px-20">
            <Motion
                class="mb-10 space-y-8 sm:space-y-10"
                :initial="{ opacity: 0, y: 24 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.6, easing: 'ease-out' }"
            >
                <!-- Banner -->
                <Motion
                    v-if="pageContent.banner_image_path"
                    class="h-44 sm:h-52 md:h-64 rounded-3xl overflow-hidden bg-gray-200 mb-4 sm:mb-6"
                    :initial="{ opacity: 0 }"
                    :animate="{ opacity: 1 }"
                    :transition="{ delay: 0.1, duration: 0.5 }"
                >
                    <img
                        :src="pageContent.banner_image_path"
                        alt="About Morna Mulberry"
                        class="w-full h-full object-cover"
                    />
                </Motion>

                <!-- Title + subtitle -->
                <header class="space-y-3">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        {{ pageContent.title }}
                    </h1>
                    <p
                        v-if="pageContent.subtitle"
                        class="text-sm sm:text-base text-gray-600 max-w-2xl leading-relaxed"
                    >
                        {{ pageContent.subtitle }}
                    </p>
                </header>

                <!-- Story -->
                <section v-if="pageContent.story_text" class="space-y-3">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                        Our Story
                    </h2>
                    <p class="text-sm sm:text-base text-gray-700 whitespace-pre-line leading-relaxed">
                        {{ pageContent.story_text }}
                    </p>
                </section>

                <!-- Mission & Vision -->
                <section class="grid gap-5 sm:gap-6 md:grid-cols-2">
                    <Motion
                        v-if="pageContent.mission_text"
                        class="space-y-3 rounded-2xl bg-white border shadow-sm p-4 sm:p-5"
                        :initial="{ opacity: 0, y: 16 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{ duration: 0.45, delay: 0.15, easing: 'ease-out' }"
                    >
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                            Our Mission
                        </h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ pageContent.mission_text }}
                        </p>
                    </Motion>

                    <Motion
                        v-if="pageContent.vision_text"
                        class="space-y-3 rounded-2xl bg-white border shadow-sm p-4 sm:p-5"
                        :initial="{ opacity: 0, y: 16 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{ duration: 0.45, delay: 0.25, easing: 'ease-out' }"
                    >
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                            Our Vision
                        </h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ pageContent.vision_text }}
                        </p>
                    </Motion>
                </section>
            </Motion>

            <!-- Team section -->
            <Motion
                v-if="teamMembersList.length"
                class="mb-8 space-y-4"
                :initial="{ opacity: 0, y: 20 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ duration: 0.55, delay: 0.2, easing: 'ease-out' }"
            >
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">
                        Meet Our Team
                    </h2>
                </div>

                <div class="grid gap-5 sm:gap-6 sm:grid-cols-2 md:grid-cols-3">
                    <Motion
                        v-for="member in teamMembersList"
                        :key="member.id"
                        class="rounded-2xl border bg-white shadow-sm p-4 sm:p-5 flex flex-col items-center text-center"
                        :initial="{ opacity: 0, y: 16 }"
                        :animate="{ opacity: 1, y: 0 }"
                        :transition="{ duration: 0.4, delay: 0.05 * member.sort_order || 0.1, easing: 'ease-out' }"
                    >
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-gray-100 mb-3">
                            <img
                                v-if="member.image_path"
                                :src="member.image_path"
                                :alt="member.name"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 font-semibold text-lg"
                            >
                                {{ member.name.charAt(0) }}
                            </div>
                        </div>
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900">
                            {{ member.name }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-purple-700 font-medium mb-2">
                            {{ member.role }} <!-- Changed from 'position' -->
                        </p>
                        <p class="text-[11px] sm:text-xs text-gray-600 leading-relaxed">
                            {{ member.bio }} <!-- Changed from 'description' -->
                        </p>
                    </Motion>
                </div>
            </Motion>
        </div>
    </GuestLayout>
</template>