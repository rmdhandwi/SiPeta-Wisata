<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import LeafletMap from '@/components/LeafletMap.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/LokasiWisata.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { LokasiWisataItem, SharedData, type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import axios from 'axios';
import { ConfirmDialog, useConfirm } from 'primevue';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const lokasiWisata = ref<LokasiWisataItem[]>([]);
const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();
const loading = ref(true);
const visible = ref(false);
const kolom = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const form = useForm({
    nama_colom: '',
});

const selectedLocation = ref({ lat: -2.533, lng: 140.703, nama: '' });

function showMap(data: LokasiWisataItem) {
    selectedLocation.value = {
        lat: data.latitude || -2.533,
        lng: data.longitude || 140.703,
        nama: data.nama_lokasi_wisata,
    };
    visible.value = true;
}

function formatHeader(col: string): string {
    return col.replace(/_/g, ' ').replace(/\w\S*/g, (txt) => txt.charAt(0).toUpperCase() + txt.substring(1));
}

const columns = ref<string[]>([]);

onMounted(async () => {
    loading.value = true;
    try {
        const { data } = await axios.get('/api/lokasi-wisata');
        lokasiWisata.value = Array.isArray(data) ? data : [];

        if (lokasiWisata.value.length > 0) {
            // Kolom yang ingin ditampilkan saja
            const hiddenFields = ['id_lokasi_wisata', 'jenis_wisata_id', 'jenis_wisata', 'longitude', 'latitude'];

            columns.value = Object.keys(lokasiWisata.value[0]).filter(
                (key) => typeof lokasiWisata.value[0][key] !== 'object' && !hiddenFields.includes(key),
            );
        }
    } catch (error: any) {
        const message = error?.response?.data?.message || 'Gagal memuat data lokasi wisata.';
        toast.error(message, { position: 'top-right', duration: 3000 });
    } finally {
        loading.value = false;
    }
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Lokasi Wisata',
        href: '/admin/lokasiwisata',
    },
];

function goToEdit(id: number) {
    router.post(route('admin.lokasiwisata.edit.init', { id }));
}

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            toast.error(message, { position: 'top-right', duration: 3000 });
        }
    },
    { immediate: true },
);

// Submit function
function submit() {
    const url = route('admin.lokasiwisata.colom');
    form.post(url, {
        onSuccess: () => {
            const successMessage = page.props.flash?.success;
            if (successMessage) {
                toast.success(successMessage, {
                    position: 'top-right',
                    duration: 3000,
                });
                form.reset();
                kolom.value = false;
                window.location.reload();
            }
        },
        onError: () => {
            const errorMessage = page.props.flash?.error;
            if (errorMessage) {
                toast.error(errorMessage, {
                    position: 'top-right',
                    duration: 3000,
                });
                kolom.value = true;
            }
        },
    });
}

function confirmDelete(id: number) {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus data lokasi wisata ini?',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Ya',
        },
        accept: () => doDestroy(id),
        reject: () => {
            toast.info('Batal menghapus data', { position: 'top-right' });
        },
    });
}

function doDestroy(id: number) {
    router.delete(route('admin.lokasiwisata.destroy', id), {
        onSuccess: () => {
            const message = page.props.flash?.success;
            if (message) {
                toast.success(message, { position: 'top-right' });
            }
        },
        onError: () => {
            const message = page.props.flash?.error ?? 'Gagal menghapus data.';
            toast.error(message, { position: 'top-right' });
        },
        onFinish: () => {
            window.location.reload();
        },
    });
}
</script>

<template>
    <Head title="Lokasi Wisata" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Data Lokasi Wisata" description="Daftar Lokasi wisata" />

                <Card>
                    <template #content>
                        <DataTable
                            size="small"
                            columnResizeMode="fit"
                            :filters="filters"
                            :value="lokasiWisata"
                            :loading="loading"
                            paginator
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                            :rows="10"
                            scrollable
                            scrollHeight="500px"
                            rowGroupMode="rowspan"
                            groupRowsBy="nama_jenis_wisata"
                            sortField="nama_jenis_wisata"
                            sortMode="single"
                            :sortOrder="1"
                            :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                            dataKey="id_lokasi_wisata"
                            responsiveLayout="scroll"
                        >
                            <template #header>
                                <div class="mb-3 flex justify-between">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Kata Kunci" class="w-auto" />
                                    </IconField>

                                    <Button severity="info" size="small" icon="pi pi-plus" label="Kolom" @click="kolom = true" />
                                </div>
                            </template>
                            <template #loading>
                                <span class="flex justify-center">Memuat Data...</span>
                            </template>

                            <template #empty>
                                <span class="flex justify-center">Tidak Ada Lokasi Wisata</span>
                            </template>

                            <Column header="No">
                                <template #body="slotProps">
                                    {{ slotProps.index + 1 }}
                                </template>
                            </Column>
                            <Column v-for="col in columns" :key="col" :field="col" :header="formatHeader(col)">
                                <template #body="slotProps">
                                    {{ slotProps.data[col] }}
                                </template>
                            </Column>
                            <Column frozen align-frozen="right">
                                <template #body="slotProps">
                                    <div class="flex place-content-center gap-2">
                                        <Button
                                            severity="warn"
                                            size="small"
                                            icon="pi pi-pen-to-square"
                                            @click="goToEdit(slotProps.data.id_lokasi_wisata)"
                                        />
                                        <Button
                                            severity="danger"
                                            size="small"
                                            icon="pi pi-trash"
                                            @click="confirmDelete(slotProps.data.id_lokasi_wisata)"
                                        />
                                        <Button
                                            v-if="Number(slotProps.data.longitude) && Number(slotProps.data.latitude)"
                                            severity="info"
                                            size="small"
                                            icon="pi pi-map"
                                            @click="() => showMap(slotProps.data)"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
        </AdminLayout>
        <Dialog v-model:visible="kolom" header="Tambah Kolom Lokasi Wisata" :style="{ width: '25rem' }" modal>
            <form @submit.prevent="submit">
                <div class="mb-4 items-center space-y-2">
                    <Label for="nama_colom" class="font-semibold">Nama Kolom Baru</Label>
                    <InputText v-model="form.nama_colom" class="flex-auto" fluid autocomplete="off" :invalid="!!form.errors.nama_colom" />
                    <Message v-if="form.errors.nama_colom" severity="error" size="small" variant="simple">
                        {{ form.errors.nama_colom }}
                    </Message>
                </div>
                <div class="flex justify-between gap-2">
                    <Button type="button" label="Batal" severity="secondary" @click="kolom = false" />
                    <Button label="Simpan" type="submit" />
                </div>
            </form>
        </Dialog>
        <Dialog
            v-model:visible="visible"
            maximizable
            modal
            :header="'Lokasi Wisata ' + selectedLocation.nama"
            :style="{ width: '50vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        >
            <div>
                <LeafletMap :lat="selectedLocation.lat" :lng="selectedLocation.lng" />
            </div>
        </Dialog>
    </AppLayout>
</template>
