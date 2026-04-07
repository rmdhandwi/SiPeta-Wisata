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

// Ambil semua jenis unik dan buat pilihan filter
const jenisUnik = computed(() => [
    { label: 'Semua', value: '' },
    ...[...new Set(props.lokasi.map((l) => l.jenis))].map((j) => ({
        label: j,
        value: j,
    })),
]);

// Warna dinamis berdasarkan indeks jenis wisata
const generateColorByIndex = (index: number): string => {
    const hue = (index * 47) % 360;
    return `hsl(${hue}, 70%, 50%)`;
};

// Peta jenis wisata → warna
const colorMap = computed(() => {
    const jenisSet = [...new Set(props.lokasi.map((l) => l.jenis))];
    const mapping: Record<string, string> = {};
    jenisSet.forEach((jenis, index) => {
        mapping[jenis] = generateColorByIndex(index);
    });
    return mapping;
});

// Ambil warna dari jenis
const iconColor = (jenis: string): string => {
    return colorMap.value[jenis] || '#999';
};

// Buat ikon lingkaran dengan angka dan warna
const createIcon = (rank: number, color: string): L.DivIcon => {
    return L.divIcon({
        html: `
            <div style="
                background-color: ${color};
                color: white;
                width: 26px;
                height: 26px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 13px;
                font-weight: 600;
                border: 2px solid #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            ">
                ${rank || ''}
            </div>
        `,
        className: 'custom-marker-icon',
        iconSize: [26, 26],
        iconAnchor: [13, 13],
        popupAnchor: [0, -13],
    });
};

// Tambah dan update marker ke peta
const updateMarkers = () => {
    if (!map) return;

    // Bersihkan marker lama
    markers.forEach((m) => m.remove());
    markers = [];

    const lokasiFiltered = props.lokasi.filter((l) => !selectedJenis.value || l.jenis === selectedJenis.value);

    lokasiFiltered.forEach((lokasi) => {
        const lat = Number(lokasi.latitude);
        const lng = Number(lokasi.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const color = iconColor(lokasi.jenis);
        const rank = lokasi.rank ?? '';

        const marker = L.marker([lat, lng], {
            icon: createIcon(rank as number, color),
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
