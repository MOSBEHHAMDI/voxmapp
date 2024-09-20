<template>
  <user-history-navbar/>
  <v-row class="text-left mt-0 ml-2">
    <v-col>
      <OpenLayerMap :id="'user_history_map'" ref="OpenLayerMap_"
                    :clustered="false"
                    :lat-long="[0,0]"
                    :map-style="{ width: 100+  '%',height:400+'px'}" :zoom-control="false"
      ></OpenLayerMap>
    </v-col>
  </v-row>
  <v-row class="text-left mt-0 ml-2">
    <v-col>
      <span class="main">{{ tr('Daily actions') }}</span>
    </v-col>
  </v-row>
</template>
<script>
import OpenLayerMap from "@/web/components/OpenLayerMap.vue";
import UserHistoryNavbar from "@/mobile/components/UserHistoryNavbar.vue";

export default {
  name: "MapUserHistory",
  components: {OpenLayerMap, UserHistoryNavbar},
  methods: {}
}
</script>


<style scoped>

</style>