<template>
  <v-bottom-sheet v-model="list">
    <v-card elevation="0" rounded class="pa-2 rounded-10">
      <v-card-title>
        <v-row>
          <v-col cols="10">
             <v-text-field placeholder="search" variant="filled" clearable prepend-inner-icon="mdi-magnify"
                      density="compact"></v-text-field>
          </v-col>
          <v-col class="text-end">
            <v-icon @click="$emit('update:modelValue', false)">mdi-close</v-icon>
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-text>
        <div class="border-8 pa-3 main-border-1 mb-2" v-for="questionnaire in questionnaires" :key="questionnaire.id">
          <v-row no-gutters>
            <v-col cols="11">
              <v-card-title class="pa-0 text-wrap">{{ $utils.getLabel(questionnaire.name) }}</v-card-title>
              <v-card-subtitle class="pa-0">{{ totalQuestions(questionnaire) }} questions</v-card-subtitle>
            </v-col>
            <v-col cols="1" class="pt-3 pr-3">
              <v-icon color="grey" size="30" @click="redirectToQuestionnaire(questionnaire)">mdi-plus</v-icon>
            </v-col>
          </v-row>
        </div>
      </v-card-text>
    </v-card>
  </v-bottom-sheet>
</template>
<script>
export default {
  data: () => ({
  }),
  props: ['modelValue'],
  emits: [
    "update:modelValue"
  ],
  computed: {
    list() {
      return this.modelValue
    },
    questionnaires() {
      return this.$questionnaire_store.questionnairesListMobile ?? []
    },
  },
  methods: {
    totalQuestions(questionnaire) {
      return this.$utils.totalQuestions(questionnaire)
    },
    redirectToQuestionnaire(questionnaire) {
      this.$store.questionnaireToPreview = questionnaire
      this.$questionnaire_store.set_new_survey_result()
      this.$emit('update:modelValue', false)
      this.$router.push('/questionnaire')
    }

  }
}
</script>