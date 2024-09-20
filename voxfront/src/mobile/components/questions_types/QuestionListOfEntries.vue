<template>
  <div>
    <v-select
        v-model="answer"
          :hint="`${entity}, ${answer ? answer.code : ''}`"
          :items="question.entries"
          item-text="name"
          item-title="name"
          :label="entity"
          persistent-hint
          color="orange"
          return-object
        ></v-select>
    <v-card class="pa-2 mt-5" elevation="1">
      <v-checkbox
      v-model="custom"
      :label="`Custom Value for: ${entity}`"
    ></v-checkbox>
    <v-text-field
        :disabled="!custom"
        v-model="custom_value.name"
        :label="entity"
        placeholder="Enter Value"
        ></v-text-field>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ['question', 'modelValue'],
  data() {
    return {
      value: {},
      custom: false,
      custom_value: {id: null, name: ''}
    };
  },
  computed: {
    answer: {
      get() {
        return this.modelValue ?? null
      },
      set(value) {
        this.$emit("update:modelValue", value)
      }
    },
    entriesList(){
      let result =[]
      return this.question.entries
    },
    entity(){
      return this.question.config.entry.split('\\').pop();
    }
  },
}
</script>