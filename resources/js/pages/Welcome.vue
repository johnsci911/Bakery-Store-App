<script setup lang="ts">
import OpeningHours from '@/components/OpeningHours.vue';
import SelectedOpeningHours from '@/components/SelectedOpeningHours.vue';
import StoreLiveStatus from '@/components/StoreLiveStatus.vue';
import { Head, Link } from '@inertiajs/vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

const selectedDate = ref(new Date());
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col overflow-hidden rounded-lg lg:max-w-4xl">
                <!-- Welcome message -->
                <div class="mx-auto">
                    <h1 class="text-5xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Welcome to <span class="text-blue-500">EuroBreads!</span></h1>
                </div>
                <div class="flex flex-col p-4 border border-green-500 rounded-xl mt-12">
                    <div class="flex flex-row my-4 space-x-4">
                        <div>
                            <OpeningHours :selected-date="selectedDate" />
                        </div>
                        <div class="flex flex-col w-1/2">
                            <h1 class="text-white text-2xl font-bold mb-4 text-center">Select a date to show store hours</h1>
                            <Datepicker v-model="selectedDate" :enable-time-picker="false" :dark="$page.props.darkMode" />
                            <SelectedOpeningHours :selected-date="selectedDate" />
                        </div>
                    </div>
                    <StoreLiveStatus class="mt-20 mb-8 w-2/3 mx-auto" />
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block">
        </div>
    </div>
</template>

<style>
.dp__theme_dark {
    --dp-background-color: #1a1a1a;
    --dp-text-color: #f5f5f5;
    --dp-hover-color: #2f2f2f;
    --dp-hover-text-color: #f5f5f5;
    --dp-hover-icon-color: #f5f5f5;
    --dp-primary-color: #3b82f6;
    --dp-primary-text-color: #f5f5f5;
    --dp-secondary-color: #2f2f2f;
    --dp-border-color: #3f3f3f;
    --dp-menu-border-color: #3f3f3f;
    --dp-border-color-hover: #5f5f5f;
    --dp-disabled-color: #4f4f4f;
    --dp-scroll-bar-background: #2f2f2f;
    --dp-scroll-bar-color: #5f5f5f;
    --dp-success-color: #4caf50;
    --dp-success-color-disabled: #4caf5080;
    --dp-icon-color: #f5f5f5;
    --dp-danger-color: #ff6b6b;
}
</style>
