<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

interface StoreHours {
  [key: string]: string;
}

const isOpen = ref(false);
const nextOpeningTime = ref('');
const storeHours = ref<StoreHours>({});

const fetchStoreHours = async () => {
  try {
    const response = await axios.get('/api/store-hours');
    storeHours.value = response.data;
  } catch (error) {
    console.error('Failed to fetch store hours:', error);
  }
};

const parseTimeRanges = (timeString: string): [Date, Date][] => {
  return timeString.split(', ').map(range => {
    const [start, end] = range.split(' - ');
    return [
      new Date(`1970/01/01 ${start}`),
      new Date(`1970/01/01 ${end}`)
    ];
  });
};

const checkStoreStatus = () => {
  const now = new Date();
  const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const dayOfWeek = days[now.getDay()];
  const todayHours = storeHours.value[dayOfWeek];

  if (!todayHours) {
    isOpen.value = false;
    findNextOpeningTime(now, days);
    return;
  }

  const timeRanges = parseTimeRanges(todayHours);
  const currentTime = new Date(`1970/01/01 ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}`);

  isOpen.value = timeRanges.some(([start, end]) => currentTime >= start && currentTime < end);

  if (!isOpen.value) {
    findNextOpeningTime(now, days);
  }
};

const findNextOpeningTime = (startDate: Date, days: string[]) => {
  const currentDate = new Date(startDate);
  let daysChecked = 0;

  while (daysChecked < 7) {
    const currentDay = days[currentDate.getDay()];
    const dayHours = storeHours.value[currentDay];

    if (dayHours) {
      const timeRanges = parseTimeRanges(dayHours);
      const currentTime = new Date(`1970/01/01 ${currentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}`);

      for (const [start] of timeRanges) {
        if (daysChecked === 0 && start > currentTime) {
          nextOpeningTime.value = `Today at ${start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;
          return;
        } else if (daysChecked > 0) {
          nextOpeningTime.value = `${currentDay.charAt(0).toUpperCase() + currentDay.slice(1)} at ${start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;
          return;
        }
      }
    }

    currentDate.setDate(currentDate.getDate() + 1);
    currentDate.setHours(0, 0, 0, 0); // Reset time to start of day
    daysChecked++;
  }

  nextOpeningTime.value = 'No upcoming opening times found';
};

const statusText = computed(() => isOpen.value ? 'Open' : 'Closed');

let intervalId: number;

onMounted(async () => {
  await fetchStoreHours();
  checkStoreStatus();
  intervalId = setInterval(checkStoreStatus, 60000);
});

onUnmounted(() => {
  clearInterval(intervalId);
});
</script>

<template>
  <div class="p-4 rounded-xl border text-gray-200" :class="isOpen ? 'border-green-500' : 'border-red-500'">
    <h3 class="text-lg font-semibold mb-2">Store Status</h3>
    <p :class="isOpen ? 'text-green-700' : 'text-red-700'">
      Currently {{ statusText }}
    </p>
    <p v-if="!isOpen" class="text-sm mt-2 text-gray-400">
      Next opening: {{ nextOpeningTime }}
    </p>
  </div>
</template>
