<template>
  <div style="max-width: 100% !important;">
    <table class="mb-5">
      <thead>
      <tr>
        <th>{{ columns[0] }}</th>
        <th v-for="(header, index) in columns.slice(1)" :key="index">{{ header }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, rowIndex) in tableStructure" :key="rowIndex">
        <td v-for="(r, colIndex) in (row)" :key="r" style="padding: 0 !important;">
          <div v-if="colIndex === '#'">{{ rowIndex }}</div>
          <div v-else style="width: 100% !important;" class="pl-2">
            <v-text-field v-model="dataAnswer[rowIndex][colIndex]" style="width: 100% !important;" density="compact"
                          variant="underlined" hide-details>
            </v-text-field>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

export default {
  name: "QuestionTable",
  data() {
    return {
      editTable: false,
      columns: {},
      dataAnswer: {},
      tableStructure: {}
    };
  },
  props: ['question', 'modelValue', 'general_rules'],
  emits: [
    "update:modelValue"
  ],
  created() {
    let cols = ['#']
    this.question.config?.table?.columns.map(c => {
      const label = this.$utils.getLabel(c);
      cols.push(label)
    })
    this.columns = cols
    this.dataAnswer = JSON.parse(JSON.stringify(this.modelValue ?? {}))
    this.question.config?.table?.rows.map(r => {
      const label = this.$utils.getLabel(r);
      if (typeof (this.dataAnswer[label]) === 'undefined') {
        this.dataAnswer[label] = {};
      }
      for (let i = 0; i < this.columns.length; i++) {
        if (typeof (this.dataAnswer[label][this.columns[i]]) === 'undefined') {
          this.dataAnswer[label][this.columns[i]] = ''
        }
      }
    })
    this.tableStructure = JSON.parse(JSON.stringify(this.dataAnswer))
  },
  watch: {
    dataAnswer: {
      handler(newVal, oldVal) {
        this.$emit("update:modelValue", JSON.stringify(newVal))
      },
      deep: true
    }
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
  background: white;
}

th {
  color: #fff;
  background-color: #E5992CFF;
  padding-top: 2% !important;
  padding-bottom: 2% !important;
  padding-left: 5%;
}

table tr:nth-child(even) {
  background-color: #fff2e3;
}

table {
  overflow: hidden;
  border: solid 1px #E5992CFF
}

table td {
  color: gray;
  line-height: 1.4;
  padding-left: 5%;
  border-right: #fff2e3 2px solid;
}
</style>
