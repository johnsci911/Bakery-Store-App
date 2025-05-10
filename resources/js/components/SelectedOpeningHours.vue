<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps<{
  selectedDate: Date;
}>();

const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

const selectedDay = computed(() => daysOfWeek[props.selectedDate.getDay()]);

const storeHours = ref({});

const fetchStoreHours = async () => {
  try {
    const response = await axios.get('/api/v1/store-hours');
    storeHours.value = response.data;
  } catch (error) {
    console.error('Failed to fetch store hours:', error);
  }
};

onMounted(() => {
  fetchStoreHours();
});

const formattedDate = computed(() => {
  return props.selectedDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
});

const displayHours = computed(() => {
  if (Object.keys(storeHours.value).length === 0) {
    return 'Loading...';
  }

  const hours = storeHours.value[selectedDay.value];
  return hours ? hours : 'Closed';
});
</script>

<template>
  <div class="mt-4 bg-gray-900 shadow rounded-lg p-6 flex flex-col justify-items-center">
    <p class="text-lg mb-2 text-gray-600 dark:text-gray-300">{{ formattedDate }}</p>
    <p class="text-xl font-medium text-gray-800 dark:text-white">
      {{ displayHours }}
    </p>
  </div>
</template>
