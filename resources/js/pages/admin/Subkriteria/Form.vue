<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ConfirmDialog, useConfirm } from 'primevue';
import { computed, onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

import Button from 'primevue/button';
import Select from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';

import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [{ title: 'Form Sub Kriteria', href: '/subkriteria/create' }];

const toast = useToast();
const confirm = useConfirm();
const page = usePage<SharedData>();
const loading = ref(false);

// Props (edit mode)
const props = defineProps<{
    data?: {
        id_subkriteria?: number;
        kriteria_id: string;
        nama_subkriteria: string;
        bobot_subkriteria: string;
    };
}>();

const kriteria = ref<any[]>([]);
const jenisWisata = ref<any[]>([]);
const selectedOption = ref(null);

// Opsi checkbox
const fasilitasOptions = ['Spot foto', 'Tempat Makan', 'Toilet', 'Tempat parkir', 'Tempat sampah'];
const transportasiOptions = ['Mobil', 'Motor', 'Perahu'];
const aksesLokasiOptions = ['Akses jalan mulus dan lebar', 'Akses jalan luas', 'Akses jalan sempit', 'Akses jalan berlubang'];

// Form setup
const form = useForm({
    id_subkriteria: props.data?.id_subkriteria ?? null,
    kriteria_id: props.data?.kriteria_id ?? null,
    nama_subkriteria: props.data?.nama_subkriteria ?? '',
    bobot_subkriteria: props.data?.bobot_subkriteria ?? '',
});

// Ambil data kriteria
onMounted(async () => {
    try {
        const [kriteriaRes, jenisRes] = await Promise.all([axios.get('/api/kriteria'), axios.get('/api/jenis-wisata')]);

        kriteria.value = kriteriaRes.data ?? [];
        jenisWisata.value = jenisRes.data ?? [];
    } catch (error: any) {
        const message = error?.response?.data?.message || 'Gagal memuat data';
        toast.error(message, { position: 'top-right' });
    }
});

const setBobot = () => {
    const name = getKriteriaName(form.kriteria_id);
    const val = selectedOption.value;

    if (!val) return;

    // jenis wisata = manual
    if (name === 'Jenis wisata') return;

    const bobot = bobotMap[name]?.[val];

    if (bobot !== undefined) {
        form.bobot_subkriteria = bobot.toString();
    }
};

// Perhitungan bobot otomatis berdasarkan pilihan checkbox
watch(selectedOption, () => {
    setBobot();
});

watch(
    () => [kriteria.value.length, jenisWisata.value.length],
    () => {
        if (!props.data) return;

        const val = props.data.nama_subkriteria?.trim();

        selectedOption.value = val;
        form.nama_subkriteria = val;

        // 🔥 tunggu reactivity selesai baru hitung bobot
        setTimeout(() => {
            setBobot();
        }, 0);
    },
    { immediate: true },
);

// Ambil nama kriteria dari ID
const getKriteriaName = (id: string | number | null) => {
    const item = kriteria.value.find((k: any) => k.id_kriteria === id);
    return item?.nama_kriteria || '';
};

// Tentukan apakah kriteria menggunakan checkbox
const usesRadio = computed(() => {
    const name = getKriteriaName(form.kriteria_id);
    return ['Fasilitas', 'Transportasi', 'Akses lokasi', 'Jenis wisata'].includes(name);
});

const isJenisWisata = computed(() => {
    return getKriteriaName(form.kriteria_id) === 'Jenis wisata';
});

const bobotMap: Record<string, Record<string, number>> = {
    Fasilitas: {
        'Tempat sampah': 1,
        'Tempat parkir': 2,
        Toilet: 3,
        'Tempat Makan': 4,
        'Spot foto': 5,
    },
    Transportasi: {
        Motor: 1,
        Mobil: 3,
        Perahu: 5,
    },
    'Akses lokasi': {
        'Akses jalan berlubang': 1,
        'Akses jalan sempit': 3,
        'Akses jalan luas': 4,
        'Akses jalan mulus dan lebar': 5,
    },
};

watch(
    () => form.kriteria_id,
    () => {
        const name = getKriteriaName(form.kriteria_id);

        // ⛔ jangan reset kalau mode edit awal
        if (!props.data) {
            selectedOption.value = null;
            form.nama_subkriteria = '';
        }

        if (name === 'Jenis wisata') {
            form.bobot_subkriteria = '';
        } else {
            form.bobot_subkriteria = '0';
        }
    },
);

function submit() {
    if (props.data) {
        confirm.require({
            message: 'Apakah Anda yakin ingin mengubah data ini?',
            header: 'Konfirmasi',
            icon: 'pi pi-exclamation-triangle',
            accept: () => doSubmit(true),
            reject: () => toast.info('Batal mengupdate data'),
        });
    } else {
        doSubmit(false);
    }
}

function doSubmit(isEdit: boolean) {
    const url = isEdit ? route('admin.subkriteria.update', props.data?.id_subkriteria) : route('admin.subkriteria.store');

    const method = isEdit ? form.put.bind(form) : form.post.bind(form);

    method(url, {
        onSuccess: () => {
            const msg = page.props.flash?.success;
            if (msg) toast.success(msg);
            if (!isEdit) {
                form.reset();
                selectedOption.value = null;
            }
        },
        onError: () => {
            const msg = page.props.flash?.error;
            if (msg) toast.error(msg);
        },
    });
}

// Isi otomatis nama_subkriteria saat mode radio aktif
watch(selectedOption, (val) => {
    if (usesRadio.value && val) {
        form.nama_subkriteria = val;
    }
});
</script>

<template>
    <Head title="Form Sub Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Sub Kriteria" description="Tambah/Edit Sub Kriteria" />

                <form @submit.prevent="submit()" class="max-w-xl space-y-4">
                    <!-- Kriteria -->
                    <div class="space-y-2">
                        <Label for="kriteria">Kriteria</Label>
                        <Select
                            v-model="form.kriteria_id"
                            :options="kriteria"
                            optionLabel="nama_kriteria"
                            optionValue="id_kriteria"
                            placeholder="Pilih kriteria"
                            class="w-full"
                        />
                        <Message v-if="form.errors.kriteria_id" severity="error" size="small" variant="simple">
                            {{ form.errors.kriteria_id }}
                        </Message>
                    </div>

                    <!-- Nama Sub Kriteria -->
                    <div class="space-y-2">
                        <Label for="nama">Nama Sub Kriteria</Label>
                        <InputText
                            v-model="form.nama_subkriteria"
                            id="nama"
                            class="w-full"
                            placeholder="Contoh: Toilet Bersih"
                            :readonly="usesRadio"
                            :invalid="!!form.errors.nama_subkriteria"
                        />
                        <Message v-if="form.errors.nama_subkriteria" severity="error" size="small" variant="simple">
                            {{ form.errors.nama_subkriteria }}
                        </Message>
                    </div>

                    <!-- Pilihan Checkbox -->
                    <div v-if="usesRadio" class="space-y-2">
                        <Label>Sub Opsi</Label>
                        <div class="flex flex-col gap-2">
                            <template v-if="getKriteriaName(form.kriteria_id) === 'Fasilitas'">
                                <div v-for="item in fasilitasOptions" :key="item" class="flex items-center gap-2">
                                    <RadioButton :inputId="item" :value="item" v-model="selectedOption" />
                                    <label :for="item">{{ item }}</label>
                                </div>
                            </template>

                            <template v-else-if="getKriteriaName(form.kriteria_id) === 'Transportasi'">
                                <div v-for="item in transportasiOptions" :key="item" class="flex items-center gap-2">
                                    <RadioButton :inputId="item" :value="item" v-model="selectedOption" />
                                    <label :for="item">{{ item }}</label>
                                </div>
                            </template>

                            <template v-else-if="getKriteriaName(form.kriteria_id) === 'Akses lokasi'">
                                <div v-for="item in aksesLokasiOptions" :key="item" class="flex items-center gap-2">
                                    <RadioButton :inputId="item" :value="item" v-model="selectedOption" />
                                    <label :for="item">{{ item }}</label>
                                </div>
                            </template>

                            <template v-else-if="getKriteriaName(form.kriteria_id) === 'Jenis wisata'">
                                <div v-for="item in jenisWisata" :key="item.id_jenis_wisata" class="flex items-center gap-2">
                                    <RadioButton :inputId="'jw-' + item.id_jenis_wisata" :value="item.nama_jenis_wisata" v-model="selectedOption" />
                                    <label :for="'jw-' + item.id_jenis_wisata">
                                        {{ item.nama_jenis_wisata }}
                                    </label>
                                </div>
                            </template>

                            <template v-else>
                                <p class="text-sm text-gray-500 italic">Tidak ada opsi tambahan untuk kriteria ini.</p>
                            </template>
                        </div>
                    </div>

                    <!-- Bobot manual jika bukan checkbox -->
                    <div class="space-y-2">
                        <Label for="bobot">Bobot</Label>
                        <InputText
                            v-model="form.bobot_subkriteria"
                            id="bobot"
                            class="w-full"
                            type="number"
                            placeholder="Masukkan bobot"
                            :readonly="usesRadio && !isJenisWisata"
                            :invalid="!!form.errors.bobot_subkriteria"
                        />
                        <Message v-if="form.errors.bobot_subkriteria" severity="error" size="small" variant="simple">
                            {{ form.errors.bobot_subkriteria }}
                        </Message>
                    </div>

                    <!-- Tombol Simpan -->
                    <Button type="submit" label="Simpan" class="w-full" :loading="loading">
                        <template #default>
                            <i v-if="loading" class="pi pi-spinner pi-spin mr-2" />
                            {{ props.data ? 'Update' : 'Simpan' }}
                        </template>
                    </Button>
                </form>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
