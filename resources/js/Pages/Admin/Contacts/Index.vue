<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
  contacts: {
    type: Array,
    default: () => [],
  },
});

const search = ref('');
const loading = ref(false);

const filtered = computed(() => {
  if (!search.value) return props.contacts;
  const q = search.value.toLowerCase();
  return props.contacts.filter((c) =>
    (c.name || '').toLowerCase().includes(q) ||
    (c.email || '').toLowerCase().includes(q) ||
    (c.phone || '').toLowerCase().includes(q) ||
    (c.message || '').toLowerCase().includes(q)
  );
});

const markRead = (contact) => {
  router.post(
    `/admin/contacts/${contact.id}/mark-read`,
    {},
    { preserveScroll: true }
  );
};

const markUnread = (contact) => {
  router.post(
    `/admin/contacts/${contact.id}/mark-unread`,
    {},
    { preserveScroll: true }
  );
};

const removeContact = (contact) => {
  if (!confirm(`Delete message from "${contact.name}"? This cannot be undone.`)) {
    return;
  }

  loading.value = true;
  router.delete(`/admin/contacts/${contact.id}`, {
    preserveScroll: true,
    onFinish: () => {
      loading.value = false;
    },
  });
};
</script>

<template>
  <AdminLayout title="Contacts">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">Contact messages</h2>
          <p class="text-sm text-gray-600">
            Messages submitted from the public contact form.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <input
            v-model="search"
            type="text"
            placeholder="Search by name, email, phone, or message"
            class="px-3 py-2 border border-gray-300 rounded-md text-base focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
          />
        </div>
      </div>

      <div v-if="loading" class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500">
        <div class="flex items-center justify-center">
          <svg
            class="animate-spin -ml-1 mr-3 h-5 w-5 text-purple-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          Loading...
        </div>
      </div>

      <div v-else>
        <div
          v-if="filtered.length === 0"
          class="p-6 bg-white rounded-lg shadow-sm text-center text-gray-500"
        >
          No contact messages found.
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="contact in filtered"
            :key="contact.id"
            class="bg-white rounded-lg border border-gray-200 p-4 flex flex-col md:flex-row md:items-start md:justify-between gap-3 hover:shadow-md transition-shadow"
          >
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <h3 class="font-semibold text-gray-900 truncate">
                  {{ contact.name }}
                </h3>
                <span
                  :class="[
                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                    contact.status === 'read'
                      ? 'bg-gray-100 text-gray-700 border border-gray-200'
                      : 'bg-purple-100 text-purple-800 border border-purple-200',
                  ]"
                >
                  {{ contact.status === 'read' ? 'Read' : 'New' }}
                </span>
              </div>

              <div class="text-xs text-gray-500 mb-1">
                <span v-if="contact.phone">📞 {{ contact.phone }}</span>
                <span v-if="contact.phone && contact.email" class="mx-1">•</span>
                <span v-if="contact.email">✉ {{ contact.email }}</span>
              </div>

              <p class="text-xs text-gray-400 mb-2">
                {{ contact.created_at }}
              </p>

              <p class="text-sm text-gray-700 line-clamp-2">
                {{ contact.message || 'No message provided.' }}
              </p>
            </div>

            <div class="flex flex-row md:flex-col gap-2 md:items-end">
              <a
                :href="`/admin/contacts/${contact.id}`"
                class="px-3 py-1 text-xs border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-center"
              >
                View
              </a>

              <button
                v-if="contact.status !== 'read'"
                type="button"
                class="px-3 py-1 text-xs border border-green-300 rounded text-green-700 hover:bg-green-50"
                @click="markRead(contact)"
              >
                Mark read
              </button>
              <button
                v-else
                type="button"
                class="px-3 py-1 text-xs border border-purple-300 rounded text-purple-700 hover:bg-purple-50"
                @click="markUnread(contact)"
              >
                Mark unread
              </button>

              <button
                type="button"
                class="px-3 py-1 text-xs border border-red-300 rounded text-red-600 hover:bg-red-50"
                @click="removeContact(contact)"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
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
</style>