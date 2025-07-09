<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-white p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-30 w-30 items-center justify-center rounded-md">
                            <!-- Hapus dark:text-white -->
                            <AppLogoIcon class="size-9 fill-current text-black" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium text-black">{{ title }}</h1>
                        <!-- Ganti warna agar tidak bergantung pada tema -->
                        <p class="text-center text-sm text-gray-600">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

