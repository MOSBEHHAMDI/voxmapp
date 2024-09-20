<template>
  <v-window v-model="$store.MobileCurrentTab">
    <v-window-item value="Map">
      <MapUserHistory/>
    </v-window-item>
    <v-window-item value="List">
      <ListUserHistory/>
    </v-window-item>
  </v-window>

</template>
<script>


import MapUserHistory from "@/mobile/components/MapUserHistory.vue";
import ListUserHistory from "@/mobile/components/ListUserHistory.vue";

export default {
  name: "tasks",
  components: {ListUserHistory, MapUserHistory}
}
</script>
