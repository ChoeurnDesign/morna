<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    slides: {
        type: Object,
        required: true,
    },
});

const deleteForm = useForm({});

const destroy = (slide) => {
    if (!confirm(`Delete slide: "${slide.title}"?`)) return;

    deleteForm.delete(`/admin/hero-slides/${slide.id}`, {
        preserveScroll: true,
    });
};

/**
 * Thumbnail URL for first image of this slide.
 */
const primaryImageUrl = (slide) => {
    if (!slide.images || !Array.isArray(slide.images) || !slide.images.length) {
        return null;
    }

    const img = slide.images[0];

    if (img.url) return img.url;
    if (img.image_path) return `/storage/${img.image_path}`;
    return null;
};
</script>

<template>
    <AdminLayout title="Hero slides">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl text-gray-900">
                    Hero slides
                </h2>
                <p class="text-sm text-gray-600">
                    Manage the slides that appear in the home page hero section.
                </p>
            </div>

            <Link
                href="/admin/hero-slides/create"
                class="inline-flex items-center px-4 py-2 rounded-full bg-purple-600 text-white text-sm font-medium hover:bg-purple-700"
            >
                + New slide
            </Link>
        </div>

        <!-- flash -->
        <div
            v-if="$page.props.flash && $page.props.flash.success"
            class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 text-sm px-4 py-2"
        >
            {{ $page.props.flash.success }}
        </div>

        <!-- table -->
        <div
            v-if="slides.data && slides.data.length"
            class="overflow-hidden rounded-xl border bg-white shadow-sm"
        >
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-500">
                            Image
                        </th>
                        <th class="px-4 py-2 text-left text-gray-500">
                            Title
                        </th>
                        <th class="px-4 py-2 text-left text-gray-500">
                            Active
                        </th>
                        <th class="px-4 py-2 text-left text-gray-500">
                            Sort order
                        </th>
                        <th class="px-4 py-2 text-right text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="slide in slides.data"
                        :key="slide.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-4 py-2">
                            <div
                                class="h-12 w-20 rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center"
                            >
                                <img
                                    v-if="primaryImageUrl(slide)"
                                    :src="primaryImageUrl(slide)"
                                    :alt="slide.title"
                                    class="w-full h-full object-cover"
                                />
                                <span
                                    v-else
                                    class="text-[10px] text-gray-400"
                                >
                                    No image
                                </span>
                            </div>
                            <p
                                v-if="slide.images && slide.images.length > 1"
                                class="mt-1 text-[10px] text-gray-500 text-center"
                            >
                                + {{ slide.images.length - 1 }} more
                            </p>
                        </td>

                        <td class="px-4 py-2 align-top">
                            <div class="text-gray-900">
                                {{ slide.title }}
                            </div>
                            <div
                                v-if="slide.subtitle"
                                class="text-xs text-gray-500 line-clamp-2"
                            >
                                {{ slide.subtitle }}
                            </div>
                        </td>

                        <td class="px-4 py-2 align-top">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs"
                                :class="slide.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'"
                            >
                                {{ slide.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-4 py-2 align-top text-gray-700">
                            {{ slide.sort_order }}
                        </td>

                        <td class="px-4 py-2 align-top text-right">
                            <div class="inline-flex items-center gap-2">
                                <Link
                                    :href="`/admin/hero-slides/${slide.id}/edit`"
                                    class="text-xs text-purple-700 hover:underline"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="text-xs text-red-600 hover:underline"
                                    @click="destroy(slide)"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- pagination -->
            <div
                v-if="slides.links && slides.links.length > 3"
                class="border-t bg-gray-50 px-4 py-2 text-xs text-gray-600 flex flex-wrap gap-1"
            >
                <Link
                    v-for="link in slides.links"
                    :key="link.url ?? link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-2 py-1 rounded"
                    :class="[
                        link.active
                            ? 'bg-purple-100 text-purple-700 font-medium'
                            : 'hover:bg-gray-100',
                        !link.url ? 'text-gray-400 cursor-default' : '',
                    ]"
                    preserve-scroll
                />
            </div>
        </div>

        <!-- empty state -->
        <div
            v-else
            class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500"
        >
            No hero slides yet. Create your first slide to power the home page
            hero section.
        </div>
    </AdminLayout>
</template>
