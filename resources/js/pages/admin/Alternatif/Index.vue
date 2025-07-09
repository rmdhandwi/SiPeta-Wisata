<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AdminLayout from '@/layouts/admin/Alternatif.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Alternatif, SharedData, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import axios from 'axios';
import { ConfirmDialog, useConfirm } from 'primevue';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const alternatif = ref<Alternatif[]>([]);
const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();
const loading = ref(true);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/alternatif');
        alternatif.value = response.data ?? [];
        // Flash success jika data berhasil dimuat
        // toast.success('Data berhasil dimuat', { position: 'top-right', duration: 3000 });
    } catch (error: any) {
        // Flash error dari response jika tersedia
        const message = error?.response?.data?.message || 'Gagal memuat data';
        toast.error(message, { position: 'top-right', duration: 3000 });
    } finally {
        loading.value = false;
    }
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Data Alternatif',
        href: '/admin/alternatif',
    },
];

function goToEdit(id: number) {
    router.post(route('admin.alternatif.edit.init', { id }));
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

function confirmDelete(id: number) {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus data alternatif ini?',
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
    router.delete(route('admin.alternatif.destroy', id), {
        onSuccess: () => {
            const message = page.props.flash?.success;
            if (message) {
                toast.success(message, { position: 'top-right', duration: 3000 });
            }
            router.reload();
        },
        onError: () => {
            const message = page.props.flash?.error ?? 'Gagal menghapus data.';
            toast.error(message, { position: 'top-right', duration: 3000 });
        },
        onFinish: () => {
            window.location.reload();
        },
    });
}
</script>

<template>
    <Head title="Alternatif" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Data Alternatif" description="Daftar Alternatif" />

                <Card>
                    <template #content>
                        <DataTable
                            size="small"
                            columnResizeMode="fit"
                            :filters="filters"
                            :value="alternatif"
                            :loading="loading"
                            paginator
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                            :rows="10"
                            scrollable
                            scrollHeight="500px"
                            :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                            rowGroupMode="rowspan"
                            groupRowsBy="lokasi.nama_lokasi_wisata"
                            sortField="lokasi.nama_lokasi_wisata"
                            sortMode="single"
                            :sortOrder="1"
                            dataKey="id_jenis_wisata"
                            responsiveLayout="scroll"
                        >
                            <template #header>
                                <div class="mb-3">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Kata Kunci" class="w-full" />
                                    </IconField>
                                </div>
                            </template>
                            <template #loading>
                                <span class="flex justify-center">Memuat Data...</span>
                            </template>

                            <template #empty>
                                <span class="flex justify-center">Tidak Ada Data Alternatif</span>
                            </template>

                            <Column header="No">
                                <template #body="slotProps">
                                    {{ slotProps.index + 1 }}
                                </template>
                            </Column>
                            <Column field="lokasi.nama_lokasi_wisata" header="Nama Lokasi">
                                <template #body="slotProps">
                                    {{ slotProps.data.lokasi?.nama_lokasi_wisata ?? '-' }}
                                </template>
                            </Column>

                            <Column field="subkriteria.nama_subkriteria" header="Nama Subkriteria">
                                <template #body="slotProps">
                                    {{ slotProps.data.subkriteria?.nama_subkriteria ?? '-' }}
                                </template>
                            </Column>

                            <Column frozen align-frozen="right">
                                <template #body="slotProps">
                                    <div class="flex place-content-center gap-2">
                                        <Button
                                            severity="info"
                                            size="small"
                                            icon="pi pi-pen-to-square"
                                            @click="goToEdit(slotProps.data.lokasi_wisata_id)"
                                        />
                                        <Button
                                            severity="danger"
                                            size="small"
                                            icon="pi pi-trash"
                                            @click="confirmDelete(slotProps.data.lokasi_wisata_id)"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
