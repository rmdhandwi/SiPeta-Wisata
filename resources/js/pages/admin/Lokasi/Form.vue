div
<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import LeafletMap from '@/components/LeafletMap.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/LokasiWisata.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ConfirmDialog, Select, useConfirm } from 'primevue';
import { computed, onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Lokasi Wisata',
        href: '/admin/create',
    },
];

const page = usePage<SharedData>();
const loading = ref(false);
const confirm = useConfirm();
const toast = useToast();

const jenisWisata = ref([]);

const props = defineProps<{
    data?: Record<string, any>;
    fields: string[]; // dari controller Laravel
}>();

onMounted(async () => {
    try {
        const response = await axios.get('/api/jenis-wisata');
        jenisWisata.value = response.data ?? [];
    } catch (error: any) {
        toast.error(error?.response?.data?.message || 'Gagal memuat data', {
            position: 'top-right',
        });
    } finally {
        loading.value = false;
    }
});

const defaultForm = Object.fromEntries(props.fields.map((key) => [key, props.data?.[key] ?? '']));

const form = useForm(defaultForm);

// Submit function
function submit() {
    if (props.data?.id_lokasi_wisata) {
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
        });
    } else {
        doSubmit();
    }
}

function doSubmit() {
    const isEdit = !!props.data?.id_lokasi_wisata;
    const url = isEdit ? route('admin.lokasiwisata.update', props.data.id_lokasi_wisata) : route('admin.lokasiwisata.store');

    const callback = {
        onSuccess: () => {
            toast.success(page.props.flash?.success || 'Berhasil', {
                position: 'top-right',
            });
            if (!isEdit) form.reset();
        },
        onError: () => {
            toast.error(page.props.flash?.error || 'Gagal Menyimpan', {
                position: 'top-right',
            });
        },
    };

    isEdit ? form.put(url, callback) : form.post(url, callback);
}

const visibleFields = computed(() => props.fields.filter((f) => !['id_lokasi_wisata', 'latitude', 'longitude'].includes(f)));

function formatLabel(field: string): string {
  return field
    .replace(/_/g, ' ')
    .replace(/\b\w/g, (char) => char.toUpperCase()); // huruf depan setiap kata kapital
}
</script>

<template>
    <Head title="Tambah Lokasi" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Data Lokasi Wisata" description="Tambah/Edit lokasi wisata" />

                <Fluid>
                    <form @submit.prevent="submit" class="grid grid-cols-2 gap-6">
                        <div v-for="(field, idx) in visibleFields" :key="field" class="space-y-2">
                            <Label :for="field">{{ formatLabel(field) }}</Label>

                            <Select
                                v-if="field === 'jenis_wisata_id'"
                                v-model="form[field]"
                                :options="jenisWisata"
                                optionLabel="nama_jenis_wisata"
                                optionValue="id_jenis_wisata"
                                :invalid="!!form.errors[field]"
                                class="w-full"
                                placeholder="Pilih Jenis Wisata"
                            />
                            <InputText
                                v-else
                                v-model="form[field]"
                                :id="field"
                                :invalid="!!form.errors[field]"
                                class="w-full"
                                :placeholder="`Masukkan ${field.replace(/_/g, ' ')}`"
                            />
                            <Message v-if="form.errors[field]" severity="error" size="small" variant="simple">
                                {{ form.errors[field] }}
                            </Message>
                        </div>

                        <!-- Latitude dan Longitude -->
                        <div class="col-span-full grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="longitude">Longitude</Label>
                                <InputText
                                    v-model="form.longitude"
                                    type="number"
                                    class="w-full"
                                    :invalid="!!form.errors.longitude"
                                    placeholder="Contoh: 140.703"
                                />
                                <Message v-if="form.errors.longitude" severity="error" size="small" variant="simple">
                                    {{ form.errors.longitude }}
                                </Message>
                            </div>

                            <div class="space-y-2">
                                <Label for="latitude">Latitude</Label>
                                <InputText
                                    v-model="form.latitude"
                                    type="number"
                                    class="w-full"
                                    :invalid="!!form.errors.latitude"
                                    placeholder="Contoh: -2.533"
                                />
                                <Message v-if="form.errors.latitude" severity="error" size="small" variant="simple">
                                    {{ form.errors.latitude }}
                                </Message>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <span class="text-xs">(Silakan pilih titik lokasi tanpa mengisi Longitude & Latitude)</span>
                            <LeafletMap
                                :lat="parseFloat(form.latitude) || -2.533"
                                :lng="parseFloat(form.longitude) || 140.703"
                                @update:latlng="
                                    ({ lat, lng }) => {
                                        form.latitude = lat.toFixed(6);
                                        form.longitude = lng.toFixed(6);
                                    }
                                "
                            />
                        </div>

                        <!-- Tombol Simpan -->
                        <Button type="submit" label="Simpan" class="col-span-full w-full" :loading="loading">
                            <template #default>
                                <i v-if="loading" class="pi pi-spinner pi-spin mr-2"></i>
                                {{ props.data ? 'Update' : 'Simpan' }}
                            </template>
                        </Button>
                    </form>
                </Fluid>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
