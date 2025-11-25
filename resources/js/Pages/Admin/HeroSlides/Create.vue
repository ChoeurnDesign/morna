<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    subtitle: '',
    button_text: '',
    button_link: '',
    sort_order: 0,
    is_active: true,
    images: [], // multiple images
});

const submit = () => {
    form.post('/admin/hero-slides', {
        forceFormData: true,
        preserveScroll: true,
    });
};

const onFileChange = (event) => {
    const files = event.target.files || [];
    form.images = Array.from(files); // store File[]
};
</script>

<template>
    <AdminLayout title="New hero slide">
        <div class="mb-6 flex items-center gap-4">
            <Link
                href="/admin/hero-slides"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                aria-label="Back to list"
            >
                <ArrowLeftIcon class="w-5 h-5" />
            </Link>

            <!-- Title and description on the right -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    New hero slide
                </h2>
                <p class="text-sm text-gray-600">
                    Add a slide to the homepage hero section. You can upload multiple
                    images for this slide.
                </p>
            </div>
        </div>

        <form
            @submit.prevent="submit"
            class="max-w-2xl space-y-5"
        >
            <!-- Title -->
            <div>
                <label
                    for="title"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="block w-full rounded-lg border-gray-300 text-base md:text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    @input="form.clearErrors('title')"
                />
                <p
                    v-if="form.errors.title"
                    class="mt-1 text-xs text-red-500"
                >
                    {{ form.errors.title }}
                </p>
            </div>

            <!-- Subtitle -->
            <div>
                <label
                    for="subtitle"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Subtitle
                </label>
                <textarea
                    id="subtitle"
                    v-model="form.subtitle"
                    rows="2"
                    class="block w-full rounded-lg border-gray-300 text-base md:text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                ></textarea>
                <p
                    v-if="form.errors.subtitle"
                    class="mt-1 text-xs text-red-500"
                >
                    {{ form.errors.subtitle }}
                </p>
            </div>

            <!-- Button text & link -->
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label
                        for="button_text"
                        class="block text-xs font-medium text-gray-700 mb-1"
                    >
                        Button text
                    </label>
                    <input
                        id="button_text"
                        v-model="form.button_text"
                        type="text"
                        class="block w-full rounded-lg border-gray-300 text-base md:text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p
                        v-if="form.errors.button_text"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.button_text }}
                    </p>
                </div>
                <div>
                    <label
                        for="button_link"
                        class="block text-xs font-medium text-gray-700 mb-1"
                    >
                        Button link
                    </label>
                    <input
                        id="button_link"
                        v-model="form.button_link"
                        type="text"
                        placeholder="/product or full URL"
                        class="block w-full rounded-lg border-gray-300 text-base md:text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p
                        v-if="form.errors.button_link"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.button_link }}
                    </p>
                </div>
            </div>

            <!-- Sort + Active -->
            <div class="grid gap-4 md:grid-cols-2 items-center">
                <div>
                    <label
                        for="sort_order"
                        class="block text-xs font-medium text-gray-700 mb-1"
                    >
                        Sort order
                    </label>
                    <input
                        id="sort_order"
                        v-model.number="form.sort_order"
                        type="number"
                        class="block w-full rounded-lg border-gray-300 text-base md:text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    />
                    <p
                        v-if="form.errors.sort_order"
                        class="mt-1 text-xs text-red-500"
                    >
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
                    <label
                        for="is_active"
                        class="text-xs text-gray-700"
                    >
                        Active (show on homepage)
                    </label>
                </div>
            </div>

            <!-- Images (multiple) -->
            <div>
                <label
                    for="images"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Images <span class="text-red-500">*</span>
                </label>
                <input
                    id="images"
                    type="file"
                    accept="image/*"
                    multiple
                    class="block w-full text-base md:text-sm text-gray-700 file:mr-3 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-purple-700"
                    @change="onFileChange"
                />
                <p
                    v-if="form.errors.images"
                    class="mt-1 text-xs text-red-500"
                >
                    {{ form.errors.images }}
                </p>
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Create slide</span>
                    <span v-else>Saving...</span>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>