<template>
  <v-list class="pale-bg">
    <v-list-item class="main text-white">
      <span class="main">{{ tr('dataPoints near you') }}</span>
    </v-list-item>
    <v-list-item v-if="!dataPoints.length">
      <span class="main">{{ tr(' Empty list') }}</span>
    </v-list-item>
  </v-list>
  <v-list class="pale-bg">
    <v-list-item v-for="dataPoint in  dataPoints">
      <v-card class="main-border rounded-xl text-left pl-3 pb-2" elevation="0" @click="selectDataPoint(dataPoint)">
        <v-card-title>{{ dataPoint.name }}</v-card-title>
        <v-card-subtitle>
          {{ dataPoint.updateDate }}
        </v-card-subtitle>
      </v-card>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  name: "dataPoints",
  methods: {
    selectDataPoint(dataPoint) {
      if (dataPoint.project) {
        dataPoint.project.name = this.$utils.getLabel(dataPoint.project.name)
      }
      this.$dataPoint_store.currentDataPoint = dataPoint;
      this.$router.push('/dataPointRegistry');
    }
  },
  created() {

  },
  data() {
    return {};
  },
  computed: {
    dataPoints() {
      //Todo user's datapoints => issue: VM-60
       return this.$dataPoint_store.dataPointsList;
    }
  }
}
</script>