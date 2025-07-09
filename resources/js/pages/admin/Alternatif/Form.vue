<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AlternatifLayout from '@/layouts/admin/Alternatif.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { LoaderCircle } from 'lucide-vue-next';
import { ConfirmDialog, Select, useConfirm } from 'primevue';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';

interface LokasiWisata {
    id_lokasi_wisata: number;
    nama_lokasi_wisata: string;
}

interface Kriteria {
    id_kriteria: number;
    nama_kriteria: string;
}

interface Subkriteria {
    id_subkriteria: number;
    kriteria_id: number;
    nama_subkriteria: string;
}

type AlternatifItem = {
    lokasi_wisata_id: number | null;
    subkriteria: { [kriteria_id: string]: number };
    alternatif_ids?: number[];
};

interface FormAlternatif extends Record<string, any> {
    alternatif: AlternatifItem[];
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Alternatif',
        href: '/alternatif/create',
    },
];

const page = usePage<SharedData>();
const loading = ref(false);
const confirm = useConfirm();
const toast = useToast();

const props = defineProps<{
    data?: {
        alternatif: AlternatifItem[];
    };
}>();

const lokasiWisata = ref<LokasiWisata[]>([]);
const kriteria = ref<Kriteria[]>([]);
const subkriteria = ref<Subkriteria[]>([]);

const form = useForm<FormAlternatif>({
    alternatif: props.data?.alternatif ?? [
        {
            lokasi_wisata_id: null,
            subkriteria: {},
            alternatif_ids: [],
        },
    ],
});

onMounted(async () => {
    try {
        const [resLokasi, resKriteria, resSub] = await Promise.all([
            axios.get('/api/lokasi-wisata'),
            axios.get('/api/kriteria'),
            axios.get('/api/subkriteria'),
        ]);

        lokasiWisata.value = resLokasi.data;
        kriteria.value = resKriteria.data;
        subkriteria.value = resSub.data;

        form.alternatif = form.alternatif.map((item) => ({
            ...item,
            lokasi_wisata_id: item.lokasi_wisata_id ? Number(item.lokasi_wisata_id) : null,
            alternatif_ids: Array.isArray(item.alternatif_ids) ? item.alternatif_ids : Object.values(item.alternatif_ids ?? {}),
        }));
    } catch (err: any) {
        toast.error('Gagal memuat data', { position: 'top-right' });
    }
});

function availableLokasi(currentIdx: number): LokasiWisata[] {
    const selected = form.alternatif.map((a, idx) => idx !== currentIdx && a.lokasi_wisata_id).filter(Boolean) as number[];

    return lokasiWisata.value.filter(
        (lw) => !selected.includes(lw.id_lokasi_wisata) || lw.id_lokasi_wisata === form.alternatif[currentIdx].lokasi_wisata_id,
    );
}

function addAlternatif() {
    form.alternatif.push({ lokasi_wisata_id: null, subkriteria: {} });
}

function removeAlternatif(index: number) {
    if (form.alternatif.length === 1) {
        toast.warning('Minimal satu data alternatif diperlukan', { position: 'top-right' });
        return;
    }
    form.alternatif.splice(index, 1);
}

function submit() {
    if (props.data) {
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
        doSubmit();
    }
}

function doSubmit() {
    const isEdit = !!props.data;
    const id = props.data?.alternatif[0]?.lokasi_wisata_id;

    const url = isEdit ? route('admin.alternatif.update', { lokasi_wisata_id: id }) : route('admin.alternatif.store');

    if (isEdit && props.data?.alternatif[0]?.alternatif_ids) {
        form.alternatif[0].alternatif_ids = props.data.alternatif[0].alternatif_ids;
    }

    const callback = {
        onSuccess: () => {
            toast.success(page.props.flash?.success || 'Berhasil', { position: 'top-right' });
            if (!isEdit) form.reset();
        },
        onError: () => {
            toast.error(page.props.flash?.error || 'Gagal Menyimpan', { position: 'top-right' });
        },
    };

    isEdit ? form.put(url, callback) : form.post(url, callback);
}
</script>

<template>
    <Head :title="props.data ? 'Edit Alternatif' : 'Form Alternatif'" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AlternatifLayout>
            <div class="space-y-6">
                <HeadingSmall
                    :title="props.data ? 'Edit Data Alternatif' : 'Form Data Alternatif'"
                    description="Input nilai untuk setiap subkriteria terhadap lokasi wisata."
                />

                <Button v-if="!props.data" type="button" label="Tambah Alternatif" icon="pi pi-plus" @click="addAlternatif" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div v-for="(alt, idx) in form.alternatif" :key="idx" class="relative space-y-4 rounded-lg border p-4">
                            <div class="flex items-center justify-end">
                                <Button
                                    v-if="!props.data"
                                    icon="pi pi-times"
                                    class="absolute top-2 right-2"
                                    severity="danger"
                                    size="small"
                                    variant="text"
                                    @click="removeAlternatif(idx)"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label :for="`lokasi_${idx}`">Lokasi Wisata</Label>
                                <Select
                                    v-model="form.alternatif[idx].lokasi_wisata_id"
                                    :options="availableLokasi(idx)"
                                    optionLabel="nama_lokasi_wisata"
                                    optionValue="id_lokasi_wisata"
                                    placeholder="Pilih Lokasi Wisata"
                                    :disabled="!!props.data"
                                    :invalid="!!form.errors[`alternatif.${idx}.lokasi_wisata_id`]"
                                    class="w-full"
                                />
                                <Message v-if="form.errors[`alternatif.${idx}.lokasi_wisata_id`]" severity="error" size="small" variant="simple">
                                    {{ form.errors[`alternatif.${idx}.lokasi_wisata_id`] }}
                                </Message>
                            </div>

                            <div v-for="k in kriteria" :key="k.id_kriteria" class="space-y-2">
                                <Label :for="`kriteria_${k.id_kriteria}_${idx}`">{{ k.nama_kriteria }}</Label>
                                <Select
                                    v-model="form.alternatif[idx].subkriteria[k.id_kriteria]"
                                    :options="subkriteria.filter((s) => s.kriteria_id === k.id_kriteria)"
                                    optionLabel="nama_subkriteria"
                                    optionValue="id_subkriteria"
                                    :placeholder="`Pilih subkriteria ${k.nama_kriteria}`"
                                    :invalid="!!form.errors[`alternatif.${idx}.subkriteria.${k.id_kriteria}`]"
                                    class="w-full"
                                />
                                <Message
                                    v-if="form.errors[`alternatif.${idx}.subkriteria.${k.id_kriteria}`]"
                                    severity="error"
                                    size="small"
                                    variant="simple"
                                >
                                    {{ form.errors[`alternatif.${idx}.subkriteria.${k.id_kriteria}`] }}
                                </Message>
                            </div>
                            <Button type="submit" v-if="props.data" class="w-full" :disabled="form.processing">
                                <template #default>
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                    Update Data
                                </template>
                            </Button>
                        </div>
                    </div>

                    <Button type="submit" v-if="!props.data" class="w-full" :disabled="form.processing">
                        <template #default>
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Simpan Data
                        </template>
                    </Button>
                </form>
            </div>
        </AlternatifLayout>
    </AppLayout>
</template>
