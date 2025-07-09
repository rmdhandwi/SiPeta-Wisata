<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();
const toast = useToast();


watch(
  () => page.props.flash.success,
  (message) => {
    if (message) {
      toast.success(message, {
        position: 'top-right',
        duration: 3000,
      });
    }
  },
  { immediate: true }
);
</script>

<template>
    <Head title="Dashboard" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
