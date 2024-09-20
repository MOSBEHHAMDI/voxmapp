<template>
  <v-row :class="gridLayout ? 'gridLayout' : ''" color="transparent" elevation="0">
    <v-col v-for="(item, index) in dataColumns" :key="index" md="6">
      <span style="font-weight: bold" v-html="capitalizeWords(index).replace(/([a-z])([A-Z])/g, '$1 $2')"></span>:
      <span v-if="typeof (item) === 'boolean'"><v-icon v-if="item">mdi-check-outline</v-icon><v-icon v-else>mdi-close-outline</v-icon></span>
      <span v-else v-html="capitalizeWords(item)"/>
    </v-col>
  </v-row>
</template>
<script>

export default {
  name: "DataPointColumns",
  props: ['gridLayout'],
  methods: {
    capitalizeWords(str) {
      if (str && typeof (str) !== 'boolean') {
        return str.replace(/\b\w/g, function (char) {
          return char.toUpperCase();
        });
      }
    }
  },
  computed: {
    dataColumns() {
      let result = [];
      if (this.$dataPoint_store.currentDataPoint) {
        const config = [
          {name: 'accessType', property: 'name'},
          {name: 'city', property: 'name'},
          {name: 'dataPointType', property: 'name'},
          {name: 'project', property: 'name'},
          {name: 'public', property: 'name'},
          {name: 'sourceType', property: 'name'},
          {name: 'status', property: 'name'},
          {name: 'uploads', property: 'name', visible: false},
          {name: 'users', property: 'userName', visible: false},
          {name: 'tasks', property: 'name', visible: false},
          {name: 'history', visible: false}

        ];
        result = this.$utils.getKeysValues(this.$dataPoint_store.currentDataPoint, config);
      }
      return result;
    },
  }
}
</script>

<style>
.gridLayout {
  display: grid !important;
}

.gridLayout .v-col {
  max-width: 100%;
  width: 100%;
  padding: 5px;
}
</style>