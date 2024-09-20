<template>
  <user-history-navbar/>
  <v-row>
    <v-col>
      <v-card v-for="(action,index) in  $store.currentActions.list" :key="action.id"
      >
        <template v-slot:text>
          <v-list-item class="mt- text-left"
                       subtitle="Start"
                       title="Live tracking">
            <template #prepend>
              <v-icon size="40">mdi-radar</v-icon>
            </template>
            <template #append>
              {{ $utils.extractTime(action.history.date) }}
            </template>
          </v-list-item>
        </template>
        <v-card-text>
             <OpenLayerMap :id="'userHistory_map'+index"
                          :lat-long="action.history.facility.latLong.split(',').map(Number)"
                          :map-style="{ width: 100+  '%',height:200+'px'}"
                          :zoom-control="false"
                          :clustered="false"
                          :map-feature-object="action.history.mapFeature"
                          :select-event="false"

            />
        </v-card-text>
        <v-card-actions>
          <v-row>
            <v-col>50 km distance</v-col>
            <v-divider vertical></v-divider>
            <v-col>50 km/h average speed</v-col>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>
<script>
import OpenLayerMap from "@/web/components/OpenLayerMap.vue";
import UserHistoryNavbar from "@/mobile/components/UserHistoryNavbar.vue"

export default {
  name: "ListUserHistory",
  components: {OpenLayerMap, UserHistoryNavbar},
}
</script>


<style scoped>

</style>