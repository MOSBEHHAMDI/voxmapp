<template>
  <div class="pl-5 pr-5">
    <v-row>
      <v-col class="text-left pb-0">
        <v-btn id="menu-columns-activator" color="primary" variant="tonal" class="mr-2">Columns</v-btn>
        <v-btn
            id="menu-activator"
            class="mt-4 mb-4"
            color="primary"
            elevation="0"
        >
          {{ selectedFilters.length ? selectedFilters.length : '' }} {{ ' ' + tr('Filters') }}
        </v-btn>
        <v-btn v-if="selectedFilters.length" id="menu-activator"
               class="mt-4 mb-4 ml-2"
               color="primary"
               elevation="0"
               @click="selectedFilters=[]"
        >
          <!-- <v-icon>mdi-broom</v-icon> -->
          {{ tr('Clear') }}
        </v-btn>
        <v-menu activator="#menu-activator" max-height="600">
          <v-list class="pale-bg">
            <v-list-item v-for="(filter, index) in config.header" :key="index" class="text-left" max-height="10px"
                         @click.stop>
              <template #prepend>
                <v-checkbox v-model="selectedFilters" :label="filter.label" :value="filter"></v-checkbox>
              </template>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-menu activator="#menu-columns-activator" max-height="600">
          <v-list class="pale-bg">
            <v-list-item>
              <v-btn block color="primary" @click="selectedColumns = []" variant="tonal">
                <v-icon>mdi-reload</v-icon>
                Reset
              </v-btn>
            </v-list-item>
            <v-list-item v-for="(header, index) in header" :key="index" class="text-left" max-height="10px"
                         @click.stop>
              <template #prepend>
                <v-checkbox v-model="selectedColumns" :label="header.label" :value="header"></v-checkbox>
              </template>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-col>
    </v-row>
    <div data-block="'filters" class="mt-4">
      <ListFilters :dynamic_data="dynamic_data" :filters="selectedFilters" @filterDataList="handleDataFilter"
      />
    </div>
    <div data-block="content">
      <DataTable :config="config" :dynamic_data="filteredData" :filteredHeader="filteredHeader" @triggerEvent="triggerEvent"/>
    </div>
  </div>
</template>

<script>
import DataTable from "@/web/components/DataTable";
import ListFilters from "@/web/components/ListeFilters";

export default {
  components: {ListFilters, DataTable},
  props: ['config', 'dynamic_data'],
  watch: {
    dynamic_data: {
      handler(newValue) {
        this.filteredData = newValue
      },
    }
  },
  created() {
    this.filteredData = this.dynamic_data;
  },
  data: () => ({
    filteredData: [],
    selectedFilters: [],
    selectedColumns: [],
  }),
  computed: {
    filteredHeader() {
      let dataHeader = this.header
      if (this.selectedColumns.length !== 0) {
        dataHeader = this.header.filter(e => !this.selectedColumns.includes(e))
      }
      return dataHeader
    },
    header() {
      return this.config.header.filter(h => h.visible)
    }
  },
  methods: {
    handleDataFilter(newData) {
      this.filteredData = newData;
    },
    triggerEvent(params) {
      this.$emit('triggerEvent', params)
    }
  }
}
</script>
