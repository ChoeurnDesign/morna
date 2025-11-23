<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { ref } from "vue";

const props = defineProps({
    mainProduct: {
        type: Object,
        default: null,
    },
    otherProducts: {
        type: Array,
        default: () => [],
    },
});

// ZOOM STATES AND METHODS
const showZoomedImage = ref(false);
const zoomedProduct = ref(null);

const isZooming = ref(false);
const zoomPosition = ref({ x: "50%", y: "50%" });

function handleZoomEnter(product) {
    if (window.innerWidth >= 768) {
        isZooming.value = true;
        zoomedProduct.value = product;
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
    zoomedProduct.value = null;
    zoomPosition.value.x = "50%";
    zoomPosition.value.y = "50%";
}

function openZoomModal(product) {
    showZoomedImage.value = true;
    zoomedProduct.value = product;
}

function closeZoomModal() {
    showZoomedImage.value = false;
    zoomedProduct.value = null;
}

// image helpers
const hasImage = (product) => {
    return product && (product.image_path || product.image_url);
};

const getProductImage = (product) => {
    return product.image_path || product.image_url;
};
</script>

<template>
    <GuestLayout title="Product">
        <!-- Zoom modal -->
        <div
            v-if="showZoomedImage && zoomedProduct"
            class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
            @click.self="closeZoomModal"
        >
            <div
                class="relative w-full h-full max-w-4xl max-h-screen overflow-auto"
            >
                <button
                    class="absolute top-4 right-4 text-white text-3xl font-bold p-2 z-50 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-black/70 transition-colors"
                    @click="closeZoomModal"
                >
                    &times;
                </button>
                <img
                    :src="getProductImage(zoomedProduct)"
                    :alt="zoomedProduct.name"
                    class="w-full h-auto object-contain cursor-grab"
                    style="max-width: unset; max-height: unset"
                />
                <p class="text-center text-white text-sm mt-4">
                    Tap to close. Drag or scroll to view details.
                </p>
            </div>
        </div>

        <div class="px-10 sm:px-14 lg:px-20 pt-8 sm:pt-10">
            <!-- MAIN PRODUCT -->
            <section v-if="mainProduct" class="mb-10">
                <div class="grid gap-8 lg:grid-cols-2 lg:items-start">
                    <!-- IMAGE COLUMN -->
                    <div class="order-2 lg:order-1">
                        <div class="flex justify-center">
                            <!-- Outer rounded frame -->
                            <div
                                class="rounded-[16px] bg-gray-100/80 backdrop-blur-sm overflow-hidden flex items-center justify-center w-full max-w-sm lg:max-w-md aspect-[4/5] shadow-sm"
                            >
                                <template v-if="hasImage(mainProduct)">
                                    <!-- Zoomable area using background like on Home -->
                                    <div
                                        class="w-full h-full cursor-zoom-in bg-no-repeat transition-all duration-300"
                                        :style="{
                                            backgroundImage: `url(${getProductImage(
                                                mainProduct
                                            )})`,
                                            backgroundPosition:
                                                zoomPosition.x +
                                                ' ' +
                                                zoomPosition.y,
                                            backgroundSize:
                                                isZooming &&
                                                zoomedProduct === mainProduct
                                                    ? '250%'
                                                    : '100%',
                                        }"
                                        :class="{
                                            'md:bg-cover':
                                                isZooming &&
                                                zoomedProduct === mainProduct,
                                            'bg-contain':
                                                !isZooming ||
                                                zoomedProduct !== mainProduct,
                                        }"
                                        @click="openZoomModal(mainProduct)"
                                        @mouseenter="
                                            handleZoomEnter(mainProduct)
                                        "
                                        @mousemove="handleZoomMove"
                                        @mouseleave="handleZoomLeave"
                                    ></div>
                                </template>
                                <span
                                    v-else
                                    class="text-xs text-gray-600 px-4 text-center"
                                >
                                    Add a product image in the admin panel to
                                    replace this placeholder.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- TEXT COLUMN -->
                    <div class="order-1 lg:order-2 space-y-4">
                        <h1
                            class="text-2xl md:text-3xl font-bold text-gray-900"
                        >
                            {{ mainProduct.name }}
                        </h1>

                        <div class="flex flex-wrap gap-3 text-xs text-gray-700">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-purple-700"
                            >
                                {{ mainProduct.size_ml }} ml
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700"
                            >
                                {{ mainProduct.price }} $
                            </span>
                        </div>

                        <p
                            v-if="mainProduct.short_description"
                            class="text-sm text-gray-700"
                        >
                            {{ mainProduct.short_description }}
                        </p>

                        <section
                            v-if="mainProduct.ingredients_text"
                            class="space-y-2"
                        >
                            <h2 class="text-sm font-semibold text-gray-900">
                                Ingredients
                            </h2>
                            <p
                                class="text-sm text-gray-700 whitespace-pre-line"
                            >
                                {{ mainProduct.ingredients_text }}
                            </p>
                        </section>

                        <section
                            v-if="mainProduct.description"
                            class="space-y-2"
                        >
                            <h2 class="text-sm font-semibold text-gray-900">
                                Description
                            </h2>
                            <p
                                class="text-sm text-gray-700 whitespace-pre-line leading-relaxed"
                            >
                                {{ mainProduct.description }}
                            </p>
                        </section>

                        <section class="space-y-2">
                            <h2 class="text-sm font-semibold text-gray-900">
                                Serving suggestion
                            </h2>
                            <p class="text-sm text-gray-700">
                                Serve chilled over ice, or lightly cooled from
                                the fridge. Perfect with snacks, group study
                                sessions, or as a quick refresh during a busy
                                day.
                            </p>
                        </section>
                    </div>
                </div>
            </section>

            <!-- NO MAIN PRODUCT -->
            <section
                v-else
                class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500 mb-10"
            >
                No main product found. Make sure one product is active and has
                <code class="bg-gray-200 px-1 rounded text-xs"
                    >is_main = true</code
                >.
            </section>

            <!-- OTHER PRODUCTS -->
            <section v-if="otherProducts.length" class="mb-8">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
                    Coming next
                </h2>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="product in otherProducts"
                        :key="product.id"
                        class="rounded-2xl border bg-white shadow-sm p-4 flex flex-col hover:shadow-md transition-shadow duration-300"
                    >
                        <div
                            class="aspect-[4/3] rounded-xl bg-gray-100 overflow-hidden mb-3 flex items-center justify-center cursor-zoom-in"
                            @click="openZoomModal(product)"
                            @mouseenter="handleZoomEnter(product)"
                            @mousemove="handleZoomMove"
                            @mouseleave="handleZoomLeave"
                        >
                            <div
                                v-if="hasImage(product)"
                                class="w-full h-full bg-contain bg-no-repeat bg-center transition-all duration-300"
                                :style="{
                                    backgroundImage: `url(${getProductImage(
                                        product
                                    )})`,
                                    backgroundPosition:
                                        zoomPosition.x + ' ' + zoomPosition.y,
                                    backgroundSize:
                                        isZooming && zoomedProduct === product
                                            ? '250%'
                                            : '100%',
                                }"
                                :class="{
                                    'md:bg-cover':
                                        isZooming && zoomedProduct === product,
                                    'md:bg-contain':
                                        !isZooming || zoomedProduct !== product,
                                }"
                            ></div>
                            <div
                                v-else
                                class="w-full h-full flex itemscenter justify-center text-xs text-gray-400"
                            >
                                No image
                            </div>
                        </div>

                        <h3 class="text-sm font-semibold text-gray-900 mb-1">
                            {{ product.name }}
                        </h3>
                        <p class="text-xs text-gray-600 mb-2">
                            {{
                                product.short_description ||
                                "New variation coming soon."
                            }}
                        </p>
                        <div
                            class="mt-auto flex items-center justify-between text-xs text-gray-700"
                        >
                            <span>{{ product.size_ml }} ml</span>
                            <span>{{ product.price }} $</span>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </GuestLayout>
</template>

<style scoped>
.cursor-zoom-in {
    cursor: zoom-in;
}

.cursor-grab {
    cursor: grab;
}

.cursor-grab:active {
    cursor: grabbing;
}

.transition-shadow {
    transition: box-shadow 0.3s ease;
}

.transition-all {
    transition: all 0.3s ease;
}
</style>
