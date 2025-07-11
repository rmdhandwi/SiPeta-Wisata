<script setup lang="ts">
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
const waktuSekarang = ref('');
const lokasiDipilih = ref<(typeof props.lokasi)[0] | null>(null);

const jenisUnik = computed(() => [
    { label: 'Semua', value: '' },
    ...[...new Set(props.lokasi.map((l) => l.jenis))].map((j) => ({
        label: j,
        value: j,
    })),
]);

const updateTime = () => {
    const now = new Date();
    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
    const waktu = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });

    waktuSekarang.value = `${hari}, ${tanggal} - ${waktu}`;
};
setInterval(updateTime, 1000);

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
        (l) => (!selectedJenis.value || l.jenis === selectedJenis.value),
    );

    lokasiFiltered.forEach((lokasi) => {
        const lat = Number(lokasi.latitude);
        const lng = Number(lokasi.longitude);
        if (isNaN(lat) || isNaN(lng)) return;
        const isTopRank = lokasi.rank === 1;
        const marker = L.marker([lokasi.latitude, lokasi.longitude], {
            icon: createIcon(iconColor(lokasi.jenis, isTopRank)),
        }).addTo(map!);

        marker.on('click', () => {
            lokasiDipilih.value = lokasi;
        });

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
                <img src="/image/logo_transparant.png" alt="Logo SiPeta-Wisata" class="max-h-10 w-auto" onerror="this.style.display='none'" />
                <div>
                    <h1 class="text-xl font-semibold text-gray-700">Peta Potensi Lokasi Wisata</h1>
                    <p class="text-sm text-gray-500">Visualisasi lokasi berdasarkan jenis, preferensi, dan peringkat.</p>
                </div>
            </div>
            <div class="text-right text-sm text-gray-600">
                <div class="font-medium">⏰ {{ waktuSekarang }}</div>
            </div>
        </nav>

        <!-- Main Grid -->
        <main class="grid grid-cols-1 gap-4 p-6 xl:grid-cols-3">
            <!-- Peta -->
            <div class="space-y-4 xl:col-span-2">
                <div class="flex items-center gap-2">
                    <label class="font-medium" title="Pilih jenis wisata untuk menampilkan hanya kategori tertentu."> Filter Jenis Wisata: </label>
                    <Select
                        v-model="selectedJenis"
                        :options="jenisUnik"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Pilih Jenis Wisata"
                        class="w-64"
                    />
                    <Button icon="pi pi-arrow-left" as="a" href="/" variant="outlined" label="Kembali" severity="info" />
                </div>

                <div :id="mapId" class="h-[700px] w-full rounded shadow" />
            </div>

            <!-- Detail Lokasi -->
            <div class="flex items-center justify-center xl:items-center">
                <div v-if="lokasiDipilih" class="mx-auto w-full max-w-md rounded bg-white p-6 shadow">
                    <h2 class="mb-2 text-center text-lg font-semibold text-blue-600">📍 Detail Lokasi Terpilih</h2>
                    <p class="mb-1 text-center text-gray-700">
                        <strong>{{ lokasiDipilih.nama }}</strong
                        ><br />
                        <span class="text-sm text-gray-500 italic">{{ lokasiDipilih.jenis }}</span>
                    </p>
                    <table class="mt-4 w-full text-sm text-gray-600">
                        <tr>
                            <td class="font-medium">Fasilitas</td>
                            <td>: {{ lokasiDipilih.fasilitas || '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-medium">Keamanan</td>
                            <td>: {{ lokasiDipilih.keamanan || '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-medium">Akses</td>
                            <td>: {{ lokasiDipilih.akses_lokasi || '-' }}</td>
                        </tr>
                        <tr v-if="lokasiDipilih.rank !== undefined">
                            <td class="font-medium">Ranking</td>
                            <td>: {{ lokasiDipilih.rank }}</td>
                        </tr>
                        <tr v-if="lokasiDipilih.preferensi !== undefined">
                            <td class="font-medium">Preferensi</td>
                            <td>: {{ lokasiDipilih.preferensi.toFixed(4) }}</td>
                        </tr>
                    </table>
                </div>

                <div v-else class="w-full text-center text-sm text-gray-500 italic">
                    Klik salah satu marker di peta untuk melihat detail lokasi wisata.
                </div>
            </div>
        </main>
    </div>
</template>
