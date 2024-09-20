<template>
  <div>
    <Slider></Slider>
    <v-card class="pale-bg overflow-cols" elevation="0" style="height: 100vh">
      <v-tabs
          v-model="tab" color="orange"
      >
        <v-tab value="Info">Info</v-tab>
        <v-tab value="Gallery">Gallery</v-tab>
        <v-tab value="Surveys">Surveys</v-tab>
        <v-tab value="History">History</v-tab>
        <v-tab value="Tasks">Tasks</v-tab>
      </v-tabs>
      <v-card-text>
        <v-window v-model="tab">
          <v-window-item value="Info">
            <info></info>
          </v-window-item>
          <v-window-item value="Gallery">
            <galleries></galleries>
          </v-window-item>
          <v-window-item value="Tasks">
            <tasks></tasks>
          </v-window-item>
          <v-window-item value="History">
            <history></history>
          </v-window-item>
          <v-window-item value="Surveys">
            <v-img v-if="$dataPoint_store.currentDataPoint.surveys.length === 0" class="mt-10"
                   src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.rajasthanndaacademy.com%2Fassets%2Fimages%2Fno-record-found.png&f=1&nofb=1&ipt=4f7fe2f5dbac5ccd6874acbed1b3e48837f35edd75075d689867b8d6ad3cf9f8&ipo=images"></v-img>

            <div v-else>
              <div class="border-8 pa-3 main-border-1 mb-2 bg-white text-left"
                   v-for="survey in $dataPoint_store.currentDataPoint.surveys" :key="survey.id">
                <v-row no-gutters>
                  <v-col>
                    <v-card-title class="pa-0 text-wrap text-capitalize">
                      {{ getQuestionnaireName(survey.questionnaire) }}
                    </v-card-title>
                    <v-card-subtitle class="pa-0">Started At: {{
                        formatConsoleDate(new Date(survey.startDate))
                      }}
                    </v-card-subtitle>
                    <v-card-subtitle class="pa-0">Finished At: {{
                        formatConsoleDate(new Date(survey.finishDate))
                      }}
                    </v-card-subtitle>
                  </v-col>
                </v-row>
              </div>
            </div>
            <v-btn icon elevation="0" class="main-bg text-white float-btn" @click="questionnaire_list = true">
              <v-icon>mdi-chart-box-plus-outline</v-icon>
            </v-btn>
          </v-window-item>
        </v-window>
      </v-card-text>
    </v-card>
    <QuestionnaireListDialog v-model="questionnaire_list"></QuestionnaireListDialog>
  </div>
</template>

<script>
import Slider from "../components/Slider.vue";
import QuestionnaireListDialog from "../components/QuestionnaireListDialog.vue";
import Map from "@/web/components/OpenLayerMap.vue";
import Info from "@/mobile/components/info.vue";
import Galleries from "@/mobile/components/galleries.vue";
import Tasks from "@/mobile/components/tasks.vue";
import History from "@/mobile/components/history.vue";

export default {
  components: {History, Tasks, Galleries, Info, Map, Slider, QuestionnaireListDialog},
  data: () => ({
    tab: null,
    questionnaire_list: false,
  }),
  computed: {},
  methods: {
    getQuestionnaireName(questionnaire) {
      if (typeof questionnaire.name !== "undefined") {
        return this.$utils.getLabel(questionnaire.name)
      } else {
        let q = this.$questionnaire_store.questionnairesListMobile.filter(q => {
          return q.id === questionnaire.id
        })
        return this.$utils.getLabel(q[0].name)
      }
    },
    formatConsoleDate(date) {
      //todo (duplicate)check the same function in plugins.js
      let date_string = [
        date.getDate(),
        date.getMonth() + 1,
        date.getFullYear(),
      ].join('-');
      let hour_string = [
        date.getHours(),
        date.getMinutes(),
        date.getSeconds(),
      ].join(':');
      return '' + date_string + ' ' + hour_string + '';
    },
  }
}
</script>

<style>
.data-point {
  background: rgba(0, 0, 0, 0.55) !important;
  color: #ffffff !important;
}
</style>