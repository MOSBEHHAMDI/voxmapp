<template>
  <div>
    <v-container fluid>
      Availability
      <v-checkbox
          v-for="(r, i) in products"
          density="compact"
          :key="i"
          v-model="selected"
          :value="r"
          :label="`Product: ${r}`"
          @change="setValue"
      ></v-checkbox>
    </v-container>
  </div>
</template>

<script>
export default {
  props: ['question', 'modelValue'],
  emits: [
    "update:modelValue"
  ],
  computed: {
    selected() {
        return this.modelValue ? this.extractNumbersFromString(this.modelValue) : []
      }
  },
  data() {
    return {
      selected: [],
      products: []
    };
  },
  created() {
    this.initQuestion()
  },
  methods: {
    setValue(){
      let result = []
      this.products.forEach((p, i) => {
        result.push(p + (this.selected.includes(p) ? '' : ' not') + ' available')
      })
      this.$emit("update:modelValue", result.join('|'))
    },
    extractNumbersFromString(inputString) {
      const regex = /\d+/g;
      const numbersArray = inputString.match(regex);
      return numbersArray.map(Number);
    },
    initQuestion() {
      for (let i = 0; i < this.question.config.randomProduct.nb_products; i++) {
        let x = Math.floor((Math.random() * this.question.config.randomProduct.end_interval) + this.question.config.randomProduct.begin_interval);
        this.products.push(x)
      }
    }
  }
}
</script>

<style>
#question div.v-input__slot > label {
  margin-left: 60px !important;
}
</style>