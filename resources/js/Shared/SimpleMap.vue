<template>
    <div :class="[mapClass]">
        <LMap
            :minZoom="minZoom"
            :maxZoom="maxZoom"
            v-model="zoom"
            v-model:zoom="zoom"
            :center="center"
        >
            <LControlScale
                position="bottomright"
                :imperial="useImperial"
                :metric="useMetric"
            ></LControlScale>
            <LControlLayers v-if="showLayers" position="topright"></LControlLayers>
            <LTileLayer
                v-for="tileProvider in tileProviders"
                :key="tileProvider.name"
                :name="tileProvider.name"
                :visible="tileProvider.visible"
                :url="tileProvider.url"
                :attribution="tileProvider.attribution"
                layer-type="base"
            />
            <LTileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"></LTileLayer>

            <LMarker
                v-for="(item, index) in markers"
                :key="index"
                :lat-lng="item.location"
                :draggable="draggable"
            >
                <LPopup>
                    {{ item.popupText }}
                </LPopup>
            </LMarker>
        </LMap>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import {
    LMap,
    LIcon,
    LTileLayer,
    LMarker,
    LControlLayers,
    LControlScale,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LRectangle,
} from '@vue-leaflet/vue-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

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

console.log(L);
// var markers = L.markerClusterGroup();

const log = (a) => {
    console.log(a);
};
</script>
