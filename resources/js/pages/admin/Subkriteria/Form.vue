<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ConfirmDialog, Select, useConfirm } from 'primevue';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Sub Kriteria',
        href: '/subkriteria/create',
    },
];

const props = defineProps<{
    data?: {
        id_subkriteria?: number;
        kriteria_id: string;
        nama_subkriteria: string;
        bobot_subkriteria: string;
    };
}>();

const kriteria = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/kriteria');
        kriteria.value = response.data ?? [];
    } catch (error: any) {
        // Flash error dari response jika tersedia
        const message = error?.response?.data?.message || 'Gagal memuat data';
        toast.error(message, { position: 'top-right', duration: 3000 });
    } finally {
        loading.value = false;
    }
});

const page = usePage<SharedData>();
const loading = ref(false);
const confirm = useConfirm();
const toast = useToast();

// Setup form dengan default value dari props.data jika ada (edit mode)
const form = useForm({
    id_subkriteria: props.data?.id_subkriteria ?? null,
    kriteria_id: props.data?.kriteria_id ?? null,
    nama_subkriteria: props.data?.nama_subkriteria ?? '',
    bobot_subkriteria: props.data?.bobot_subkriteria ?? '',
});

// Submit function
function submit() {
    if (props.data) {
        // Konfirmasi sebelum edit
        confirm.require({
            message: 'Apakah Anda yakin ingin mengubah data ini?',
            header: 'Konfirmasi',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Batal',
                severity: 'secondary',
                outlined: true,
            },
            acceptProps: {
                label: 'Ya',
                severity: 'primary',
            },
            accept: () => doSubmit(),
            reject: () => {
                toast.info('Batal mengupdate data', { position: 'top-right' });
            },
        });
    } else {
        // Langsung submit saat tambah
        doSubmit();
    }
}

function doSubmit() {
    const isEdit = !!props.data;

    const url = isEdit ? route('admin.subkriteria.update', props.data.id_subkriteria) : route('admin.subkriteria.store');

    if (isEdit) {
        form.put(url, {
            onSuccess: () => {
                const successMessage = page.props.flash?.success;
                if (successMessage) {
                    toast.success(successMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
            onError: () => {
                const errorMessage = page.props.flash?.error;
                if (errorMessage) {
                    toast.error(errorMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
        });
    } else {
        form.post(url, {
            onSuccess: () => {
                const successMessage = page.props.flash?.success;
                if (successMessage) {
                    toast.success(successMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                    form.reset();
                }
            },
            onError: () => {
                const errorMessage = page.props.flash?.error;
                if (errorMessage) {
                    toast.error(errorMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
        });
    }
}
</script>

<template>
    <Head title="Tambah Data Sub Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Data Sub Kriteria" description="Tambah/Edit Sub Kriteria" />

                <form @submit.prevent="submit" class="max-w-md space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Kriteria</Label>
                        <Select
                            v-model="form.kriteria_id"
                            :options="kriteria"
                            optionLabel="nama_kriteria"
                            optionValue="id_kriteria"
                            :tabindex="1"
                            :invalid="!!form.errors.kriteria_id"
                            placeholder="Pilih kriteria"
                            class="w-full"
                        />
                        <Message v-if="form.errors.kriteria_id" severity="error" size="small" variant="simple">
                            {{ form.errors.kriteria_id }}
                        </Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="nama">Nama Sub Kriteria</Label>
                        <InputText
                            id="nama"
                            v-model="form.nama_subkriteria"
                            type="text"
                            class="w-full"
                            :disabled="loading"
                            :invalid="!!form.errors.nama_subkriteria"
                            placeholder="Masukkan nama sub kriteria"
                        />
                        <Message v-if="form.errors.nama_subkriteria" severity="error" size="small" variant="simple">{{
                            form.errors.nama_subkriteria
                        }}</Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="bobot_subkriteria">Bobot</Label>
                        <InputText
                            id="bobot_subkriteria"
                            v-model="form.bobot_subkriteria"
                            rows="3"
                            :invalid="!!form.errors.bobot_subkriteria"
                            class="w-full"
                            :disabled="loading"
                            placeholder="Masukkan bobot sub kriteria"
                        />
                        <Message v-if="form.errors.bobot_subkriteria" severity="error" size="small" variant="simple">{{
                            form.errors.bobot_subkriteria
                        }}</Message>
                    </div>

                    <Button type="submit" label="Simpan" class="w-full" :loading="loading">
                        <template #default>
                            <i v-if="loading" class="pi pi-spinner pi-spin mr-2"></i>
                            {{ props.data ? 'Update' : 'Simpan' }}
                        </template>
                    </Button>
                </form>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
