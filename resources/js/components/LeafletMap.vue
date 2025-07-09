<template>
    <div :id="mapId" class="h-screen w-full"></div>
</template>

<script setup lang="ts">
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { nextTick, onMounted, watch } from 'vue';

const props = defineProps<{
    lat?: number;
    lng?: number;
    id?: string;
}>();

const emit = defineEmits<{
    (e: 'update:latlng', coords: { lat: number; lng: number }): void;
}>();

const mapId = props.id || 'leaflet-map';
let map: L.Map | null = null;
let marker: L.Marker | null = null;

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

    const initialLat = props.lat ?? -2.533;
    const initialLng = props.lng ?? 140.703;

    map = L.map(mapId).setView([initialLat, initialLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

    map.on('click', function (e: L.LeafletMouseEvent) {
        const { lat, lng } = e.latlng;

        marker?.setLatLng([lat, lng]);

        emit('update:latlng', { lat, lng }); // kirim ke parent
    });
};

onMounted(() => {
    initMap();
});

watch([() => props.lat, () => props.lng], () => {
    if (map && props.lat && props.lng && marker) {
        marker.setLatLng([props.lat, props.lng]);
        map.setView([props.lat, props.lng], 13);
    }
});
</script>

