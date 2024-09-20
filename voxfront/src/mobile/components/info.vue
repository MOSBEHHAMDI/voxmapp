<template>
  <v-card class="border-11">
    <v-row>
      <v-col class="text-left">
        <v-card-text>
          <div class="font-weight-bold main">Address</div>
          <span> {{ $dataPoint_store.currentDataPoint?.address }}</span>
        </v-card-text>
        <v-card-subtitle class="mb-2">{{ $dataPoint_store.currentDataPoint?.city?.name }}</v-card-subtitle>
      </v-col>
      <v-col>
        <Map :id="'info_map'"
             :latLong="$dataPoint_store.currentDataPoint?.latLong?.split(',').map(Number)"
             :map-style="{width: 100+  '%',height : 100 + 'px'}" :zoom="1"
             :zoom-control="false"
             :select-event="false"
        />
      </v-col>
    </v-row>
  </v-card>
  <v-row class="mt-0">
    <v-col>
      <v-card class="border-11 text-left">
        <v-card-text>
          <div class="font-weight-bold main">State</div>
          <span> {{ $dataPoint_store.currentDataPoint?.status?.name }}</span>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col>
      <v-card class="border-11 text-left">
        <v-card-text>
          <div class="font-weight-bold main">Created By</div>
          <span> {{ $dataPoint_store.currentDataPoint?.createdBy?.userName }}</span>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
  <div class="main font-weight-bold text-left mt-6">Contacts</div>
  <v-row>
    <v-col>
      <v-card v-for="user in $dataPoint_store.currentDataPoint?.users" :key="user.id" class="mt-2 rounded-xl"
      >
        <template v-slot:text>
          <v-list-item :subtitle="user.email"
                       :title="user.userName"
                       class="mt- text-left"
                       prepend-avatar="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
          >
          </v-list-item>
        </template>

      </v-card>
    </v-col>
  </v-row>
</template>
<script>
import Map from "@/web/components/OpenLayerMap.vue";
import OpenLayerMap from "@/web/components/OpenLayerMap.vue";

export default {
  name: "info",
  components: {OpenLayerMap, Map}
}
</script>
