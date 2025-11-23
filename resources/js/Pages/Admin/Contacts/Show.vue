<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  contact: {
    type: Object,
    required: true,
  },
});

const markRead = () => {
  router.post(
    `/admin/contacts/${props.contact.id}/mark-read`,
    {},
    { preserveScroll: true }
  );
};

const markUnread = () => {
  router.post(
    `/admin/contacts/${props.contact.id}/mark-unread`,
    {},
    { preserveScroll: true }
  );
};

const removeContact = () => {
  if (!confirm('Delete this contact message? This cannot be undone.')) return;
  router.delete(`/admin/contacts/${props.contact.id}`);
};
</script>

<template>
  <AdminLayout :title="`Contact #${contact.id}`">
    <div class="max-w-3xl space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-900">
            Message from {{ contact.name }}
          </h2>
          <p class="text-sm text-gray-500">
            {{ contact.created_at }}
          </p>
        </div>

        <div class="flex gap-2">
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
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-4">
        <div class="grid sm:grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">
              Name
            </p>
            <p class="text-gray-900 font-medium">
              {{ contact.name }}
            </p>
          </div>

          <div>
            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">
              Phone
            </p>
            <p class="text-gray-900">
              {{ contact.phone || '—' }}
            </p>
          </div>

          <div>
            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">
              Email
            </p>
            <p class="text-gray-900">
              {{ contact.email || '—' }}
            </p>
          </div>

          <div>
            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">
              Status
            </p>
            <p class="text-gray-900 capitalize">
              {{ contact.status }}
            </p>
          </div>
        </div>

        <div>
          <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">
            Message
          </p>
          <p class="text-gray-900 whitespace-pre-line text-sm">
            {{ contact.message || 'No message provided.' }}
          </p>
        </div>
      </div>

      <div class="flex flex-wrap gap-3">
        <button
          v-if="contact.status !== 'read'"
          type="button"
          class="px-4 py-2 text-sm border border-green-300 rounded text-green-700 hover:bg-green-50"
          @click="markRead"
        >
          Mark as read
        </button>
        <button
          v-else
          type="button"
          class="px-4 py-2 text-sm border border-purple-300 rounded text-purple-700 hover:bg-purple-50"
          @click="markUnread"
        >
          Mark as unread
        </button>

        <button
          type="button"
          class="px-4 py-2 text-sm border border-red-300 rounded text-red-600 hover:bg-red-50"
          @click="removeContact"
        >
          Delete
        </button>
      </div>
    </div>
  </AdminLayout>
</template>