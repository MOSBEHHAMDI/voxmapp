<template>
  <div>
    <v-card v-if="Object.entries(dynamic_data).length === 0" class="text-center mt-3 main mb-5" elevation="0">
      <v-card-title class="pl-0 pb-5">{{ tr('Empty List') }}</v-card-title>
    </v-card>
    <v-card class="text-left text-capitalize main pa-0" v-else color="transparent" elevation="0">
      <v-card-title class="pl-0 pb-5" v-if="config.title">{{ config.title }}</v-card-title>
      <v-card-text class="mb-5 pa-0">
        <div v-for="(data, i) in dynamic_data" :key="i"
             class="bg-white main-border questionnaire-info"
             :class="borderClass(i, Object.entries(dynamic_data).length)">
          <v-card
              v-if="data.quickEdit"
              class="ma-5 pa-3 grey-bg main main-thin-border" color="#ededed" elevation="0">
            <QuickEdit/>
          </v-card>
          <v-card color="transparent" class="pa-0" elevation="0">
            <v-card-text>
              <v-row @click="$emit('triggerEvent', {event: 'rowSelected', data})">
                <v-col v-for="(h, index) in filteredHeader" :key="index" class="pa-2 pl-3 pr-3 text-capitalize">
                  <div class="main" :class="index === 0 ? 'font-size-medium' : 'font-weight-bold'">
                    <span v-if="h.title && (typeof data[h.title].id !== 'undefined')">{{ $utils.getLabel(data[h.title]) }}</span>
                    <span v-else-if="h.title && (typeof data[h.title].id === 'undefined')">{{ data[h.title] }}</span>
                    <span v-else>{{ h.label ? tr(h.label) : tr(removeCamelCase(h.name)) }}</span>
                  </div>
                  <DynamicComponent v-if="data[h.name] && h.name !== h.title " :class="'main'" :content="convertData(data[h.name], h)"/>
                </v-col>
                <v-col class="main-border-left" @click.stop cols="2">
                  <div class="main font-weight-bold mt-n1">
                    <span>Actions</span>
                  </div>
                  <div class="text-center">
                    <v-menu>
                      <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            variant="tonal"
                            color="white"
                            elevation="0"
                            v-bind="props"
                        >
                          <v-icon color="primary">mdi-cog</v-icon>
                        </v-btn>
                      </template>
                      <v-list>
                        <v-list-item
                            v-for="(action, index) in actions.actions"
                            :key="index"
                            @click="$emit('triggerEvent', {event: action.event, data})"
                        >
                          <template v-slot:prepend>
                            <v-icon :icon="'mdi-' + action.icon"></v-icon>
                          </template>
                          <v-list-item-title> {{ action.name }}</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import DynamicComponent from "@/web/components/DynamicComponent.vue";
import QuickEdit from "@/web/components/QuickEdit";

export default {
  components: {
    QuickEdit,
    DynamicComponent
  },
  props: ['config', 'dynamic_data', 'filteredHeader'],
  data: () => ({
    languages_items: ['english', 'pachto', 'dari', 'french'],
    languages: {}
  }),
  computed: {
    actions() {
      return this.config.header.find(o => o.name === 'actions')
    }
  },
  methods: {
    borderClass(i, length) {
      if (i === 0 && length !== 1) {
        return 'rounded-top'
      } else if (i === length - 1) {
        return 'border-bottom-main rounded-bottom'
      } else if (length === 1 && i === 0) {
        return 'border-bottom-main rounded-bottom rounded-top';
      }
    },
    convertData(data, header) {
      if (Array.isArray(data)) {
        const items = data.map(item => item[header.property]);
        return items.join(', ');
      }
      if (typeof data === 'object') {
        return data[header.property];
      }
      return data;
    },
    removeCamelCase(camelCaseStr) {
      return camelCaseStr.replace(/([a-z])([A-Z])/g, '$1 $2').toLowerCase();
    }
  }
}
</script>

<style>
span.text-grey:hover {
  color: var(--main-color) !important;
  cursor: alias;
}

.no-wrap {
  white-space: nowrap !important;
}
</style>
