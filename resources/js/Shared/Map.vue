<template>
    <LMap
        ref="map"
        :max-zoom="maxZoom"
        :min-zoom="minZoom"
        v-model:zoom="zoom"
        v-model:center="center"
        :zoomAnimation="false"
        :markerZoomAnimation="false"
        :useGlobalLeaflet="true"
        :options="{ zoomControl: false }"
        @ready="onLeafletReady"
        :class="[mapClass]"
    >
        <template v-if="leafletReady">
            <LControlZoom position="topleft" />
            <LControlLayers v-if="showLayers" position="topright" />
            <LTileLayer
                v-for="tileProvider in tileProviders"
                :key="tileProvider.name"
                :name="tileProvider.name"
                :visible="tileProvider.visible"
                :url="tileProvider.url"
                :attribution="tileProvider.attribution"
                layer-type="base"
            />
        </template>
    </LMap>
</template>
<script setup>
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/leaflet.markercluster.js';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import { LMap, LTileLayer, LControlZoom, LControlLayers } from '@vue-leaflet/vue-leaflet';
import { ref, nextTick, onMounted } from 'vue';

const props = defineProps({
    center: {
        type: [Array, Object],
        default: [0.9612510512763061, 114.55494157889441],
    },
    zoom: {
        type: [Number, String],
        default: 6,
    },
    markers: {
        type: Object,
        default: {},
    },
    mapClass: {
        type: String,
        default: 'xl:h-[42.5rem] w-auto',
    },
    draggable: {
        type: Boolean,
        default: false,
    },
    maxZoom: {
        type: Number,
        default: 18,
    },
    minZoom: {
        type: Number,
        default: 6,
    },
    showLayers: {
        type: Boolean,
        default: false,
    },
    tileProviders: {
        type: [Object, Array],
        default: [
            {
                name: 'OpenStreetMap',
                visible: true,
                attribution:
                    '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            },
            {
                name: 'OpenTopoMap',
                visible: false,
                url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                attribution:
                    'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
            },
            {
                name: 'Street',
                visible: false,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
                id: 'mapbox/streets-v11',
            },
            {
                name: 'Grayscale',
                visible: false,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://api.mapbox.com/styles/v1/mapbox/light-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
            },
            {
                name: 'Darkmode',
                visible: false,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://api.mapbox.com/styles/v1/mapbox/dark-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
            },
        ],
    },
    useMetric: {
        type: Boolean,
        default: true,
    },
    useImperial: {
        type: Boolean,
        default: false,
    },
});

const leafletReady = ref(false);
const leafletObject = ref(null);

const onLeafletReady = async (map) => {
    await nextTick();
    leafletReady.value = true;
};

onMounted(() => {
    try {
        L.Map.addInitHook(function () {
            const markerCluster = L.markerClusterGroup({
                removeOutsideVisibleBounds: true,
                chunkedLoading: true,
            }).addTo(this);

            function r(min, max) {
                return Math.random() * (max - min) + min;
            }

            let markers = [];
            for (let [key, value] of Object.entries(props.markers)) {
                const marker = L.marker(L.latLng(value.location));
                marker.bindPopup(value.popupText);
                markers.push(marker);
            }

            markerCluster.addLayers(markers);
        });
    } catch (error) {
        console.log('error', error);
    }
});
</script>
