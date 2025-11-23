<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  initial: Object,
});

const emit = defineEmits(['saved', 'cancel']);

const form = ref({
  name: '',
  province: '',
  location_text: '',
  description: '',
  is_active: true,
  main_image: null,
  map_image: null,
});

const processing = ref(false);
const errors = ref({});

// Reset form when initial prop changes
watch(
  () => props.initial,
  (newInitial) => {
    if (newInitial) {
      form.value = {
        name: newInitial.name || '',
        province: newInitial.province || '',
        location_text: newInitial.location_text || '',
        description: newInitial.description || '',
        is_active: newInitial.is_active ?? true,
        main_image: null,
        map_image: null,
      };
    } else {
      form.value = {
        name: '',
        province: '',
        location_text: '',
        description: '',
        is_active: true,
        main_image: null,
        map_image: null,
      };
    }
    errors.value = {};
  },
  { immediate: true }
);

const handleFileChange = (event, field) => {
  const file = event.target.files[0];
  if (file) {
    form.value[field] = file;
  }
};

const submit = () => {
  processing.value = true;
  errors.value = {};

  const url = props.initial
    ? `/admin/origins/${props.initial.id}`
    : '/admin/origins';

  const formData = new FormData();

  // Normalize checkbox: send "1" when checked, omit when not
  formData.append('name', form.value.name ?? '');
  formData.append('province', form.value.province ?? '');
  formData.append('location_text', form.value.location_text ?? '');
  formData.append('description', form.value.description ?? '');
  if (form.value.is_active) {
    formData.append('is_active', '1');
  }

  if (form.value.main_image instanceof File) {
    formData.append('main_image', form.value.main_image);
  }
  if (form.value.map_image instanceof File) {
    formData.append('map_image', form.value.map_image);
  }

  if (props.initial) {
    formData.append('_method', 'PUT');
  }

  router.post(url, formData, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      emit('saved');
    },
    onError: (err) => {
      errors.value = err || {};
      console.error('Form errors:', err);
    },
    onFinish: () => {
      processing.value = false;
    },
  });
};

const cancel = () => {
  emit('cancel');
};
</script>

<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-semibold text-gray-900">
        {{ initial ? 'Edit Origin' : 'Create New Origin' }}
      </h3>
      <button
        @click="cancel"
        class="text-gray-400 hover:text-gray-600 transition-colors"
        :disabled="processing"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
        <input
          v-model="form.name"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.name }"
          :disabled="processing"
        />
        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
      </div>

      <!-- Province -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
        <input
          v-model="form.province"
          type="text"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :disabled="processing"
        />
      </div>

      <!-- Location Text -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Location Text</label>
        <input
          v-model="form.location_text"
          type="text"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :disabled="processing"
        />
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea
          v-model="form.description"
          rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :disabled="processing"
        ></textarea>
      </div>

      <!-- Main Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Main Image</label>
        <input
          type="file"
          accept="image/*"
          @change="handleFileChange($event, 'main_image')"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.main_image }"
          :disabled="processing"
        />
        <p class="mt-1 text-xs text-gray-500">Recommended: Landscape orientation, high quality</p>
        <p v-if="errors.main_image" class="mt-1 text-sm text-red-600">{{ errors.main_image }}</p>
      </div>

      <!-- Map Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Map Image</label>
        <input
          type="file"
          accept="image/*"
          @change="handleFileChange($event, 'map_image')"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.map_image }"
          :disabled="processing"
        />
        <p class="mt-1 text-xs text-gray-500">Optional: Map showing the location</p>
        <p v-if="errors.map_image" class="mt-1 text-sm text-red-600">{{ errors.map_image }}</p>
      </div>

      <!-- Active Status -->
      <div class="flex items-center">
        <input
          v-model="form.is_active"
          type="checkbox"
          id="is_active"
          class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
          :disabled="processing"
        />
        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
      </div>

      <!-- General error placeholder (if you want) -->
      <div v-if="errors.general" class="p-3 bg-red-50 border border-red-200 rounded-md">
        <p class="text-sm text-red-600">{{ errors.general }}</p>
      </div>

      <!-- Form Actions -->
      <div class="flex gap-3 pt-4 border-t border-gray-200">
        <button
          type="button"
          @click="cancel"
          class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
          :disabled="processing"
        >
          Cancel
        </button>
        <button
          type="submit"
          class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="processing"
        >
          <span v-if="processing">Saving...</span>
          <span v-else>{{ initial ? 'Update' : 'Create' }} Origin</span>
        </button>
      </div>
    </form>
  </div>
</template>