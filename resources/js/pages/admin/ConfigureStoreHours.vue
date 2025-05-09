<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StoreHoursForm from '@/components/Admin/StoreHoursForm.vue';

defineProps<{
  storeHours: {
    monday: string;
    wednesday: string;
    friday: string;
    saturday: string;
  }
}>();
</script>

<template>
  <AppLayout>
    <Head title="Configure Store Hours" />

    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-100">
        Configure Store Hours
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-slate-600 shadow-sm sm:rounded-lg">
          <div class="p-6 bg-slate-900 border-b border-gray-200">
            <StoreHoursForm :store-hours="storeHours" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
