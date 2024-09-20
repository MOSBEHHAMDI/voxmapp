<template>
  <div>
    <v-slider
        v-model="answer"
        id="slider"
        color="orange"
        step="1"
        thumb-label="always"
        v-bind:min="question.config.slider.min"
        v-bind:max="question.config.slider.max"
    >
    </v-slider>
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
        //to do validation target
        this.$emit("update:modelValue", value.toString())
      }
    }
  },
  data() {
    return {}
  },
}

</script>

<style>

</style>