<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Form from './Form.vue';
import { router, usePage } from '@inertiajs/vue3';

// Get origins from props
const props = defineProps({
  origins: {
    type: Array,
    default: () => []
  }
});

const page = usePage();
const origins = ref(props.origins);
const loading = ref(false);
const showForm = ref(false);
const editing = ref(null);
const query = ref('');

// Refresh origins data
const refreshOrigins = () => {
  loading.value = true;
  router.reload({
    only: ['origins'],
    preserveState: true,
    onFinish: () => {
      loading.value = false;
      // Update local origins with fresh data from props
      origins.value = page.props.origins || [];
    }
  });
};

// computed filtered list
const filtered = computed(() => {
  if (!query.value) return origins.value;
  const q = query.value.toLowerCase();
  return origins.value.filter(o =>
    (o.name || '').toLowerCase().includes(q) ||
    (o.province || '').toLowerCase().includes(q)
  );
});

const openCreate = () => {
  editing.value = null;
  showForm.value = true;
};

const openEdit = (origin) => {
  editing.value = { ...origin }; // Create a copy to avoid direct mutation
  showForm.value = true;
};

const onSaved = () => {
  showForm.value = false;
  // Refresh the list to get updated data
  refreshOrigins();
};

const onCancel = () => {
  showForm.value = false;
  editing.value = null;
};

const remove = async (origin) => {
  if (!confirm(`Delete origin "${origin.name}"? This action cannot be undone.`)) return;
  
  try {
    loading.value = true;
    await router.delete(`/admin/origins/${origin.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        // Remove from local list
        origins.value = origins.value.filter(o => o.id !== origin.id);
      },
      onError: (errors) => {
        console.error('Delete error:', errors);
        alert('Delete failed. Please try again.');
      }
    });
  } catch (error) {
    console.error('Delete error:', error);
    alert('Delete failed. Please try again.');
  } finally {
    loading.value = false;
  }
};

// toggleActive still exists if you want to keep API, but no button in UI
const toggleActive = async (origin) => {
  try {
    await router.post(`/admin/origins/${origin.id}/toggle-active`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        const index = origins.value.findIndex(o => o.id === origin.id);
        if (index !== -1) {
          origins.value[index].is_active = !origins.value[index].is_active;
        }
      },
      onError: (errors) => {
        console.error('Toggle error:', errors);
        alert('Failed to toggle active status.');
      }
    });
  } catch (error) {
    console.error('Toggle error:', error);
    alert('Failed to toggle active status.');
  }
};
</script>

<template>
  <AdminLayout title="Origins">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">Origins</h2>
          <p class="text-sm text-gray-600">Manage origin stories, farm locations, and images.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <input 
            v-model="query" 
            placeholder="Search by name or province"
            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          />

          <button 
            class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors"
            @click="openCreate"
          >
            Add Origin
          </button>
        </div>
      </div>

      <div v-if="loading" class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500">
        <div class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Loading...
        </div>
      </div>

      <div v-else class="grid gap-4">
        <div v-if="filtered.length === 0" class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500">
          <template v-if="query">
            No origins found matching "{{ query }}".
          </template>
          <template v-else>
            No origins yet.
            <button @click="openCreate" class="text-purple-600 hover:text-purple-700 font-medium">
              Create the first one
            </button>.
          </template>
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          <div 
            v-for="origin in filtered" 
            :key="origin.id"
            class="bg-white rounded-lg border border-gray-200 p-4 flex flex-col h-full transition-shadow hover:shadow-md"
          >
            <div class="flex gap-4">
              <div class="w-24 h-20 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                <img
                  :src="origin.main_image_path || '/images/placeholder-image.jpg'"
                  :alt="origin.name"
                  class="w-full h-full object-cover"
                />
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate">{{ origin.name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                      <span v-if="origin.province">{{ origin.province }}</span>
                      <span v-if="origin.location_text"> — {{ origin.location_text }}</span>
                    </p>
                  </div>

                  <div class="text-right shrink-0">
                    <p class="text-xs text-gray-400 mb-1">ID: {{ origin.id }}</p>
                    <!-- Static status pill: no click -->
                    <span
                      :class="[
                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                        origin.is_active
                          ? 'bg-green-100 text-green-800 border border-green-200'
                          : 'bg-gray-100 text-gray-700 border border-gray-200'
                      ]"
                    >
                      {{ origin.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>

                <p class="mt-2 text-sm text-gray-700 line-clamp-2">
                  {{ origin.description }}
                </p>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
              <button 
                class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition-colors flex-1"
                @click="openEdit(origin)"
              >
                Edit
              </button>
              <button 
                class="px-3 py-1 text-sm border border-red-300 rounded text-red-600 hover:bg-red-50 transition-colors flex-1"
                @click="remove(origin)"
              >
                Delete
              </button>
              <!-- View button removed for admin as requested -->
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
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>