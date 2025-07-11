<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { Select } from 'primevue';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    lokasi: Array<{
        nama: string;
        jenis: string;
        latitude: number;
        longitude: number;
        fasilitas: string;
        transportasi: string;
        keamanan: string;
        akses_lokasi: string;
        rank?: number;
        preferensi?: number;
    }>;
}>();

const mapId = 'leaflet-map-pemetaan';
let map: L.Map | null = null;
let markers: L.Marker[] = [];

const selectedJenis = ref('');
const jenisUnik = computed(() => [
    { label: 'Semua', value: '' },
    ...[...new Set(props.lokasi.map((l) => l.jenis))].map((j) => ({
        label: j,
        value: j,
    })),
]);

// Penentuan warna ikon
const iconColor = (jenis: string): string => {
    switch (jenis.toLowerCase()) {
        case 'wisata alam':
            return 'green';
        case 'wisata sejarah':
            return 'blue';
        case 'wisata pantai':
            return 'orange';
        default:
            return 'gray';
    }
};

// Buat ikon Leaflet dari folder public/icon
const createIcon = (color: string): L.Icon => {
    return L.icon({
        iconUrl: `/icon/marker-icon-${color}.png`,
        shadowUrl: '/icon/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    });
};

// Tambah marker ke peta
const updateMarkers = () => {
    if (!map) return;
    markers.forEach((m) => m.remove());
    markers = [];

    const lokasiFiltered = props.lokasi.filter((l) => !selectedJenis.value || l.jenis === selectedJenis.value);

    lokasiFiltered.forEach((lokasi) => {
        const lat = Number(lokasi.latitude);
        const lng = Number(lokasi.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: createIcon(iconColor(lokasi.jenis)),
        }).addTo(map!);

        const popup = `
        <div style="font-size: 14px;">
            <strong style="font-size: 16px;">${lokasi.nama}</strong>
            <table style="margin-top: 5px;">
            ${lokasi.rank !== undefined ? `<tr><td><b>Ranking</b></td><td>: ${lokasi.rank}</td></tr>` : ''}
            ${lokasi.preferensi !== undefined ? `<tr><td><b>Preferensi</b></td><td>: ${lokasi.preferensi}</td></tr>` : ''}
                <tr><td><b>Jenis</b></td><td>: ${lokasi.jenis}</td></tr>
                <tr><td><b>Fasilitas</b></td><td>: ${lokasi.fasilitas || '-'}</td></tr>
                <tr><td><b>Keamanan</b></td><td>: ${lokasi.keamanan || '-'}</td></tr>
                <tr><td><b>Akses</b></td><td>: ${lokasi.akses_lokasi || '-'}</td></tr>
                <tr><td><b>Transportasi</b></td><td>: ${lokasi.transportasi || '-'}</td></tr>
            </table>
        </div>
    `;
        marker.bindPopup(popup);
        markers.push(marker);
    });
};

// Inisialisasi peta
const initMap = async () => {
    await nextTick();
    const el = document.getElementById(mapId);
    if (!el) return;

    if (map) map.remove();

    map = L.map(mapId).setView([-2.533, 140.703], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    updateMarkers();
};

onMounted(() => initMap());
watch(selectedJenis, updateMarkers);
watch(() => props.lokasi, updateMarkers);
</script>

<template>
    <Head title="Pemetaan" />

    <AppLayout :breadcrumbs="[{ title: 'Pemetaan', href: '/admin/pemetaan' }]">
        <div class="flex flex-col gap-4 p-4">
            <HeadingSmall title="Pemetaan Lokasi Wisata" description="Visualisasi lokasi berdasarkan jenis wisata." />

            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <label class="font-medium">Filter Jenis Wisata:</label>
                    <Select
                        v-model="selectedJenis"
                        :options="jenisUnik"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Pilih Jenis Wisata"
                        class="w-64"
                    />
                </div>

                <div :id="mapId" class="h-[730px] w-full rounded shadow" />
            </div>
        </div>
    </AppLayout>
</template>
