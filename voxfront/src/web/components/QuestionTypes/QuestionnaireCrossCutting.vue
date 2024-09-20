<template>
  <v-row class="mt-2 pa-0">
    <v-col class="pa-0">
      <div class="text-left mb-3 mt-3 white-bottom-border">
        <div class="pa-7">
          <v-row class="pb-0">
            <v-col>
              <v-autocomplete
                  v-model="config.questionnaire.id"
                  :items="questionnairesList"
                  append-inner-icon="mdi-arrow-down"
                  bg-color="primary"
                  class="rounded"
                  density="compact"
                  item-color="primary"
                  item-title="name"
                  item-value="id"
                  label="Questionnaires List"
                  menu-icon=""
                  rounded-shaped
              ></v-autocomplete>
            </v-col>
            <v-col>
              <v-autocomplete
                  v-model="config.questionnaire.questions"
                  :items="questionsList"
                  append-inner-icon="mdi-arrow-down"
                  chips
                  clearable
                  item-title="label"
                  item-value="id"
                  label="Quetions List"
                  menu-icon=""
                  multiple
              ></v-autocomplete>
            </v-col>
          </v-row>

        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "QuestionnaireCrossCutting",
  watch: {
    'config.questionnaire.id': {
      handler() {
        if ( this.config &&  this.config.questionnaire)
        {
          this.config.questionnaire.questions = []
        }
      },
    },
  },
  computed: {
    questionnairesList() {
      return this.$questionnaire_store.questionnairesList.filter((q) => q.id !== this.$questionnaire_store.currentQuestionnaire.id);
    },
    questionsList() {
      const questionnaire = this.$questionnaire_store.questionnairesList.find(q => q.id === this.config.questionnaire.id);

      return questionnaire ? questionnaire.sections.flatMap(section => section.questions) : [];
    },
    config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (currentQuestion && !currentQuestion.config.questionnaire) {
          currentQuestion.config.questionnaire = {
            id: null,
            questions: [],
          };
        }
        return  currentQuestion ? currentQuestion.config : {} ;
      },
      set(config) {
          this.$questionnaire_store.currentQuestion.config = config;

      }
    },

  },
  methods: {},

};
</script>
