<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import TopsisLayout from '@/layouts/admin/Topsis.vue';
import { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Column, DataTable } from 'primevue';
import { useToast } from 'vue-toast-notification';

const page = usePage<SharedData>();
const toast = useToast();

const props = defineProps<{
    matrixKeputusan: any[];
    normalisasi: any[];
    bobotMatriks: any[];
    solusiIdeal: {
        positif: Record<string, number>;
        negatif: Record<string, number>;
    };
    jarak: {
        positif: Record<string, number>;
        negatif: Record<string, number>;
    };
    preferensi: Record<number, number>;
    peringkat: { id: number; nilai: number; rank: number }[];
}>();

const breadcrumbItems = [
    {
        title: 'Perhitungan TOPSIS',
        href: '/admin/topsis',
    },
];

function formatHeader(col: string): string {
    return col.charAt(0).toUpperCase() + col.slice(1);
}
</script>

<template>
    <Head title="TOPSIS" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <TopsisLayout>
            <div class="space-y-6">
                <HeadingSmall title="Perhitungan TOPSIS" description="Berikut adalah hasil dari setiap tahap perhitungan metode TOPSIS." />

                <!-- Matriks Keputusan -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="props.matrixKeputusan">
                            <template #header>
                                <h3 class="mb-2 text-lg font-semibold">Matriks Keputusan</h3>
                            </template>
                            <Column field="alternatif" header="Alternatif" />
                            <template v-for="(val, key) in props.matrixKeputusan[0] ?? {}">
                                <Column
                                    v-if="String(key) !== 'alternatif'"
                                    :key="String(key)"
                                    :field="String(key)"
                                    :header="formatHeader(String(key))"
                                />
                            </template>
                        </DataTable>
                    </template>
                </Card>

                <!-- Normalisasi -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="props.normalisasi">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Normalisasi</h3>
                            </template>
                            <Column field="alternatif" header="Alternatif" />
                            <template v-for="(val, key) in props.normalisasi[0] ?? {}">
                                <Column
                                    v-if="String(key) !== 'alternatif'"
                                    :key="String(key)"
                                    :field="String(key)"
                                    :header="formatHeader(String(key))"
                                />
                            </template>
                        </DataTable>
                    </template>
                </Card>

                <!-- Pembobotan -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="props.bobotMatriks">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Matriks Bobot Ternormalisasi</h3>
                            </template>
                            <Column field="alternatif" header="Alternatif" />
                            <template v-for="(val, key) in props.bobotMatriks?.[0] ?? {}">
                                <Column
                                    v-if="String(key) !== 'alternatif'"
                                    :key="String(key)"
                                    :field="String(key)"
                                    :header="formatHeader(String(key))"
                                />
                            </template>
                        </DataTable>
                    </template>
                </Card>

                <!-- Solusi Ideal -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="[props.solusiIdeal.positif]">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Solusi Ideal Positif (A+)</h3>
                            </template>
                            <Column v-for="(val, key) in props.solusiIdeal.positif" :key="key" :field="key" :header="formatHeader(key)" />
                        </DataTable>

                        <DataTable showGridlines :value="[props.solusiIdeal.negatif]">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Solusi Ideal Negatif (A-)</h3>
                            </template>
                            <Column v-for="(val, key) in props.solusiIdeal.negatif" :key="key" :field="key" :header="formatHeader(key)" />
                        </DataTable>
                    </template>
                </Card>

                <!-- Jarak ke Solusi Ideal -->
                <Card>
                    <template #content>
                        <DataTable
                            showGridlines
                            :value="
                                Object.keys(props.jarak?.positif ?? {}).map((key) => ({
                                    alternatif: key,
                                    positif: props.jarak?.positif?.[key] ?? 0,
                                    negatif: props.jarak?.negatif?.[key] ?? 0,
                                }))
                            "
                        >
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Jarak ke Solusi Ideal</h3>
                            </template>
                            <Column field="alternatif" header="Alternatif" />
                            <Column field="positif" header="Jarak +" />
                            <Column field="negatif" header="Jarak -" />
                        </DataTable>
                    </template>
                </Card>

                <!-- Nilai Preferensi -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="Object.entries(props.preferensi).map(([key, val]) => ({ alternatif: key, nilai: val }))">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Nilai Preferensi</h3>
                            </template>
                            <Column field="alternatif" header="Alternatif" />
                            <Column field="nilai" header="Nilai Preferensi" />
                        </DataTable>
                    </template>
                </Card>

                <!-- Peringkat -->
                <Card>
                    <template #content>
                        <DataTable showGridlines :value="props.peringkat">
                            <template #header>
                                <h3 class="mt-4 mb-2 text-lg font-semibold">Peringkat</h3>
                            </template>
                            <Column field="id" header="Alternatif" />
                            <Column field="nilai" header="Nilai" />
                            <Column field="rank" header="Peringkat" />
                        </DataTable>
                    </template>
                </Card>
            </div>
        </TopsisLayout>
    </AppLayout>
</template>
