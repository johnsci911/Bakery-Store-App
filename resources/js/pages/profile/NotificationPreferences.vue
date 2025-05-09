<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { PropType, ref, watch } from 'vue';

interface NotificationPreferences {
  store_reopen: boolean;
}

const props = defineProps({
  notificationPreferences: {
    type: Object as PropType<NotificationPreferences>,
    required: true
  }
});

interface FormData {
  store_reopen_notification: boolean;
}

const form = useForm<FormData>({
  store_reopen_notification: props.notificationPreferences.store_reopen
});

const successMessage = ref('');
const errorMessage = ref('');

const updatePreferences = () => {
  form.post(route('user-notification-preferences.update'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Preferences updated successfully!';
      errorMessage.value = '';
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
    },
    onError: () => {
      errorMessage.value = 'Failed to update preferences. Please try again.';
      successMessage.value = '';
    }
  });
};

watch(() => form.store_reopen_notification, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    updatePreferences();
  }
});

const goBack = () => {
  router.visit(route('dashboard')); // Assuming you have a named route for dashboard
  // Alternatively, use: router.visit(document.referrer);
}
</script>

<template>
  <div class="mt-8 p-4 border border-gray-200 rounded-xl max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-gray-200 mb-4">Notification Preferences</h2>
    <p class="text-sm text-gray-400 mb-6">
      Manage your email notification settings.
    </p>
    <div class="border-gray shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="mb-4">
        <label class="flex items-center">
          <input
            id="store_reopen_notification"
            v-model="form.store_reopen_notification"
            type="checkbox"
            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5"
          />
          <span class="ml-2 text-gray-500">Notify me when the store reopens</span>
        </label>
      </div>
      <div v-if="form.errors.store_reopen_notification" class="text-red-500 text-sm mt-1">
        {{ form.errors.store_reopen_notification }}
      </div>
    </div>
    <div class="flex items-center justify-between">
      <!-- Back to Dashboard -->
      <button @click="goBack" class="text-sm text-blue-500 hover:text-gray-600">
        Back to Dashboard
      </button>

      <div v-if="form.processing" class="text-blue-500 text-sm">
        Updating preferences...
      </div>
      <div v-else-if="successMessage" class="text-green-500 text-sm">
        {{ successMessage }}
      </div>
      <div v-else-if="errorMessage" class="text-red-500 text-sm">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>
