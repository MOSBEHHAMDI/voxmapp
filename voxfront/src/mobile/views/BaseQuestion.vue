<template>
  <component :is="component" v-if="component" :question="question" :general_rules="rules" v-model="answer" ref="question"></component>
</template>

<script>
export default {
  props: ['question', 'data_answers'],
  data() {
    return {
      component: null,
      rules: [],
      model: 'test'
    };
  },
  computed: {
    answer: {
      get() {
        return this.data_answers.answers[this.question.id];
      },
      set(value) {
        this.data_answers.answers[this.question.id] = value;
        //todo validate target
      }
    },
  },
  async created() {
    let componentName = this.getQuestionTypeLabel(this.question.questiontype)
    const componentModule = await import(`../components/questions_types/Question${componentName}.vue`);
    this.component = componentModule.default || componentModule;
    if (this.question.mandatory) {
      this.rules.push(value => {
        return !!value || 'This field is required'
      });
    }

  },
  methods: {
    getQuestionTypeLabel(type) {
      if (typeof type.label === "undefined") {
        let questionType = this.$questionnaire_store.questionnaire_page_info.questionTypes.filter(t => t.id === type)
        return questionType[0].label
      }
      return type.label
    }
  }
}
</script>
