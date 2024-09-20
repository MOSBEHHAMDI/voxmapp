<template>
  <div>
    <v-row>
      <v-col class="text-left">
        <h2 class="main">{{ tr('Questionnaire') }}</h2>
      </v-col>
    </v-row>
    <v-row>
      <v-col class="text-left pt-0" md="2">
        <v-card color="transparent" elevation="0" height="320" @click="questionnaire_possiblities = true">
          <v-tooltip :text="tr('Click to add a new questionnaire')">
            <template v-slot:activator="{ props }">
              <div class="main-bg h-100 border-8 pt-3 new_questionnaire_btn" v-bind="props">
                <div class="pl-3 pr-3">
                  <span style="font-size: x-large">{{ tr('New Questionnaire') }}</span>
                </div>
                <img alt="questionnaire" class="w-100 mt-3" src="../../assets/img.png">
              </div>
            </template>
          </v-tooltip>
        </v-card>
        <v-card class="mt-6 pa-4 secondary-bg border-8" color="transparent" elevation="0" @click="questionnaireImport">
          <span class="main font-weight-bold">{{ tr('Import') }}</span>
          <input
              ref="uploader"
              class="d-none"
              type="file"
              @change="onFileChanged"
          ></v-card>
        {{ selectedFile ? selectedFile.name : '' }}
      </v-col>
      <v-col class="secondary-bg pa-0">
        <List v-if="questionnaires" :config="config" :dynamic_data="questionnaires"
              @triggerEvent="triggerEvent"
        ></List>
      </v-col>
    </v-row>
    <v-dialog v-model="questionnaire_possiblities" width="900">
      <v-card class="pa-0" elevation="0">
        <v-card-title class="bg-blue-darken-2 text-white">
          Create New Questionnaire
          <v-icon class="float-end" @click="questionnaire_possiblities = false">mdi-close</v-icon>
        </v-card-title>
        <v-card-text class="pa-0">
          <div class="bg-grey-lighten-3 pa-5">
            Choose one of the options to continue. You will be prompted to enter name and other details in further
            steps.
          </div>
          <div class="pl-15 pr-15 pt-10 pb-10 text-center">
            <v-row class="pl-15 pr-15 text-center">
              <v-col @click="openCreateModal()">
                <v-card color="grey-lighten-3" class="pa-7">
                  <v-icon class="mb-2">mdi-pencil</v-icon>
                  <p>Build from scratch</p>
                </v-card>
              </v-col>
              <v-col>
                <v-card color="grey-lighten-3" class="disabled pa-7">
                  <v-icon class="mb-2">mdi-upload-outline</v-icon>
                  <p>Upload XLSForm</p>
                </v-card>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="new_quest_dialog"
              transition="dialog-bottom-transition"
              width="900">
      <v-card class="main-bg text-capitalize text-white pa-5">
        <v-card-title class="text-center">{{ tr('New Questionnaire') }}</v-card-title>
        <v-card-text>
          <QuickEdit @triggerEvent="triggerEvent" @update:newQuestDialog="hideNQDialogue"></QuickEdit>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import List from "@/web/components/DataList";
import QuickEdit from "@/web/components/QuickEdit";

