<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
        transportasi: string;
        akses_lokasi: string;
        rank?: number;
    }>;
}>();

const mapId = 'cetak-peta';
let map: L.Map | null = null;

const initMap = async () => {
    await nextTick();
    const el = document.getElementById(mapId);
    if (!el) return;

    map = L.map(mapId); // tanpa setView dulu

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const bounds: L.LatLngBoundsExpression[] = [];

    props.lokasi.forEach((lokasi) => {
        if (!lokasi.latitude || !lokasi.longitude || !lokasi.rank) return;

        // Tambahkan ke bounds
        bounds.push([Number(lokasi.latitude), Number(lokasi.longitude)]);

        // Marker dengan ranking
        const icon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="
                background: #f87171;
                color: white;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: bold;
                border: 2px solid white;
                box-shadow: 0 0 3px rgba(0,0,0,0.5);
            ">${lokasi.rank}</div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15],
        });

        const marker = L.marker([lokasi.latitude, lokasi.longitude], { icon }).addTo(map!);
        marker.bindPopup(`<b>${lokasi.nama}</b><br/>Ranking: ${lokasi.rank}`);
    });

    // Jika ada bounds, sesuaikan tampilan peta
    if (bounds.length > 0) {
        map.invalidateSize();
        map.fitBounds(bounds, { padding: [30, 20] });
    } else {
        // fallback jika tidak ada lokasi valid
        map.setView([-2.533, 140.703], 10);
    }
};

onMounted(() => {
    initMap();
    setTimeout(() => window.print(), 1000);
});
</script>

<template>
    <Head title="Cetak Laporan" />
    <div class="p-4 text-[14px] leading-tight print:p-0">
        <!-- Judul Laporan -->
        <div class="mb-4 flex flex-col items-center justify-center">
            <img src="../../../../public/image/logo_transparant.png" alt="Logo" class="h-20 w-20" />
            <h1 class="text-xl font-bold uppercase">Laporan Lokasi Wisata</h1>
            <h1 class="text-[18px] font-bold uppercase">PEMERINTAH KABUPATEN JAYAPURA</h1>
            <h2 class="text-[16px] font-semibold uppercase">DINAS PARIWISATA DAN KEBUDAYAAN</h2>
            <p class="text-sm">Jl. Contoh No. 123, Distrik Abepura, Kota Jayapura, Papua</p>
            <p class="text-sm">Email: dinaspariwisata@kabxyz.go.id | Telp: (0967) 123456</p>
        </div>

        <!-- Peta -->
        <div id="cetak-peta" class="mb-6 h-[400px] w-full border"></div>

        <!-- Tanggal Cetak -->
        <p class="mb-2 text-right text-sm italic">Tanggal Cetak: {{ new Date().toLocaleDateString('id-ID') }}</p>

        <!-- Tabel -->
        <table class="w-full border-collapse border text-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-2 py-1">Nama Lokasi</th>
                    <th class="border px-2 py-1">Jenis</th>
                    <th class="border px-2 py-1">Fasilitas</th>
                    <th class="border px-2 py-1">Keamanan</th>
                    <th class="border px-2 py-1">Transportasi</th>
                    <th class="border px-2 py-1">Akses Lokasi</th>
                    <th class="border px-2 py-1">Ranking</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(lokasi, i) in props.lokasi" :key="lokasi.nama">
                    <td class="border px-2 py-1">{{ lokasi.nama }}</td>
                    <td class="border px-2 py-1">{{ lokasi.jenis }}</td>
                    <td class="border px-2 py-1">{{ lokasi.fasilitas }}</td>
                    <td class="border px-2 py-1">{{ lokasi.keamanan }}</td>
                    <td class="border px-2 py-1">{{ lokasi.transportasi }}</td>
                    <td class="border px-2 py-1">{{ lokasi.akses_lokasi }}</td>
                    <td class="border px-2 py-1">{{ lokasi.rank ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
