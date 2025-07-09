<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/JenisWisata.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ConfirmDialog, useConfirm } from 'primevue';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Jenis Wisata',
        href: '/admin/create',
    },
];

const props = defineProps<{
    data?: {
        id_jenis_wisata?: number;
        nama_jenis_wisata: string;
        keterangan: string;
    };
}>();

const page = usePage<SharedData>();
const loading = ref(false);
const confirm = useConfirm();
const toast = useToast();

// Setup form dengan default value dari props.data jika ada (edit mode)
const form = useForm({
    id_jenis_wisata: props.data?.id_jenis_wisata ?? null,
    nama_jenis_wisata: props.data?.nama_jenis_wisata ?? '',
    keterangan: props.data?.keterangan ?? '',
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

    const url = isEdit ? route('admin.jeniswisata.update', props.data.id_jenis_wisata) : route('admin.jeniswisata.store');

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
    <Head title="Tambah Data Jenis Wisata" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">

        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Data Jenis Wisata" description="Tambah/Edit jenis wisata" />

                <form @submit.prevent="submit" class="max-w-md space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Nama Jenis Wisata</Label>
                        <InputText
                            id="nama"
                            v-model="form.nama_jenis_wisata"
                            type="text"
                            class="w-full"
                            :disabled="loading"
                            :invalid="!!form.errors.nama_jenis_wisata"
                            placeholder="Masukkan nama jenis wisata"
                        />
                        <Message v-if="form.errors.nama_jenis_wisata" severity="error" size="small" variant="simple">{{
                            form.errors.nama_jenis_wisata
                        }}</Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="keterangan">Keterangan</Label>
                        <Textarea
                            id="keterangan"
                            v-model="form.keterangan"
                            rows="3"
                            :invalid="!!form.errors.keterangan"
                            class="w-full"
                            :disabled="loading"
                            placeholder="Masukkan keterangan"
                        />
                        <Message v-if="form.errors.keterangan" severity="error" size="small" variant="simple">{{ form.errors.keterangan }}</Message>
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
