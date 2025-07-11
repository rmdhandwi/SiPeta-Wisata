<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { computed, nextTick, onMounted, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const page = usePage<SharedData>();
const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Laporan Wisata', href: 'laporan' }];

watch(
    () => page.props.flash.success,
    (msg) => {
        if (msg) toast.success(msg, { position: 'top-right', duration: 3000 });
    },
    { immediate: true },
);

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

const mapId = 'printable-map';
let map: L.Map | null = null;
let markers: L.Marker[] = [];
let markerMap = new Map<string, L.Marker>();

const sortedLokasi = computed(() => [...props.lokasi].filter((l) => l.rank !== undefined).sort((a, b) => (a.rank || 999) - (b.rank || 999)));

// Warna berdasarkan jenis
const getMarkerColor = (jenis: string): string => {
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

// Hanya angka ranking berwarna
const createCustomIcon = (rank: number, jenis: string): L.DivIcon => {
    const color = getMarkerColor(jenis);
    return L.divIcon({
        html: `<div style="
            background-color: ${color};
            color: white;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid white;
            box-shadow: 0 0 2px rgba(0,0,0,0.5);
        ">${rank}</div>`,
        className: '',
        iconSize: [26, 26],
        iconAnchor: [13, 13],
        popupAnchor: [0, -13],
    });
};

const initMap = async () => {
    await nextTick();
    const mapElement = document.getElementById(mapId);
    if (!mapElement) return;

    if (map) map.remove();
    markerMap.clear();

    map = L.map(mapId).setView([-2.533, 140.703], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    markers.forEach((m) => m.remove());
    markers = [];

    sortedLokasi.value.forEach((lokasi, index) => {
        let lat = Number(lokasi.latitude);
        let lng = Number(lokasi.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        for (let j = 0; j < index; j++) {
            const prev = sortedLokasi.value[j];
            if (Math.abs(Number(prev.latitude) - lat) < 0.0002 && Math.abs(Number(prev.longitude) - lng) < 0.0002) {
                lat += 0.0002 * (index + 1);
                lng += 0.0002 * (index + 1);
                break;
            }
        }

        const marker = L.marker([lat, lng], {
            icon: createCustomIcon(lokasi.rank ?? 0, lokasi.jenis),
        }).addTo(map!);

        marker.bindPopup(`
            <div style="font-size: 14px;">
                <strong style="font-size: 16px;">${lokasi.nama}</strong>
                <table style="margin-top: 5px;">
                    <tr><td><b>Jenis</b></td><td>: ${lokasi.jenis}</td></tr>
                    <tr><td><b>Fasilitas</b></td><td>: ${lokasi.fasilitas || '-'}</td></tr>
                    <tr><td><b>Keamanan</b></td><td>: ${lokasi.keamanan || '-'}</td></tr>
                    <tr><td><b>Akses</b></td><td>: ${lokasi.akses_lokasi || '-'}</td></tr>
                </table>
            </div>
        `);

        markers.push(marker);
        markerMap.set(lokasi.nama, marker);
    });
};

const focusToLocation = (lat: number, lng: number, nama: string) => {
    if (map) {
        map.setView([lat, lng], 18, { animate: true });
        const marker = markerMap.get(nama);
        if (marker) marker.openPopup();
    }
};

onMounted(() => {
    initMap();
});
</script>

<template>
    <Head title="Laporan Lokasi Wisata" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Tombol Print Mengambang -->
                <div class="fixed right-4 bottom-4 z-50 opacity-50 transition-all hover:opacity-100">
                    <Button as="a" :href="route('laporan.cetak')" severity="info" icon="pi pi-print" label="Print" />
                </div>

                <!-- PETA -->
                <div class="relative rounded border shadow">
                    <div id="printable-map" class="h-full w-full"></div>
                </div>

                <!-- DAFTAR -->
                <div class="overflow-auto rounded bg-white p-4 shadow">
                    <!-- Header -->
                    <div class="mb-6 border-b pb-4 text-center">
                        <img src="image/logo_transparant.png" alt="Logo" class="mx-auto mb-2 h-auto w-20" />
                        <h1 class="text-xl font-bold uppercase">Pemerintah Kabupaten Jayapura</h1>
                        <h2 class="text-lg font-semibold">Dinas Pariwisata dan Ekonomi Kreatif</h2>
                        <p class="mt-1 text-sm">Penyusunan Rencana Induk Pengembangan Pariwisata Daerah (RIPPDA)</p>
                        <p class="text-sm">Kabupaten Jayapura Tahun</p>
                        <h3 class="mt-2 font-bold underline">Peta Sebaran Daya Tarik Wisata</h3>
                    </div>

                    <h2 class="mb-4 text-center text-xl font-bold">Daftar Lokasi Berdasarkan Ranking</h2>

                    <!-- Keterangan Warna -->
                    <div class="mb-4 flex flex-wrap justify-center gap-4 text-[13px]">
                        <div class="flex items-center gap-2">
                            <div class="h-4 w-4 rounded-full border bg-green-600"></div>
                            <span>Wisata Alam</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-4 w-4 rounded-full border bg-blue-600"></div>
                            <span>Wisata Sejarah</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-4 w-4 rounded-full border bg-orange-500"></div>
                            <span>Wisata Pantai</span>
                        </div>
                    </div>

                    <!-- Baris per 10 item -->
                    <div class="space-y-3 text-[13px]">
                        <ul class="columns-2 text-sm md:columns-3">
                            <li v-for="lokasi in props.lokasi.sort((a, b) => (a.rank || 999) - (b.rank || 999))" :key="lokasi.nama" class="mb-1">
                                {{ lokasi.rank }}. {{ lokasi.nama }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
