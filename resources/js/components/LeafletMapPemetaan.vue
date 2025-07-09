<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';
import { onMounted, ref, computed, nextTick, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pemetaan',
        href: '/admin/pemetaan',
    },
];

const page = usePage<SharedData>();
const toast = useToast();

const props = defineProps<{
    lokasi: Array<{
        nama: string;
        jenis: string;
        latitude: number;
        longitude: number;
        rank?: number;
        preferensi?: number;
    }>;
}>();

const selectedJenis = ref('');
const mapId = 'leaflet-map-pemetaan';
let map: L.Map | null = null;
let markers: L.Marker[] = [];

const jenisUnik = computed(() => [
    ...new Set(props.lokasi.map((l) => l.jenis))
]);

const iconColor = (jenis: string, isTopRank: boolean): string => {
    if (isTopRank) return 'red';
    switch (jenis.toLowerCase()) {
        case 'wisata alam': return 'green';
        case 'wisata sejarah': return 'blue';
        case 'wisata pantai': return 'orange';
        default: return 'gray';
    }
};

const createIcon = (color: string) => {
    return L.icon({
        iconUrl: `/marker-${color}.png`,
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
        (l) => (!selectedJenis.value || l.jenis === selectedJenis.value) &&
               typeof l.latitude === 'number' &&
               typeof l.longitude === 'number'
    );

    lokasiFiltered.forEach((lokasi) => {
        const isTopRank = lokasi.rank === 1;
        const marker = L.marker([lokasi.latitude, lokasi.longitude], {
            icon: createIcon(iconColor(lokasi.jenis, isTopRank)),
        }).addTo(map!);

        const popupContent = `
            <strong>${lokasi.nama}</strong><br/>
            Jenis: ${lokasi.jenis}<br/>
            ${lokasi.rank ? `Ranking: ${lokasi.rank}<br/>` : ''}
            ${lokasi.preferensi ? `Nilai Preferensi: ${lokasi.preferensi}` : ''}
        `;

        marker.bindPopup(popupContent);
        markers.push(marker);
    });
};

const initMap = async () => {
    await nextTick();
    const mapElement = document.getElementById(mapId);
    if (!mapElement) {
        console.error('Map container not found');
        return;
    }
    if (map) {
        map.remove();
    }
    map = L.map(mapId).setView([-2.533, 140.703], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    updateMarkers();
};

onMounted(() => {
    initMap();
});

watch(selectedJenis, () => {
    updateMarkers();
});

watch(() => props.lokasi, () => {
    updateMarkers();
});
</script>

<template>
    <Head title="Pemetaan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <HeadingSmall title="Pemetaan Lokasi Wisata" description="Visualisasi lokasi berdasarkan jenis wisata." />

            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <label class="font-medium">Filter Jenis Wisata:</label>
                    <select v-model="selectedJenis" class="rounded border p-1">
                        <option value="">Semua</option>
                        <option v-for="jenis in jenisUnik" :key="jenis" :value="jenis">{{ jenis }}</option>
                    </select>
                </div>

                <div :id="mapId" class="h-[600px] w-full rounded"></div>
            </div>
        </div>
    </AppLayout>
</template>
