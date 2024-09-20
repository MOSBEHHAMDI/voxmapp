<template>
  <div>
    <OpenLayerMap :id="'baseline_map'" ref="OpenLayerMap_"
                :clustered="true"
                :data-points="dataPoints" :map-style="{ width: 100+  '%',height:100+'vh'}"
                :zoom-control="true"
                :options="{tracking:true,filter:true,style:true,dataPointsPage:true,currentLocation:true}"
                :select-event="true"
  ></OpenLayerMap>
  </div>

</template>

<script>
import OpenLayerMap from "../../web/components/OpenLayerMap.vue"

export default {
  components: {
    OpenLayerMap
  },
  mounted() {
    window.mainMap = this.$refs["OpenLayerMap_"];
  },
  computed: {
    dataPoints() {
      return this.$dataPoint_store.dataPointsList
    },
  }
}
</script>
