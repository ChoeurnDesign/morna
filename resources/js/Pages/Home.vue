<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { computed, ref } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";

const props = defineProps({
    heroSlides: {
        type: Array,
        default: () => [],
    },
    mainProduct: {
        type: Object,
        default: null,
    },
    siteSettings: {
        type: Object,
        default: () => ({}),
    },
});

const modules = [Autoplay, Pagination];

// ZOOM STATES AND METHODS
const showZoomedImage = ref(false);

const isZooming = ref(false);
const zoomPosition = ref({ x: "50%", y: "50%" });

function handleZoomEnter() {
    if (window.innerWidth >= 768) {
        isZooming.value = true;
    }
}

function handleZoomMove(event) {
    if (!isZooming.value) return;
    const { target } = event;
    if (!(target instanceof HTMLElement)) return;

    const { left, top, width, height } = target.getBoundingClientRect();
    const x = (event.clientX - left) / width;
    const y = (event.clientY - top) / height;

    zoomPosition.value.x = `${x * 100}%`;
    zoomPosition.value.y = `${y * 100}%`;
}

function handleZoomLeave() {
    isZooming.value = false;
    zoomPosition.value.x = "50%";
    zoomPosition.value.y = "50%";
}

// Logo and site name from settings
const logoUrl = computed(() => {
    return props.siteSettings?.logo_url || null;
});

const siteName = computed(() => {
    return props.siteSettings?.site_name || "Morna Mulberry";
});

const slideFrames = computed(() => {
    const frames = [];

    (props.heroSlides ?? []).forEach((slide) => {
        const imgs =
            slide.images && slide.images.length ? slide.images : [null];

        imgs.forEach((img, index) => {
            frames.push({
                key: img
                    ? `${slide.id}-${img.id || img.image_path || index}`
                    : `${slide.id}-color-${index}`,
                imageUrl:
                    img && img.image_path ? `/storage/${img.image_path}` : null,
            });
        });
    });

    if (!frames.length) {
        frames.push({
            key: "fallback-color",
            imageUrl: null,
        });
    }

    return frames;
});

const heroTitle = computed(() => {
    return props.heroSlides?.[0]?.title || siteName.value;
});

const heroSubtitle = computed(() => {
    return (
        props.heroSlides?.[0]?.subtitle ||
        "A modern mulberry drink made by Cambodian students. Real fruit, real flavor, real impact."
    );
});

const heroButtonText = computed(() => {
    return props.heroSlides?.[0]?.button_text || null;
});

const heroButtonLink = computed(() => {
    return props.heroSlides?.[0]?.button_link || "/product";
});

const featureCards = [
    {
        title: "Real mulberry goodness",
        text: "Crafted from real mulberries, not artificial flavors.",
    },
    {
        title: "Student-born startup",
        text: "Created by young Cambodian founders supporting local farms.",
    },
    {
        title: "Farm-to-bottle care",
        text: "Quality checked at every step: harvest, extraction, bottling.",
    },
    {
        title: "Perfect for daily refresh",
        text: "Ideal for studying, working, or relaxing.",
    },
];

const mainProductComputed = computed(() => props.mainProduct);
</script>

