<template>
  <div class="primary-text">
    <p class="text-left">{{ label }}</p>
    <v-autocomplete
        v-model="model.targetQuestion"
        :label="tr(label)"
        :items="questionsList"
        append-inner-icon="mdi-arrow-down"
        item-color="primary"
        item-title="label"
        item-value="id"
        menu-icon=""
        return-object
        clearable
        @click="checkQuestionsId()"
    >
    </v-autocomplete>
     <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-card-title class="secondary-bg main">Note</v-card-title>
        <v-card-text>To select a Target the questionnaire needs to be saved first.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="dialog = false" color="warning">Cancel</v-btn>
          <v-btn @click="$questionnaire_store.save(), dialog = false" color="primary">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ['model', 'label', 'name'],
  data() {
    return {
      dialog: false
    };
  },
  computed: {
    questionsList() {
      let questions = []
      this.$questionnaire_store.currentQuestionnaire.sections.forEach((s) => {
        s.questions.forEach(q => {
          if (q.label && q.id && (this.$questionnaire_store.currentQuestion.id !== q.id)) {
            questions.push({label: this.$utils.getLabel(q.label), id: q.id})
          }
        })
      })
      return questions
    },
  },
  created() {
    if (this.model && this.model.targetQuestion) {
      this.model.targetQuestion.label = this.$utils.getLabel(this.model.targetQuestion.label)
    }
  },
  methods: {
    checkQuestionsId() {
      this.$questionnaire_store.currentQuestionnaire.sections.forEach((s) => {
        s.questions.find(q => {
          if (typeof q.id === "undefined") {
            this.dialog = true
          }
        })
      })
    },
  }
}
</script>