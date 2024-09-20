<template>
  <div>
    <v-menu :close-on-content-click="false" >
        <template v-slot:activator="{ props }">
          <v-text-field
                        :model-value="answer ? answer.toISOString().substr(0, 10) : ''"
                        append-inner-icon="mdi-calendar"
                        readonly
                        v-bind="props"
          ></v-text-field>
        </template>
        <v-date-picker v-model="answer" :max="question.config.date.max"
        :min="question.config.date.min"></v-date-picker>
      </v-menu>
  </div>
</template>
<script>
export default {
  props: ['question', 'modelValue', 'general_rules'],
  emits: [
    "update:modelValue"
  ],
  computed: {
    answer: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit("update:modelValue", value)
      }
    }
  },
}
</script>