export default {
  components: {QuickEdit, List},
  created() {
    this.$questionnaire_store.currentQuestionnaire = null
    this.$questionnaire_store.currentQuestion = null
    this.$questionnaire_store.getQuestionnairesInfo()
    this.$questionnaire_store.getQuestionnairesList()
  },
  computed: {
    config() {
      return {
        title: 'Questionnaires',
        header: [
          {
            name: 'name',
            label: 'Name',
            visible: true,
            title: 'name',
          },
          {
            name: 'welcomeText',
            visible: false,
            label: 'Welcome Text'
          },
          {
            name: 'creationDate',
            label: 'Creation Date',
            visible: true
          },
          {
            name: 'updateDate',
            visible: true,
            label: 'Update Date',
          },
          {
            name: 'questionnaireType',
            visible: true,
            label: 'Questionnaire Type',
            property: 'label',
          },
          {
            name: 'users',
            visible: true,
            label: 'Users',
            property: 'userName',
          },
          {
            name: 'actions',
            actions: [
              {name: 'Edit', icon: 'pencil-outline', event: 'editQuestionnaire'},
              {name: 'Quick Edit', icon: 'application-edit-outline', event: 'quickEditQuestionnaireAction'},
              {name: 'Delete', icon: 'close', event: 'removeQuestionnaire'},
              {name: 'Preview', icon: 'cellphone-text', event: 'previewQuestionnaire'},
              {name: 'Dashboards', icon: 'view-dashboard-outline', event: 'redirectToDashboardQuestionnaire'},
              {name: 'Export', icon: 'cloud-download', event: 'exportQuestionnaire'},
            ],
            visible: false,
            label: 'Actions',
          }
        ],
        save_row: 'saveQuestionnaire'
      }
    },
    questionnaires: {
      get() {
        return this.$questionnaire_store.questionnairesList ?? [];
      },
      set(list) {
        this.$questionnaire_store.questionnairesList = list
      }
    },
  },
  data: () => ({
    isSelecting: false,
    selectedFile: null,
    questionnaire_possiblities: false,
    new_quest_dialog: false,
  }),
  methods: {
    hideNQDialogue(newValue) {
      console.log('hideNQDialogue called with value:', newValue);
      this.new_quest_dialog = newValue;
    },
    openCreateModal() {
      this.questionnaire_possiblities = false
      let user_language = this.$store.home_page_info.languages.find((e) => e.code === this.$utils.currentLanguage().code)
      this.$questionnaire_store.currentQuestionnaire = {
        name: null,
        welcomeText: null,
        project: null,
        sections: [],
        users: [],
        languages: [user_language],
        questionnaireType: null,
        metadatas: [],
      };
      this.new_quest_dialog = true
    },
    triggerEvent(params) {
      if (this[params.event]) {
        this[params.event](params.data)
      } else {
        console.log(params.event, ' method does not exist in questionnaire list.')
      }
    },
    saveQuestionnaire(questionnaire) {
      console.log(questionnaire)
    },
    quickEditQuestionnaireAction(questionnaire) {
      this.$questionnaire_store.currentQuestionnaire = questionnaire
      this.$questionnaire_store.currentQuestionnaire.quickEdit = true
    },
    editQuestionnaire(questionnaire) {
      this.$questionnaire_store.currentQuestion = null;
      this.$questionnaire_store.currentQuestionnaire = questionnaire;
      this.$router.push({path: '/questionnaire/edit/'});
    },
    removeQuestionnaire(questionnaire) {
      this.handleConfirm(questionnaire.id);
    },
    handleConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure you want to delete this questionnaire?'
      }, () => {
        this.$questionnaire_store.deleteQuestionnaire(id)
      })
    },
    redirectToDashboardQuestionnaire(questionnaire) {
      console.log(questionnaire)
    },
    exportQuestionnaire(questionnaire) {
      this.$questionnaire_store.exportAsXLSForm(questionnaire.id);
    },
    previewQuestionnaire(questionnaire) {
      this.$store.questionnaireToPreview = questionnaire
      this.$questionnaire_store.set_new_survey_result()
      this.$router.push('/preview_questionnaire')
    },
    questionnaireImport() {
      this.isSelecting = true;
      window.addEventListener('focus', () => {
        this.isSelecting = false
      }, {once: true});
      this.$refs.uploader.click();
    },
    onFileChanged(e) {
      this.selectedFile = e.target.files[0];
      //ToDo: to be completed after
    },
  },
}
</script>

<style>
.new_questionnaire_btn {
  transition: background-color 0.3s ease;
  animation: scaleIn 0.5s ease-out 1s;
  color: white;
}

.new_questionnaire_btn:hover {
  background-color: var(--secondary-color) !important;
  color: var(--main-color) !important;
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
  }
  to {
    transform: scale(1);
  }
}
</style>
