<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, Subkriteria, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import axios from 'axios';
import { ConfirmDialog, useConfirm } from 'primevue';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const subkriteria = ref<Subkriteria[]>([]);
const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();
const loading = ref(true);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/subkriteria');
        subkriteria.value = response.data ?? [];
    } catch (error: any) {
        const message = error?.response?.data?.message || 'Gagal memuat data';
        toast.error(message, { position: 'top-right', duration: 3000 });
    } finally {
        loading.value = false;
    }
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Sub Kkriteria',
        href: '/admin/subkriteria',
    },
];

function goToEdit(id: number) {
    router.post(route('admin.subkriteria.edit.init', { id }));
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
        message: 'Apakah Anda yakin ingin menghapus data subkriteria ini?',
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
    router.delete(route('admin.subkriteria.destroy', id), {
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
    <Head title="Sub Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Data sub kriteria" description="Daftar sub kriteria" />

                <Card>
                    <template #content>
                        <DataTable
                            size="small"
                            columnResizeMode="fit"
                            :filters="filters"
                            :value="subkriteria"
                            :loading="loading"
                            paginator
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                            :rows="10"
                            scrollable
                            scrollHeight="500px"
                            :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                            dataKey="id_subkriteria"
                            responsiveLayout="scroll"
                            rowGroupMode="rowspan"
                            groupRowsBy="kriteria.nama_kriteria"
                            sortMode="single"
                            sortField="kriteria.nama_kriteria"
                            :sortOrder="1"
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
                                <span class="flex justify-center">Tidak Ada Subkriteria</span>
                            </template>

                            <Column header="No" headerStyle="width:3rem">
                                <template #body="slotProps">
                                    {{ slotProps.index + 1 }}
                                </template>
                            </Column>

                            <!-- Grup berdasarkan Nama Kriteria -->
                            <Column field="kriteria.nama_kriteria" header="Nama Kriteria" style="min-width: 150px" />

                            <Column field="nama_subkriteria" header="Nama Subkriteria" style="min-width: 150px" />
                            <Column field="bobot_subkriteria" header="Bobot" style="min-width: 150px" />

                            <Column frozen align-frozen="right">
                                <template #body="slotProps">
                                    <div class="flex place-content-center gap-2">
                                        <Button
                                            severity="info"
                                            size="small"
                                            icon="pi pi-pen-to-square"
                                            @click="goToEdit(slotProps.data.id_subkriteria)"
                                        />
                                        <Button
                                            severity="danger"
                                            size="small"
                                            icon="pi pi-trash"
                                            @click="confirmDelete(slotProps.data.id_subkriteria)"
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
