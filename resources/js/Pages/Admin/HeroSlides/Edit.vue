<template>
    <AdminLayout title="Edit hero slide">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit hero slide</h2>
                <p class="text-sm text-gray-600">
                    Update the slide content for the homepage hero section.
                </p>
            </div>
            <Link href="/admin/hero-slides" class="text-xs text-gray-600 hover:text-gray-900">
                ← Back to list
            </Link>
        </div>

        <form @submit.prevent="submit" class="max-w-2xl space-y-5">
            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    @input="form.clearErrors('title')"
                />
                <p v-if="form.errors.title" class="mt-1 text-xs text-red-500">
                    {{ form.errors.title }}
                </p>
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-xs font-medium text-gray-700 mb-1">
                    Subtitle
                </label>
                <textarea
                    id="subtitle"
                    v-model="form.subtitle"
                    rows="2"
                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                ></textarea>
                <p v-if="form.errors.subtitle" class="mt-1 text-xs text-red-500">
                    {{ form.errors.subtitle }}
                </p>
            </div>

            <!-- Button text & link -->
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="button_text" class="block text-xs font-medium text-gray-700 mb-1">
                        Button text
                    </label>
                    <input
                        id="button_text"
                        v-model="form.button_text"
                        type="text"
                        class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p v-if="form.errors.button_text" class="mt-1 text-xs text-red-500">
                        {{ form.errors.button_text }}
                    </p>
                </div>

                <div>
                    <label for="button_link" class="block text-xs font-medium text-gray-700 mb-1">
                        Button link
                    </label>
                    <input
                        id="button_link"
                        v-model="form.button_link"
                        type="text"
                        placeholder="/product or full URL"
                        class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p v-if="form.errors.button_link" class="mt-1 text-xs text-red-500">
                        {{ form.errors.button_link }}
                    </p>
                </div>
            </div>

            <!-- Sort + Active -->
            <div class="grid gap-4 md:grid-cols-2 items-center">
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">
                        Sort order
                    </label>
                    <input
                        id="sort_order"
                        v-model.number="form.sort_order"
                        type="number"
                        class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p v-if="form.errors.sort_order" class="mt-1 text-xs text-red-500">
                        {{ form.errors.sort_order }}
                    </p>
                </div>

                <div class="flex items-center gap-2 mt-4 md:mt-6">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                    />
                    <label for="is_active" class="text-xs text-gray-700">
                        Active (show on homepage)
                    </label>
                </div>
            </div>

            <!-- Current images + upload -->
            <div class="grid gap-4 md:grid-cols-[1fr,2fr] items-start">
                <!-- Current images -->
                <div>
                    <p class="block text-xs font-medium text-gray-700 mb-1">Current images</p>

                    <div
                        v-if="!currentImages.length"
                        class="h-24 w-40 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs"
                    >
                        No image
                    </div>

                    <div
                        v-else
                        class="grid grid-cols-2 gap-2"
                    >
                        <div
                            v-for="img in currentImages"
                            :key="img.id"
                            class="h-20 w-full rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center"
                        >
                            <img
                                v-if="srcForImage(img)"
                                :src="srcForImage(img)"
                                :alt="form.title"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>
                </div>

                <!-- Upload new images -->
                <div>
                    <label for="images" class="block text-xs font-medium text-gray-700 mb-1">
                        Replace images
                    </label>
                    <input
                        id="images"
                        type="file"
                        accept="image/*"
                        multiple
                        class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-purple-700"
                        @change="onFileChange"
                    />
                    <p v-if="form.errors.images" class="mt-1 text-xs text-red-500">
                        {{ form.errors.images }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Leave empty to keep the current image(s). Uploading new
                        files will replace all existing images.
                    </p>
                </div>
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60 transition-colors duration-200"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Save changes</span>
                    <span v-else>Saving...</span>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    slide: {
        type: Object,
        required: true,
    },
});

// form state
const form = useForm({
    title: props.slide.title ?? '',
    subtitle: props.slide.subtitle ?? '',
    button_text: props.slide.button_text ?? '',
    button_link: props.slide.button_link ?? '',
    sort_order: props.slide.sort_order ?? 0,
    is_active: !!props.slide.is_active,
    images: [], // new images to REPLACE existing ones
});

// submit with PUT
const submit = () => {
    // If no new images are selected, remove the images field entirely
    if (form.images.length === 0) {
        const { images, ...formWithoutImages } = form;
        formWithoutImages.put(`/admin/hero-slides/${props.slide.id}`, {
            preserveScroll: true,
        });
    } else {
        form.put(`/admin/hero-slides/${props.slide.id}`, {
            forceFormData: true,
            preserveScroll: true,
        });
    }
};

// handle file input
const onFileChange = (event) => {
    const files = Array.from(event.target.files || []);
    form.images = files;
};

// current images from backend
const currentImages = computed(() => {
    return Array.isArray(props.slide.images) ? props.slide.images : [];
});

const srcForImage = (img) => {
    if (!img) return null;
    if (img.url) return img.url;
    if (img.image_path) return `/storage/${img.image_path}`;
    return null;
};
</script>

<style scoped>
/* Additional custom styles */
.transition-colors {
    transition: color 0.2s ease, background-color 0.2s ease;
}
</style>