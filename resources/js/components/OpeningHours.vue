<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface OpeningHours {
  monday: string;
  wednesday: string;
  friday: string;
  saturday: string;
}

const openingHours = ref<OpeningHours>({
  monday: '',
  wednesday: '',
  friday: '',
  saturday: '',
});

const fetchOpeningHours = async () => {
  try {
    const response = await axios.get('/api/v1/store-hours');
    openingHours.value = response.data;
  } catch (error) {
    console.error('Failed to fetch opening hours:', error);
  }
};

onMounted(fetchOpeningHours);
</script>

<template>
  <div class="opening-hours text-gray-200">
    <h3 class="text-lg font-semibold mb-2">Opening Hours</h3>
    <ul>
      <li>Monday: {{ openingHours.monday }}</li>
      <li>Wednesday: {{ openingHours.wednesday }}</li>
      <li>Friday: {{ openingHours.friday }}</li>
      <li>Saturday: {{ openingHours.saturday }}</li>
    </ul>
  </div>
</template>
