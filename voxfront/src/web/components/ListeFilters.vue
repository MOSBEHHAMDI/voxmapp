<template>
  <v-row v-if="filters.length" class="pb-0">
    <v-col v-for="(filter, index) in filters" :key="index">
      <v-menu v-if="isValidDate(dynamic_data[0][filter.name])">
        <template v-slot:activator="{ props }">
          <v-text-field :value="$utils.formatDate(taggedFilters[filter.name],'-')"
                        :placeholder="filter.label"
                        class="special_input"
                        clearable
                        hide-details
                        v-bind="props"
                        variant="solo"
                        @change="applyFilter"
                        @keyup.enter="applyFilter"
                        prepend-inner-icon="mdi-calendar"
          >
            <template #append-inner>
              <v-icon v-if="taggedFilters[filter.name]" @click.stop class="ml-2"
                      @click="taggedFilters[filter.name] = null">mdi-close-circle
              </v-icon>
            </template>
          </v-text-field>
        </template>
        <v-date-picker @click.stop v-model="taggedFilters[filter.name]"></v-date-picker>
      </v-menu>
      <v-autocomplete
          v-if="hasObjects(filter)"
          v-model="taggedFilters[filter.name]"
          :items="options(filter)"
          :placeholder="tr(filter.name)"
          append-inner-icon="mdi-arrow-down"
          chips
          class="special_autocomplete"
          clearable
          closable-chips
          hide-details
          item-color="primary"
          menu-icon=""
          multiple
          return-object
          variant="solo"
          @focus="currentFilter = filter"
          @click:clear="applyFilter"
          @update:menu="applyFilter"
      ></v-autocomplete>
      <v-text-field v-else-if=" dynamic_data && dynamic_data.length> 0 && !isValidDate(dynamic_data[0][filter.name])"
                    v-model="taggedFilters[filter.name]"
                    :placeholder="tr(filter.name)"
                    class="special_input"
                    clearable
                    hide-details
                    variant="solo"
                    @keyup.enter="applyFilter"
                    @update:focused="applyFilter"
      >
      </v-text-field>
    </v-col>
    <v-col cols="2">
      <v-btn class="border-8 text-capitalize font-weight-regular filter-btn"
             color="primary" elevation="0"
             @click="applyFilter">{{ tr('Apply Filter') }}
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: ['filters', 'dynamic_data'],
  data: () => ({
    currentFilter: null,
    taggedFilters: {},
    filteredData: [],
  }),
  watch: {
    taggedFilters: {
      handler() {
        this.filterData();
      },
      deep: true
    }
  },
  computed: {},
  methods: {
    isValidDate(str) {
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      return dateRegex.test(str)
    }
    ,
    options(filter) {
      const dynamic_data = this.dynamic_data.map((dd) => dd[filter.name]);
      const prop = filter.filterProperty ? filter.filterProperty : filter.property;
      let items = new Set();
      dynamic_data.forEach((dd, index) => {
        if (Array.isArray(dd)) {
          dd.forEach((item, index) => {
            items.add(item[prop])
          })
        } else if (dd && typeof dd === 'object') {
          items.add(dd[prop])
        }
      })
      return Array.from(items).sort((a, b) => a.localeCompare(b, undefined, {sensitivity: "variant"}));
    },
    hasObjects(filter) {
      const data = this.dynamic_data[0];
      return data && data[filter.name] && typeof data[filter.name] === 'object'
    },
    filterData() {
      this.filteredData = this.dynamic_data.filter((dd) => {
        let included = true;
        this.filters.forEach((filter) => {
          const currentFilter = filter.name;
          const propertyName = filter.filterProperty ? filter.filterProperty : filter.property;
          const filterValue = this.taggedFilters[currentFilter];
          const currentData = dd[currentFilter];
          let filterIncluded = false;
          if (filterValue && currentData && this.isValidDate(currentData)) {
            if (currentData === this.$utils.formatDate(filterValue,'-')) {
              filterIncluded = true;
            }
            included = included && filterIncluded;
          }
          if (filterValue && filterValue.length > 0) {

            if (Array.isArray(currentData)) {
              currentData.forEach((item) => {
                if (filterValue.includes(item[propertyName])) {
                  filterIncluded = true;
                }
              });
            } else if (currentData && typeof currentData === 'object') {
              if (filterValue.includes(currentData[propertyName])) {
                filterIncluded = true;
              }
            } else if (typeof currentData === 'string') {
              filterIncluded = currentData.toLowerCase().includes(filterValue.toLowerCase());
            }
            included = included && filterIncluded;
          }
        })
        return included;
      });
    },
    applyFilter() {
      console.log('test')
      if (Object.keys(this.taggedFilters).length > 0) {
        this.$emit('filterDataList', this.filteredData);
      }
    }
  }
}
</script>

<style>
.filter-btn {
  height: 41px !important;
  min-height: 41px;
}
</style>