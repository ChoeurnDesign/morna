<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Form from './Form.vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
  faqs: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const faqs = ref(props.faqs);
const loading = ref(false);
const showForm = ref(false);
const editing = ref(null);
const query = ref('');
const isReordering = ref(false);
const dragIndex = ref(null);

// Refresh FAQs from server
const refreshFaqs = () => {
  loading.value = true;
  router.reload({
    only: ['faqs'],
    preserveState: true,
    onFinish: () => {
      loading.value = false;
      faqs.value = page.props.faqs || [];
    },
  });
};

const filtered = computed(() => {
  if (!query.value) return faqs.value;
  const q = query.value.toLowerCase();
  return faqs.value.filter((f) =>
    (f.question || '').toLowerCase().includes(q) ||
    (f.answer || '').toLowerCase().includes(q)
  );
});

const openCreate = () => {
  editing.value = null;
  showForm.value = true;
};

const openEdit = (faq) => {
  editing.value = { ...faq };
  showForm.value = true;
};

const onSaved = () => {
  showForm.value = false;
  editing.value = null;
  refreshFaqs();
};

const onCancel = () => {
  showForm.value = false;
  editing.value = null;
};

const remove = (faq) => {
  if (!confirm(`Delete FAQ "${faq.question}"? This action cannot be undone.`)) return;

  loading.value = true;
  router.delete(`/admin/faqs/${faq.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      faqs.value = faqs.value.filter((f) => f.id !== faq.id);
    },
    onError: () => {
      alert('Delete failed. Please try again.');
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};

const toggleStatus = (faq) => {
  router.post(`/admin/faqs/${faq.id}/toggle-status`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      const idx = faqs.value.findIndex((f) => f.id === faq.id);
      if (idx !== -1) {
        faqs.value[idx].is_active = !faqs.value[idx].is_active;
      }
    },
    onError: () => {
      alert('Failed to toggle FAQ status.');
    },
  });
};

// Simple drag-reorder (by index)
const startDrag = (index) => {
  if (!isReordering.value) return;
  dragIndex.value = index;
};

const onDrop = (index) => {
  if (!isReordering.value || dragIndex.value === null) return;
  const items = [...faqs.value];
  const moved = items.splice(dragIndex.value, 1)[0];
  items.splice(index, 0, moved);
  faqs.value = items;
  dragIndex.value = null;
};

const saveOrder = () => {
  const order = faqs.value.map((f) => f.id);
  router.post('/admin/faqs/reorder', { order }, {
    preserveScroll: true,
    onSuccess: () => {
      isReordering.value = false;
    },
    onError: () => {
      alert('Failed to save order.');
    },
  });
};
</script>

<template>
  <AdminLayout title="FAQs">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">FAQs</h2>
          <p class="text-sm text-gray-600">
            Manage frequently asked questions shown on the website.
          </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <input
            v-model="query"
            placeholder="Search question or answer"
            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          />

          <div class="flex gap-2">
            <button
              type="button"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
              :class="{ 'bg-purple-50 border-purple-300 text-purple-700': isReordering }"
              @click="isReordering = !isReordering"
            >
              {{ isReordering ? 'Done ordering' : 'Reorder' }}
            </button>
            <button
              v-if="isReordering"
              type="button"
              class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700"
              @click="saveOrder"
            >
              Save Order
            </button>
            <button
              type="button"
              class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700"
              @click="openCreate"
            >
              Add FAQ
            </button>
          </div>
        </div>
      </div>

      <div v-if="loading" class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500">
        <div class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
          </svg>
          Loading...
        </div>
      </div>

      <div v-else class="grid gap-4">
        <div
          v-if="filtered.length === 0"
          class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500"
        >
          <template v-if="query">
            No FAQs found matching "{{ query }}".
          </template>
          <template v-else>
            No FAQs yet.
            <button
              @click="openCreate"
              class="text-purple-600 hover:text-purple-700 font-medium"
            >
              Create the first one
            </button>.
          </template>
        </div>

        <div v-else class="space-y-2">
          <div
            v-for="(faq, index) in filtered"
            :key="faq.id"
            class="bg-white rounded-lg border border-gray-200 p-4 flex flex-col gap-3 transition-shadow hover:shadow-md"
            :draggable="isReordering"
            @dragstart.prevent="startDrag(index)"
            @dragover.prevent
            @drop.prevent="onDrop(index)"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-gray-900 truncate">
                    {{ faq.question }}
                  </h3>
                  <span
                    v-if="isReordering"
                    class="inline-flex items-center text-xs text-gray-400"
                  >
                    :: drag
                  </span>
                </div>
                <p class="mt-2 text-sm text-gray-700 line-clamp-3">
                  {{ faq.answer }}
                </p>
              </div>

              <div class="text-right shrink-0">
                <p class="text-xs text-gray-400 mb-1">ID: {{ faq.id }}</p>
                <button
                  type="button"
                  :class="[
                    'px-2 py-1 border rounded text-xs font-medium transition-colors',
                    faq.is_active
                      ? 'bg-green-100 text-green-800 border-green-200 hover:bg-green-200'
                      : 'bg-gray-100 text-gray-800 border-gray-200 hover:bg-gray-200',
                  ]"
                  @click="toggleStatus(faq)"
                >
                  {{ faq.is_active ? 'Active' : 'Inactive' }}
                </button>
              </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex gap-2">
              <button
                type="button"
                class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition-colors flex-1"
                @click="openEdit(faq)"
              >
                Edit
              </button>
              <button
                type="button"
                class="px-3 py-1 text-sm border border-red-300 rounded text-red-600 hover:bg-red-50 transition-colors flex-1"
                @click="remove(faq)"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form modal -->
    <div
      v-if="showForm"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
    >
      <div
        class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-lg shadow-xl"
        @click.stop
      >
        <Form
          :initial="editing"
          @saved="onSaved"
          @cancel="onCancel"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>