<template>
    <GuestLayout :title="siteName" :site-settings="siteSettings">
        <!-- Zoom modal -->
        <div
            v-if="showZoomedImage && mainProductComputed"
            class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
            @click.self="showZoomedImage = false"
        >
            <div
                class="relative w-full h-full max-w-lg max-h-screen overflow-auto"
            >
                <button
                    class="absolute top-4 right-4 text-white text-3xl font-bold p-2 z-50"
                    @click="showZoomedImage = false"
                >
                    &times;
                </button>
                <img
                    :src="mainProductComputed.image_url"
                    :alt="mainProductComputed.name"
                    class="w-full h-auto object-contain cursor-grab"
                    style="max-width: unset; max-height: unset"
                />
                <p class="text-center text-white text-sm mt-4">
                    Tap to close. Drag or scroll to view details.
                </p>
            </div>
        </div>

        <div class="min-h-screen bg-gray-50">
            <!-- HERO -->
            <section class="mb-8 sm:mb-12 lg:mb-16 pt-4 sm:pt-6">
                <div class="px-10 sm:px-14 lg:px-20">
                    <div
                        class="flex flex-col lg:grid lg:grid-cols-5 lg:items-center gap-6 sm:gap-8"
                    >
                        <div class="w-full lg:col-span-3 order-1 lg:order-1">
                            <div
                                class="rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg bg-gray-200"
                            >
                                <div
                                    class="relative w-full aspect-[5/3] sm:aspect-[7/3] lg:aspect-[5/3]"
                                >
                                    <Swiper
                                        :modules="modules"
                                        :loop="true"
                                        :autoplay="{
                                            delay: 4000,
                                            disableOnInteraction: false,
                                        }"
                                        :pagination="{
                                            clickable: true,
                                            dynamicBullets: true,
                                            dynamicMainBullets: 3,
                                        }"
                                        class="w-full h-full absolute inset-0"
                                    >
                                        <SwiperSlide
                                            v-for="frame in slideFrames"
                                            :key="frame.key"
                                        >
                                            <div
                                                class="w-full h-full bg-cover bg-center"
                                                :class="{
                                                    'bg-gradient-to-br from-purple-100 via-pink-100 to-green-100':
                                                        !frame.imageUrl,
                                                }"
                                                :style="
                                                    frame.imageUrl
                                                        ? {
                                                              backgroundImage: `url('${frame.imageUrl}')`,
                                                          }
                                                        : {}
                                                "
                                            ></div>
                                        </SwiperSlide>
                                    </Swiper>

                                    <div
                                        class="absolute inset-0 bg-black/40 z-10 flex items-center p-4 sm:p-6 md:p-8 lg:p-12"
                                    >
                                        <div class="w-full text-white">
                                            <div
                                                class="space-y-2 sm:space-y-3 md:space-y-4 max-w-xl mx-auto lg:mx-0 text-center lg:text-left"
                                            >
                                                <h1
                                                    class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight"
                                                >
                                                    {{ heroTitle }}
                                                </h1>

                                                <p
                                                    class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-100 leading-relaxed"
                                                >
                                                    {{ heroSubtitle }}
                                                </p>

                                                <div
                                                    v-if="heroButtonText"
                                                    class="flex justify-center lg:justify-start pt-2"
                                                >
                                                    <a
                                                        :href="heroButtonLink"
                                                        class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-white text-xs sm:text-sm font-medium text-purple-700 shadow-lg hover:bg-purple-50 transition-all duration-300 transform hover:scale-105"
                                                    >
                                                        {{ heroButtonText }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="w-full lg:col-span-2 order-2 lg:order-2 space-y-3 sm:space-y-4 lg:space-y-5"
                        >
                            <h2
                                class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 text-center lg:text-left"
                            >
                                Refresh your day with {{ siteName }}
                            </h2>
                            <p
                                class="text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed text-center lg:text-left"
                            >
                                A modern mulberry drink made by Cambodian
                                students. Real fruit, real flavor, real impact.
                            </p>

                            <div
                                class="flex flex-wrap gap-2 sm:gap-3 justify-center lg:justify-start"
                            >
                                <span
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-purple-50 text-purple-700 font-medium border border-purple-200 text-xs sm:text-sm"
                                >
                                    Real mulberry
                                </span>
                                <span
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-green-50 text-green-700 font-medium border border-green-200 text-xs sm:text-sm"
                                >
                                    Student startup
                                </span>
                                <span
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-orange-50 text-orange-700 font-medium border border-orange-200 text-xs sm:text-sm"
                                >
                                    Farm-inspired
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- WHY -->
            <section class="mb-10 sm:mb-12 lg:mb-16">
                <div class="px-10 sm:px-14 lg:px-20">
                    <h2
                        class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6 text-center lg:text-left"
                    >
                        Why {{ siteName }}?
                    </h2>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6"
                    >
                        <article
                            v-for="(feature, index) in featureCards"
                            :key="index"
                            class="rounded-xl sm:rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-300"
                        >
                            <div class="h-full p-4 sm:p-5 lg:p-6 flex flex-col">
                                <h3
                                    class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 mb-2 sm:mb-3"
                                >
                                    {{ feature.title }}
                                </h3>
                                <p
                                    class="text-xs sm:text-sm lg:text-base text-gray-600 leading-relaxed"
                                >
                                    {{ feature.text }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- SIGNATURE DRINK -->
            <section class="mb-8 sm:mb-10 lg:mb-12">
                <div class="px-10 sm:px-14 lg:px-20">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-2"
                    >
                        <h2
                            class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-900 text-center sm:text-left"
                        >
                            Signature drink
                        </h2>
                        <a
                            href="/product"
                            class="text-sm sm:text-base font-medium text-purple-700 hover:text-purple-900 transition-colors text-center sm:text-right"
                        >
                            View product →
                        </a>
                    </div>

                    <div
                        v-if="mainProductComputed"
                        class="rounded-xl sm:rounded-2xl border border-gray-200 bg-white shadow-sm p-4 sm:p-5 lg:p-6 flex flex-col md:flex-row gap-4 sm:gap-5 lg:gap-6 hover:shadow-md transition-shadow duration-300"
                    >
                        <!-- TEXT -->
                        <div
                            class="flex-1 space-y-3 sm:space-y-4 order-2 md:order-1"
                        >
                            <h3
                                class="text-lg sm:text-xl font-semibold text-gray-900"
                            >
                                {{ mainProductComputed.name }}
                            </h3>

                            <p
                                class="text-sm sm:text-base text-gray-600 leading-relaxed"
                            >
                                {{
                                    mainProductComputed.short_description ??
                                    "A refreshing mulberry drink."
                                }}
                            </p>

                            <div class="flex flex-wrap gap-2 sm:gap-3">
                                <span
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-xs sm:text-sm"
                                >
                                    Size: {{ mainProductComputed.size_ml }} ml
                                </span>
                                <span
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-green-50 text-green-700 border border-green-200 text-xs sm:text-sm"
                                >
                                    Price: ${{ mainProductComputed.price }}
                                </span>
                            </div>

                            <a
                                href="/product"
                                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-purple-600 text-white text-sm sm:text-base font-medium hover:bg-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                            >
                                View details
                            </a>
                        </div>

                        <!-- IMAGE (only inner radius) -->
                        <div
                            class="w-full md:w-48 lg:w-56 xl:w-64 flex justify-center items-center order-1 md:order-2"
                        >
                            <div
                                class="w-full h-auto flex items-center justify-center"
                            >
                                <div
                                    v-if="mainProductComputed.image_url"
                                    class="w-full h-64 rounded-xl overflow-hidden relative cursor-zoom-in"
                                    @click="showZoomedImage = true"
                                    @mouseenter="handleZoomEnter"
                                    @mousemove="handleZoomMove"
                                    @mouseleave="handleZoomLeave"
                                >
                                    <div
                                        class="w-full h-full bg-contain bg-no-repeat transition-all duration-300"
                                        :style="{
                                            backgroundImage: `url(${mainProductComputed.image_url})`,
                                            backgroundPosition:
                                                zoomPosition.x +
                                                ' ' +
                                                zoomPosition.y,
                                            backgroundSize: isZooming
                                                ? '250%'
                                                : '100%',
                                        }"
                                    ></div>
                                </div>
                                <div
                                    v-else
                                    class="text-xs sm:text-sm text-gray-500 flex items-center justify-center h-32 p-3 sm:p-4 text-center"
                                >
                                    Add product image
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="rounded-xl sm:rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-4 sm:p-5 lg:p-6 text-center text-sm sm:text-base text-gray-500"
                    >
                        No main product yet. Mark a product as main product.
                    </div>
                </div>
            </section>
        </div>
    </GuestLayout>
</template>

<style scoped>
.container {
    max-width: 1200px;
}

.transition-colors {
    transition: color 0.2s ease, background-color 0.2s ease;
}

.transition-all {
    transition: all 0.3s ease;
}

.transition-shadow {
    transition: box-shadow 0.3s ease;
}

.transition-transform {
    transition: transform 0.3s ease;
}

/* Improved Swiper Styles */
:deep(.swiper) {
    border-radius: 1rem;
}

:deep(.swiper-pagination) {
    bottom: 12px !important;
}

:deep(.swiper-pagination-bullet) {
    background: white;
    opacity: 0.6;
    width: 6px;
    height: 6px;
    margin: 0 3px !important;
}

:deep(.swiper-pagination-bullet-active) {
    opacity: 1;
    background: white;
    transform: scale(1.2);
}

/* Mobile-specific adjustments */
@media (max-width: 640px) {
    :deep(.swiper-pagination) {
        bottom: 8px !important;
    }

    :deep(.swiper-pagination-bullet) {
        width: 5px;
        height: 5px;
        margin: 0 2px !important;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    :deep(.swiper-pagination) {
        bottom: 14px !important;
    }
}

img {
    display: block;
    max-width: 100%;
    height: auto;
}

.bg-cover {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
