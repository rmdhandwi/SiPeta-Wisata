<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import TopsisLayout from '@/layouts/admin/Topsis.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Column, DataTable } from 'primevue';

const page = usePage<SharedData>();
// Props dari controller
const props = defineProps<{ hasil: any[] }>();

const breadcrumbItems = [
    {
        title: 'Hasil Potensi Wisata',
        href: '/admin/potensi',
    },
];
</script>

<template>
    <Head title="TOPSIS" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <TopsisLayout>
            <div class="space-y-6">
                <HeadingSmall title="Potensi Lokasi Wisata" description="Hasil Potensi Wisata Berdasarkan TOPSIS" />
                <Card>
                    <template #content>
                        <DataTable :value="props.hasil" showGridlines>
                            <template #header>
                                <h2 class="text-lg font-semibold">Peringkat Lokasi Wisata</h2>
                            </template>
                            <Column field="rangking" header="Rangking" />
                            <Column field="alternatif" header="Nama Lokasi Wisata" />
                            <Column field="jarak_positif" header="Jarak + (A+)" />
                            <Column field="jarak_negatif" header="Jarak - (A-)" />
                            <Column field="preferensi" header="Nilai Preferensi" />
                        </DataTable>
                    </template>
                </Card>
            </div>
        </TopsisLayout>
    </AppLayout>
</template>

<style scoped>
.text-right {
    text-align: right;
}
</style>
