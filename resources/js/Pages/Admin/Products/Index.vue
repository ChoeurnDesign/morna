<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import {
    PlusIcon,
    PencilSquareIcon,
    StarIcon,
    TrashIcon,
    FolderIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

// Use separate forms for actions and delete
const actionForm = useForm({});
const deleteForm = useForm({});

// Set as main product
const setAsMain = (product) => {
    if (!product.is_active) {
        alert('Cannot set inactive product as main.');
        return;
    }

    actionForm.post(`/admin/products/${product.id}/set-main`, {
        preserveScroll: true,
    });
};

// Kept for other usages; not exposed via UI now
const toggleStatus = (product) => {
    actionForm.post(`/admin/products/${product.id}/toggle-status`, {
        preserveScroll: true,
    });
};

// Delete product
const deleteProduct = (product) => {
    if (
        !window.confirm(
            `Are you sure you want to delete "${product.name}"? This action cannot be undone.`,
        )
    ) {
        return;
    }

    deleteForm.delete(`/admin/products/${product.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Delete success');
        },
        onError: (errors) => {
            console.error('Delete error:', errors);
        },
    });
};
</script>

<template>
    <AdminLayout title="Products Management">
        <div class="px-10 sm:px-14 lg:px-20 py-6">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6"
            >
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Manage your product catalog and set main product
                    </p>
                </div>
                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors mt-4 sm:mt-0"
                >
                    <PlusIcon class="w-4 h-4 mr-2" />
                    Add New Product
                </Link>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div
                    class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-200 text-xs font-medium text-gray-700 uppercase tracking-wider"
                >
                    <div class="col-span-4">Product</div>
                    <div class="col-span-2 text-center">Size</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-2 text-center">Actions</div>
                </div>

                <div v-if="products.length > 0" class="divide-y divide-gray-200">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors"
                    >
                        <!-- Product -->
                        <div class="col-span-4 flex items-center space-x-3">
                            <div
                                class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0"
                            >
                                <img
                                    v-if="product.image_path"
                                    :src="product.image_path"
                                    :alt="product.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400"
                                >
                                    <FolderIcon class="w-6 h-6" />
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <span
                                        v-if="product.is_main"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                    >
                                        Main
                                    </span>
                                </div>
                                <p
                                    class="text-xs text-gray-500 mt-1 line-clamp-1"
                                >
                                    {{ product.short_description || 'No description' }}
                                </p>
                            </div>
                        </div>

                        <!-- Size -->
                        <div class="col-span-2 text-center">
                            <span class="text-sm text-gray-900">{{
                                product.size_ml
                            }} ml</span>
                        </div>

                        <!-- Price -->
                        <div class="col-span-2 text-center">
                            <span class="text-sm text-gray-900"
                                >${{ product.price }}</span
                            >
                        </div>

                        <!-- Status -->
                        <div class="col-span-2 text-center">
                            <span
                                :class="[
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    product.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{ product.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="col-span-2 text-center">
                            <div
                                class="flex items-center justify-center space-x-2"
                            >
                                <!-- Edit -->
                                <Link
                                    :href="`/admin/products/${product.id}/edit`"
                                    class="inline-flex items-center p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                    title="Edit Product"
                                >
                                    <PencilSquareIcon class="w-4 h-4" />
                                </Link>

                                <!-- Set as Main -->
                                <button
                                    v-if="!product.is_main && product.is_active"
                                    @click="setAsMain(product)"
                                    class="inline-flex items-center p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                    title="Set as Main Product"
                                >
                                    <StarIcon class="w-4 h-4" />
                                </button>

                                <!-- Delete -->
                                <button
                                    @click="deleteProduct(product)"
                                    class="inline-flex items-center p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Delete Product"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-12">
                    <FolderIcon
                        class="w-12 h-12 text-gray-400 mx-auto mb-4"
                    />
                    <h3 class="text-sm font-medium text-gray-900 mb-1">
                        No products yet
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Get started by creating your first product.
                    </p>
                    <Link
                        href="/admin/products/create"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors"
                    >
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Add New Product
                    </Link>
                </div>
            </div>

            <!-- Info -->
            <div
                class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200"
            >
                <div class="flex">
                    <svg
                        class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">About Main Products</p>
                        <p class="mt-1">
                            The main product will be featured on the homepage.
                            Only one product can be set as main at a time.
                            You cannot delete the main product while it is the
                            only main product. Set another product as main
                            first if you need to remove it.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>