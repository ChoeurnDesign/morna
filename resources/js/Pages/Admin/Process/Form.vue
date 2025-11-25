<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { computed, ref } from 'vue';

const props = defineProps({
    form: Object,
    pageTitle: String,
    pageDescription: String,
    submitText: String,
    isEdit: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['submit']);

const commonIcons = [
    'Sprout', 'TreePine', 'Flower', 'Leaf', 'Trees',
    'Harvest', 'Package', 'Factory', 'Truck', 'Store',
    'Droplet', 'TestTube', 'Beaker', 'Filter', 'Bottle',
    'GlassWater', 'Heart', 'Star', 'CheckCircle', 'Award'
];

const fileInput = ref(null);
const newImagePreview = ref(null);

// Fixed: Better file handling
const onFileChange = (e) => {
    const file = e.target.files[0];
    
    // Clear any previous errors
    if (props.form.errors.image_path) {
        props.form.clearErrors('image_path');
    }
    
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file');
            e.target.value = '';
            return;
        }
        
        props.form.image_path = file;
        newImagePreview.value = URL.createObjectURL(file);
    } else {
        // For new records, completely remove the image_path field when no file is selected
        if (!props.isEdit) {
            delete props.form.image_path;
        } else {
            props.form.image_path = props.form.image_path;
        }
        newImagePreview.value = null;
    }
};

// Fixed: Handle image removal properly
const removeImage = () => {
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    
    if (!props.isEdit) {
        // For new records, remove the field entirely
        delete props.form.image_path;
    } else {
        // For edits, set to null to indicate removal of existing image
        props.form.image_path = null;
    }
    
    newImagePreview.value = null;
    
    // Clear any file input validation errors
    if (props.form.errors.image_path) {
        props.form.clearErrors('image_path');
    }
};

const currentImagePath = computed(() => {
    if (newImagePreview.value) {
        return newImagePreview.value;
    }
    
    if (props.isEdit && typeof props.form.image_path === 'string' && props.form.image_path) {
        return `/storage/${props.form.image_path}`;
    }

    return null;
});

const getRoute = (name, params) => {
    if (typeof window.route === 'function') {
        return window.route(name, params);
    }
    if (typeof route === 'function') {
        return route(name, params);
    }
    console.error("Ziggy route helper is unavailable for:", name);
    return name;
};

const submitForm = () => {
    emit('submit');
};
</script>

<template>
    <div class="mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                :href="getRoute('admin.process-steps.index')"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <ArrowLeftIcon class="w-5 h-5" />
            </Link>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ pageTitle }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ pageDescription }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6 shadow-md">
                <!-- Step Number -->
                <div>
                    <label for="step_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Step Number *
                    </label>
                    <input
                        id="step_number"
                        v-model="form.step_number"
                        type="number"
                        min="1"
                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        required
                    >
                    <div v-if="form.errors.step_number" class="mt-1 text-sm text-red-600">
                        {{ form.errors.step_number }}
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title *
                    </label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="e.g., Harvesting, Processing, Bottling"
                        required
                    >
                    <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description *
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Describe this step in the process..."
                        required
                    ></textarea>
                    <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </div>
                </div>
                
                <!-- Image Upload (File Input) -->
                <div>
                    <label for="image_path_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Step Image (Max 2MB)
                    </label>
                    <input
                        id="image_path_file"
                        ref="fileInput"
                        type="file"
                        @change="onFileChange"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                        accept="image/*"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Optional: Upload an image for this process step
                    </p>
                    <div v-if="form.errors.image_path" class="mt-1 text-sm text-red-600">
                        {{ form.errors.image_path }}
                    </div>
                    
                    <!-- Image Preview -->
                    <div v-if="currentImagePath" class="mt-4">
                        <p class="text-xs font-medium text-gray-600 mb-2">
                            Current/New Image:
                        </p>
                        <img 
                            :src="currentImagePath" 
                            alt="Process Step Image"
                            class="w-32 h-32 object-cover rounded-lg shadow-md"
                        />
                        <button 
                            type="button" 
                            @click="removeImage" 
                            class="mt-2 text-xs text-red-600 hover:text-red-800 transition-colors"
                        >
                            Remove Image
                        </button>
                    </div>
                </div>

                <!-- Icon Name -->
                <div>
                    <label for="icon_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Icon
                    </label>
                    <select
                        id="icon_name"
                        v-model="form.icon_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="">Select an icon (Optional)</option>
                        <option
                            v-for="icon in commonIcons"
                            :key="icon"
                            :value="icon"
                        >
                            {{ icon }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Selected icon:
                        <span class="font-semibold text-purple-600">
                            {{ form.icon_name || 'None' }}
                        </span>
                    </p>
                    <div v-if="form.errors.icon_name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.icon_name }}
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex items-center pt-4 border-t border-gray-100">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                    >
                    <label
                        for="is_active"
                        class="ml-2 text-sm font-medium text-gray-700 cursor-pointer"
                    >
                        Active (show on website)
                    </label>
                </div>
                <div v-if="form.errors.is_active" class="mt-1 text-sm text-red-600">
                    {{ form.errors.is_active }}
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 p-4 bg-gray-50 rounded-xl shadow-inner">
                <Link
                    :href="getRoute('admin.process-steps.index')"
                    class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium rounded-lg transition-colors"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    :class="[
                        'px-6 py-2 bg-purple-600 text-white rounded-lg font-medium shadow-lg transition-colors duration-200',
                        'focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2',
                        form.processing ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-700 hover:shadow-xl'
                    ]"
                >
                    {{ form.processing ? (isEdit ? 'Updating...' : 'Saving...') : submitText }}
                </button>
            </div>
        </form>
    </div>
</template>