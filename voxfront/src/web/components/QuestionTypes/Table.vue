<template>
  <div>
    <v-row>
      <DynamicList v-slot="slotProps" v-model:list="config.table.columns" :item-name="'column'"
                   :item-value="table.column" :sortable="true">
        <Translatable v-model="config.table.columns[slotProps.index]" :rows-number="1"/>
      </DynamicList>
    </v-row>
    <v-row>
      <DynamicList v-slot="slotProps" v-model:list="config.table.rows" :item-name="'row'" :item-value="table.row"
                   :sortable="true">
        <Translatable v-model="config.table.rows[slotProps.index]" :rows-number="1"/>
      </DynamicList>
    </v-row>
    <h3 class="mb-2">Table Visualization</h3>
    <QuestionTable :question="this.$questionnaire_store.currentQuestion"></QuestionTable>
  </div>
</template>
<script>
import DynamicList from "@/web/components/DynamicList.vue";
import Translatable from "@/web/components/Translatable.vue";
import QuestionTable from "@/mobile/components/questions_types/QuestionTable.vue";

export default {
  name: "Table",
  components: {
    QuestionTable,
    Translatable,
    DynamicList
  },
  data() {
    return {};
  },
  computed: {
    table() {
      const currentLanguage = this.$utils.currentLanguage().code;
      return {
        row: JSON.stringify({[currentLanguage]: "new row"}),
        column: JSON.stringify({[currentLanguage]: "new column"}),
      };
    },
    config: {
      get() {
        const config = this.$questionnaire_store.currentQuestion.config;
        if (!config.table) {
          config.table = {
            rows: [],
            columns: [],
          };
        }
        return config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      }
    },
  }
};
</script>
