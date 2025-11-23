<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  initial: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['saved', 'cancel']);

const form = ref({
  question: '',
  answer: '',
  is_active: true,
});

const processing = ref(false);
const errors = ref({});

watch(
  () => props.initial,
  (val) => {
    if (val) {
      form.value = {
        question: val.question || '',
        answer: val.answer || '',
        is_active: val.is_active ?? true,
      };
    } else {
      form.value = {
        question: '',
        answer: '',
        is_active: true,
      };
    }
    errors.value = {};
  },
  { immediate: true }
);

const submit = () => {
  processing.value = true;
  errors.value = {};

  const url = props.initial
    ? `/admin/faqs/${props.initial.id}`
    : '/admin/faqs';

  const data = {
    question: form.value.question,
    answer: form.value.answer,
  };
  if (form.value.is_active) {
    data.is_active = 1;
  }

  const options = {
    preserveScroll: true,
    onSuccess: () => {
      emit('saved');
    },
    onError: (err) => {
      errors.value = err || {};
    },
    onFinish: () => {
      processing.value = false;
    },
  };

  if (props.initial) {
    router.post(url, { ...data, _method: 'PUT' }, options);
  } else {
    router.post(url, data, options);
  }
};

const cancel = () => {
  emit('cancel');
};
</script>

<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-semibold text-gray-900">
        {{ initial ? 'Edit FAQ' : 'Create New FAQ' }}
      </h3>
      <button
        type="button"
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
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Question *
        </label>
        <input
          v-model="form.question"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.question }"
          :disabled="processing"
        />
        <p v-if="errors.question" class="mt-1 text-sm text-red-600">
          {{ errors.question }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Answer *
        </label>
        <textarea
          v-model="form.answer"
          rows="4"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.answer }"
          :disabled="processing"
        ></textarea>
        <p v-if="errors.answer" class="mt-1 text-sm text-red-600">
          {{ errors.answer }}
        </p>
      </div>

      <div class="flex items-center">
        <input
          v-model="form.is_active"
          type="checkbox"
          id="is_active"
          class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
          :disabled="processing"
        />
        <label for="is_active" class="ml-2 block text-sm text-gray-700">
          Active (visible on website)
        </label>
      </div>

      <div v-if="errors.general" class="p-3 bg-red-50 border border-red-200 rounded-md">
        <p class="text-sm text-red-600">{{ errors.general }}</p>
      </div>

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
          <span v-else>{{ initial ? 'Update' : 'Create' }} FAQ</span>
        </button>
      </div>
    </form>
  </div>
</template>