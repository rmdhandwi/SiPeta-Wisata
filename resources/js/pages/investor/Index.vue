<script setup lang="ts">
import { SharedData } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { Select } from 'primevue';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const page = usePage<SharedData>();
const user = page.props.auth.user;

function logout() {
    router.post('/logout');
}

const props = defineProps<{
    lokasi: Array<{
        nama: string;
        jenis: string;
        latitude: number;
        longitude: number;
        fasilitas: string;
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

const iconColor = (jenis: string, isTopRank: boolean): string => {
    if (isTopRank) return 'red';
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

const updateMarkers = () => {
    if (!map) return;
    markers.forEach((m) => m.remove());
    markers = [];

    const lokasiFiltered = props.lokasi.filter(
        (l) => (!selectedJenis.value || l.jenis === selectedJenis.value) && typeof l.latitude === 'number' && typeof l.longitude === 'number',
    );

    lokasiFiltered.forEach((lokasi) => {
        const isTopRank = lokasi.rank === 1;
        const marker = L.marker([lokasi.latitude, lokasi.longitude], {
            icon: createIcon(iconColor(lokasi.jenis, isTopRank)),
        }).addTo(map!);

        const popup = `
      <div style="font-size: 14px;">
        <strong style="font-size: 16px;">${lokasi.nama}</strong>
        <table style="margin-top: 5px;">
          <tr><td><b>Jenis</b></td><td>: ${lokasi.jenis}</td></tr>
          <tr><td><b>Fasilitas</b></td><td>: ${lokasi.fasilitas || '-'}</td></tr>
          <tr><td><b>Keamanan</b></td><td>: ${lokasi.keamanan || '-'}</td></tr>
          <tr><td><b>Akses</b></td><td>: ${lokasi.akses_lokasi || '-'}</td></tr>
          ${lokasi.rank !== undefined ? `<tr><td><b>Ranking</b></td><td>: ${lokasi.rank}</td></tr>` : ''}
          ${lokasi.preferensi !== undefined ? `<tr><td><b>Preferensi</b></td><td>: ${lokasi.preferensi}</td></tr>` : ''}
        </table>
      </div>
    `;

        marker.bindPopup(popup);
        markers.push(marker);
    });
};

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
    <Head title="Lokasi Wisata" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navbar -->
        <nav class="flex items-center justify-between bg-white px-6 py-4 shadow">
            <div class="flex items-center gap-4">
                <img src="image/logo_transparant.png" alt="Logo SiPeta-Wisata" class="max-h-10 w-auto" />
                <h1 class="text-xl font-semibold text-gray-700">Potensi Lokasi Wisata</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-600">👤 {{ user.name }}</span>
                <Button @click="logout" icon="pi pi-sign-out" severity="danger" size="small" label="Logout" />
            </div>
        </nav>

        <!-- Main content -->
        <main class="p-6">
            <div class="mb-4 flex items-center gap-2">
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

            <div :id="mapId" class="h-[700px] w-full rounded shadow" />
        </main>
    </div>
</template>
