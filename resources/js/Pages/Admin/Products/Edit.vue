<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

// Initialize form data with proper defaults
const form = useForm({
    name: props.product.name || '',
    short_description: props.product.short_description || '',
    description: props.product.description || '',
    size_ml: props.product.size_ml || 250,
    price: props.product.price || '',
    ingredients_text: props.product.ingredients_text || '',
    image: null,
    is_active: props.product.is_active ?? true,
    _method: 'PUT' // Add this for proper form method spoofing
});

const imagePreview = ref(props.product.image_path);

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    
    // Clear the file input element
    const fileInput = document.getElementById('image-upload');
    if (fileInput) {
        fileInput.value = '';
    }
};

const submit = () => {
    // Use post method with _method=PUT for better compatibility
    form.post(`/admin/products/${props.product.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Add any success handling here
        },
        onError: (errors) => {
            console.log('Form errors:', errors);
        }
    });
};
</script>

<template>
    <AdminLayout title="Edit Product">
        <div class="flex flex-col h-full overflow-hidden">
            <div class="px-10 sm:px-14 lg:px-20 py-6 overflow-y-auto">
            
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                        <p class="text-sm text-gray-600 mt-1">Update product information</p>
                    </div>
                    <Link
                        href="/admin/products"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        ← Back to Products
                    </Link>
                </div>

                <!-- Success/Error Messages -->
<div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
    {{ $page.props.flash.success }}
</div>

<div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
    {{ $page.props.flash.error }}
</div>

                <div class="max-w-4xl pb-20">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                            
                            <div class="grid gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Name *
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="form.name"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        placeholder="e.g., Original Mulberry Drink"
                                    />
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <div>
                                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Short Description
                                    </label>
                                    <textarea
                                        id="short_description"
                                        v-model="form.short_description"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        placeholder="Brief description for product listings..."
                                    ></textarea>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ form.short_description?.length || 0 }}/500 characters
                                    </p>
                                    <p v-if="form.errors.short_description" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.short_description }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="size_ml" class="block text-sm font-medium text-gray-700 mb-2">
                                            Size (ml) *
                                        </label>
                                        <input
                                            type="number"
                                            id="size_ml"
                                            v-model="form.size_ml"
                                            required
                                            min="1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        />
                                        <p v-if="form.errors.size_ml" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.size_ml }}
                                        </p>
                                    </div>

                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                            Price ($) *
                                        </label>
                                        <input
                                            type="number"
                                            id="price"
                                            v-model="form.price"
                                            required
                                            min="0"
                                            step="0.01"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        />
                                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.price }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h2>
                            
                            <div class="flex flex-col sm:flex-row gap-6">
                                <div class="sm:w-48">
                                    <div class="aspect-[3/4] rounded-lg border-2 border-dashed border-gray-300 overflow-hidden bg-gray-50">
                                        <div v-if="imagePreview" class="w-full h-full relative">
                                            <img
                                                :src="imagePreview"
                                                alt="Product preview"
                                                class="w-full h-full object-contain"
                                            />
                                            <button
                                                type="button"
                                                @click="removeImage"
                                                class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload New Image
                                    </label>
                                    <input
                                        type="file"
                                        id="image-upload"
                                        @change="handleImageChange"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                    />
                                    <p class="mt-2 text-xs text-gray-500">
                                        Recommended: Square image, JPG or PNG, max 2MB
                                    </p>
                                    <p class="mt-2 text-xs text-gray-600">
                                        Leave empty to keep current image
                                    </p>
                                    <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.image }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detailed Information</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Description
                                    </label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        placeholder="Detailed product description..."
                                    ></textarea>
                                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <div>
                                    <label for="ingredients_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ingredients
                                    </label>
                                    <textarea
                                        id="ingredients_text"
                                        v-model="form.ingredients_text"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        placeholder="List of ingredients, one per line..."
                                    ></textarea>
                                    <p v-if="form.errors.ingredients_text" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.ingredients_text }}
                                    </p>
                                </div>

                                <div>
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.is_active"
                                            :disabled="product.is_main"
                                            :class="[
                                                'rounded border-gray-300 focus:ring-purple-500',
                                                product.is_main ? 'text-gray-400' : 'text-purple-600'
                                            ]"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">
                                            Product is active and visible to customers
                                            <span v-if="product.is_main" class="text-orange-600 text-xs ml-1">(Main product cannot be deactivated)</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link
                                href="/admin/products"
                                class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                :class="[
                                    'px-6 py-2 text-sm font-medium text-white rounded-lg transition-colors',
                                    form.processing
                                        ? 'bg-purple-400 cursor-not-allowed'
                                        : 'bg-purple-600 hover:bg-purple-700'
                                ]"
                            >
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Product</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>