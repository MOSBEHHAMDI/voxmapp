<template>
  <v-form @submit.prevent="submit">
    <v-card class="pa-0" color="transparent" elevation="0">
      <v-card-text class="pa-0">
        <div class="questionnaire-main-bg h-100 pa-5 text-left">
          <div class="text-center font-weight-bold">Total Questions: {{ totalQuestions }}</div>
          <div v-for="section in $store.questionnaireToPreview.sections" :key="section.id">
            <div class="font-weight-bold section_title text-capitalize mt-5 sibling bg-white mb-2 pa-3 border-10">
              {{ tr('Section') }}: {{ $utils.getLabel(section.name) }}
            </div>
            <div class="mt-5 sibling bg-white mb-2 pa-3 border-10"
                 v-for="question in section.questions" :key="question.id">
              <span class="text-capitalize">{{ $utils.getLabel(question.label) }}</span>
              <BaseQuestion :question="question" :data_answers="data_answers" class="mt-3"></BaseQuestion>
            </div>
          </div>
        </div>
      </v-card-text>
      <v-card-actions class="questionnaire-main-bg pl-5 pr-5">
        <v-btn color="orange-accent-2" variant="outlined" :disabled="webApp" @click="$router.push('/view_data_point')">Cancel</v-btn>
        <v-spacer></v-spacer>
        <v-btn
            :disabled="webApp"
            color="orange-accent-2"
            variant="flat"
            class="text-white"
            text="Save"
            type="submit"
        ></v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
import BaseQuestion from "@/mobile/views/BaseQuestion.vue";

export default {
  data() {
    return {
      test: "test",
      data_answers: {}
    };
  },
  created() {
    this.data_answers = JSON.parse(JSON.stringify(this.$questionnaire_store.survey_result))
  },
  computed: {
    webApp(){
      return import.meta.env.VITE_APP_ENV === 'web'
    },
    totalQuestions() {
      return this.$utils.totalQuestions(this.$store.questionnaireToPreview)
    }
  },
  methods: {
    async submit(event) {
      const result = await event;
      if (result.valid) {
        this.$questionnaire_store.survey_result = this.data_answers
        this.$questionnaire_store.postSurvey();
      }
    },
  },
  components: {
    BaseQuestion
  }
};
</script>

<style>
.section_title {
  color: #854f03 !important;
}

.questionnaire-main-bg {
  background: #FAECD9;
}

.h-100 {
  height: 100% !important;
}

.border-10 {
  border-radius: 10px;
}
</style>
