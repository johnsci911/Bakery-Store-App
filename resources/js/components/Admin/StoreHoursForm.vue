<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

type Day = 'monday' | 'wednesday' | 'friday' | 'saturday';

const props = defineProps<{
  storeHours: {
    [key in Day]: string
  }
}>();

const form = useForm<{
  [key in Day]: string
} & { isEveryOtherSaturday: boolean }>({
  monday: props.storeHours.monday || '',
  wednesday: props.storeHours.wednesday || '',
  friday: props.storeHours.friday || '',
  saturday: props.storeHours.saturday || '',
  isEveryOtherSaturday: props.storeHours.saturday?.toLowerCase().includes('every other') || false,
});

watch(() => form.isEveryOtherSaturday, (newValue) => {
  if (newValue) {
    if (!form.saturday.toLowerCase().includes('every other saturday')) {
      form.saturday = `Every other Saturday: ${form.saturday}`;
    }
  } else {
    form.saturday = form.saturday.replace(/every other saturday:\s*/i, '');
  }
});

const submit = () => {
  form.post(route('admin.store-hours.update'), {
    preserveScroll: true,
    onSuccess: () => {
      alert('Store hours updated successfully!');
    },
  });
};
</script>

<template>
  <form @submit.prevent="submit">
    <div v-for="day in ['monday', 'wednesday', 'friday'] as Day[]" :key="day" class="mb-4">
      <label :for="day" class="block mb-2 text-sm font-medium text-gray-700">
        {{ day.charAt(0).toUpperCase() + day.slice(1) }}
      </label>
      <input
        :id="day"
        v-model="form[day]"
        type="text"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        :placeholder="`Enter ${day} hours (e.g., 8:00 AM - 12:00 PM, 12:45 PM - 4:00 PM)`"
      >
    </div>

    <div class="mb-4">
      <label for="saturday" class="block mb-2 text-sm font-medium text-gray-700">Saturday</label>
      <div class="flex items-center mb-2">
        <input
          id="isEveryOtherSaturday"
          v-model="form.isEveryOtherSaturday"
          type="checkbox"
          class="mr-2"
        >
        <label for="isEveryOtherSaturday" class="text-sm text-gray-700">Every other Saturday</label>
      </div>
      <input
        id="saturday"
        v-model="form.saturday"
        type="text"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        placeholder="Enter Saturday hours (e.g., 8:00 AM - 4:00 PM)"
      >
    </div>

    <div class="flex items-center justify-end mt-4">
      <button
        type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        :disabled="form.processing"
      >
        {{ form.processing ? 'Saving...' : 'Save Changes' }}
      </button>
    </div>
  </form>
</template>
