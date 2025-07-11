<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const page = usePage<
    SharedData & {
        jumlahKriteria: number;
        jumlahSubkriteria: number;
        jumlahLokasiWisata: number;
    }
>();

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];

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
    { immediate: true },
);
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h2 class="mb-6 text-xl font-bold text-gray-700">Statistik Data</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <Transition name="fade" appear>
                    <Card class="border shadow-md transition-all duration-300 hover:-translate-y-2 hover:border-green-600 hover:shadow-xl">
                        <template #title>
                            <div class="flex items-center gap-2 text-green-600">
                                <i class="pi pi-sliders-h text-xl"></i>
                                <span class="text-lg font-semibold">Kriteria</span>
                            </div>
                        </template>
                        <template #content>
                            <p class="text-center text-4xl font-bold text-green-600">{{ page.props.jumlahKriteria }}</p>
                        </template>
                    </Card>
                </Transition>

                <Transition name="fade" appear>
                    <Card class="border shadow-md transition-all duration-300 hover:-translate-y-2 hover:border-blue-600 hover:shadow-xl">
                        <template #title>
                            <div class="flex items-center gap-2 text-blue-600">
                                <i class="pi pi-bars text-xl"></i>
                                <span class="text-lg font-semibold">Subkriteria</span>
                            </div>
                        </template>
                        <template #content>
                            <p class="text-center text-4xl font-bold text-blue-600">{{ page.props.jumlahSubkriteria }}</p>
                        </template>
                    </Card>
                </Transition>

                <Transition name="fade" appear>
                    <Card class="border shadow-md transition-all duration-300 hover:-translate-y-2 hover:border-orange-600 hover:shadow-xl">
                        <template #title>
                            <div class="flex items-center gap-2 text-orange-600">
                                <i class="pi pi-map-marker text-xl"></i>
                                <span class="text-lg font-semibold">Lokasi Wisata</span>
                            </div>
                        </template>
                        <template #content>
                            <p class="text-center text-4xl font-bold text-orange-600">{{ page.props.jumlahLokasiWisata }}</p>
                        </template>
                    </Card>
                </Transition>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.6s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
