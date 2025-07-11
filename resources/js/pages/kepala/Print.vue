<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { nextTick, onMounted } from 'vue';

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

let map: L.Map | null = null;

const getColor = (jenis: string) => {
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

const createIcon = (rank: number, jenis: string): L.DivIcon => {
    const color = getColor(jenis);
    return L.divIcon({
        html: `<div class="custom-icon" style="
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

onMounted(async () => {
    await nextTick();

    const mapElement = document.getElementById('printable-map');
    if (!mapElement) return;

    map = L.map(mapElement).setView([-2.5, 140.5], 11);

    const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    });

    tileLayer.addTo(map);

    const bounds = L.latLngBounds([]);

    props.lokasi.forEach((lokasi) => {
        const lat = Number(lokasi.latitude);
        const lng = Number(lokasi.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: createIcon(lokasi.rank ?? 0, lokasi.jenis),
        }).addTo(map!);

        bounds.extend([lat, lng]);

        marker.bindPopup(`
            <strong>${lokasi.nama}</strong><br/>
            Jenis: ${lokasi.jenis}<br/>
            Fasilitas: ${lokasi.fasilitas}<br/>
            Keamanan: ${lokasi.keamanan}<br/>
            Akses: ${lokasi.akses_lokasi}
        `);
    });

    if (!bounds.isValid()) {
        map.fitBounds(bounds, { padding: [30, 30] });
    }

    // ✅ TUNGGU sampai tile map selesai load, baru cetak
    tileLayer.on('load', () => {
        setTimeout(() => {
            window.print();
        }, 300); // Sedikit delay untuk memastikan render
    });

    // Otomatis kembali setelah print selesai
    window.onafterprint = () => {
        router.visit('/laporan');
    };
});
</script>

<template>
    <Head title="Print Data" />
    <div class="min-h-screen p-6">
        <h1 class="mb-4 text-center text-xl font-bold">Cetak Peta Lokasi Wisata</h1>

        <!-- PETA -->
        <div id="printable-map" class="mb-6 h-[620px] w-full rounded border"></div>

        <!-- DAFTAR -->
        <div>
            <!-- Header -->
            <div class="mb-6 border-b pb-4 text-center">
                <img src="../../../../public/image/logo_transparant.png" alt="Logo" class="mx-auto mb-2 h-auto w-20" />
                <h1 class="text-xl font-bold uppercase">Pemerintah Kabupaten Jayapura</h1>
                <h2 class="text-lg font-semibold">Dinas Pariwisata dan Ekonomi Kreatif</h2>
                <p class="mt-1 text-sm">Penyusunan Rencana Induk Pengembangan Pariwisata Daerah (RIPPDA)</p>
                <p class="text-sm">Kabupaten Jayapura Tahun</p>
                <h3 class="mt-2 font-bold">Peta Sebaran Daya Tarik Wisata</h3>
            </div>

            <h2 class="mb-2 text-center font-bold">Daftar Lokasi Wisata Kabupaten Jayapura</h2>

            <ul class="columns-2 text-sm md:columns-3">
                <li v-for="lokasi in props.lokasi.sort((a, b) => (a.rank || 999) - (b.rank || 999))" :key="lokasi.nama" class="mb-1">
                    {{ lokasi.rank }}. {{ lokasi.nama }} ({{ lokasi.jenis }})
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
/* Hapus penyembunyian body saat screen */
@media screen {
    .print\:block {
        display: none !important;
    }
}

@media print {
    body {
        visibility: visible;
    }

    #app > *:not(.print\:block) {
        display: none !important;
    }

    .print\:block {
        display: block !important;
    }
}
</style>
