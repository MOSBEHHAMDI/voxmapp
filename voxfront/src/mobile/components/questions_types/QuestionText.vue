<template>
  <v-container class="pl-1">
    <v-textarea
        name="input-7-4 questionnaire-imput"
        label="Answer"
        v-model="answer"
        variant="outlined"
        color="orange"
        auto-grow
        rows="1"
        :rules="[...general_rules, rules]"
        persistent-hint
    ></v-textarea>
  </v-container>
</template>
<script>
export default {
  data() {
    return {}
  },
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
          this.$emit("update:modelValue", value)
      }
    },
    regExp() {
      let regular_expr = this.question?.config?.text?.regExp
      if (regular_expr && regular_expr !== '') {
        regular_expr = (regular_expr.startsWith("/") && regular_expr.endsWith("/")) ? regular_expr.slice(1, -1) : regular_expr
        return new RegExp(regular_expr);
      }
      return null;
    },
    rules() {
      if (this.regExp && !this.regExp.test(this.answer)) {
        return this.tr(this.question?.config?.text?.validation_error_msg);
      }
      return true;
    }
  }
}
</script>

<style>

</style>