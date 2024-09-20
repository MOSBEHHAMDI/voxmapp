<template>
  <v-expansion-panels class="pa-0">
    <v-expansion-panel class="main-bg section">
      <v-expansion-panel-title class="pt-0 pb-0">
        <v-row>
          <v-col cols="1" class="pl-0 pr-0 pt-8">
            <v-tooltip text="Drag section">
              <template v-slot:activator="{ props }">
                <v-icon v-bind="props" class="section-drag-handle mt-1" size="30" color="white">mdi-cursor-move</v-icon>
              </template>
            </v-tooltip>
          </v-col>
          <v-col cols="2" class="ma-auto">
            <h3 class="font-weight-bold text-white"> {{ tr('Section ') }}{{ sectionNumber + 1 }}</h3>
          </v-col>
          <v-col class="ma-auto pl-0 pr-0 pt-5">
            <translatable @click.stop v-model="section.name" placeholder="Section Name"
                          :languages="$store.home_page_info.languages" :rows-number="1"/>
          </v-col>
        </v-row>
      </v-expansion-panel-title>
      <v-expansion-panel-text>
        <Container :get-child-payload="getChildPayload"
                   class="h-full"
                   dragHandleSelector=".question-drag-handle"
                   group-name="questions"
                   tag="div"
                   @drop="onInnerDrop(section, $event),onDrop"
        >
          <Draggable v-for="(question, qIndex) in section.questions"
                     :key="qIndex" class="text-left mt-3 border-8 main-bg">
            <v-row class="pa-2 main secondary-bg border-8 question-container">
              <v-col class="ma-auto">
          <span>
            <span v-if="question && question.label">      <v-tooltip text="Drag question">
              <template v-slot:activator="{ props }">
              <v-icon class="ml-1 mr-1 question-drag-handle" v-bind="props">mdi-drag-variant</v-icon>
              </template>
            </v-tooltip>
              {{
                'Question ' + (qIndex + 1) + ': ' + $utils.getLabel(question.label) + ' (' +question.questiontype?.label  +')'
              }}
            </span></span>
              </v-col>
              <v-col class="text-end" cols="2">
                <v-btn color="primary" min-width="10" variant="text" width="10" @click="setCurrentQuestion(question)">
                  {{ tr('Edit') }}
                </v-btn>
                <v-btn color="primary" min-width="10" variant="text" width="10"
                       @click="handleQuestionConfirm(question.code)">
                  <v-icon>mdi-close-circle</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </Draggable>
        </Container>
        <v-row class="text-right">
          <v-col>
            <v-tooltip :text="tr('Add Question')  ">
              <template v-slot:activator="{ props }">
                <v-icon class="mr-3 text-white mt-5" v-bind="props" @click="addNewQuestion(section.id)">mdi-plus-circle
                </v-icon>
              </template>
            </v-tooltip>
            <v-tooltip :text=" tr('Delete Section') ">
              <template v-slot:activator="{ props }">
                <v-icon class=" text-white mt-5" v-bind="props" @click="$emit('handleSectionConfirm', section.id)">
                  mdi-minus-circle
                </v-icon>
              </template>
            </v-tooltip>
          </v-col>
        </v-row>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>
</template>
<script>
import EditQuestion from "@/web/components/EditQuestion.vue";
import Translatable from "@/web/components/Translatable.vue";
import {Container, Draggable} from 'vue3-smooth-dnd'

export default {
  name: "Section",
  props: ['section', 'sectionNumber'],
  components: {Translatable, EditQuestion, Container, Draggable},
  data: () => ({
    windowSize: {
      x: 0,
      y: 0,
    },
  }),
  computed: {
    sections: {
      get() {
        return this.$questionnaire_store.currentQuestionnaire.sections;
      },
      set(newVal) {
        this.$questionnaire_store.currentQuestionnaire.sections = newVal
      }
    }
  },
  methods: {
    onDrop(collection, dropResult) {
      collection = this.$utils.applyDrag(collection, dropResult)
    },
    onInnerDrop(item, dropResult) {
      const newItems = [...this.sections]
      const index = newItems.indexOf(item)
      newItems[index].questions = this.$questionnaire_store.onDrop(newItems[index].questions, dropResult)
      this.sections = newItems
    },
    getChildPayload(index) {
      return this.section.questions[index]
    },
    setCurrentQuestion(question) {
      this.$questionnaire_store.currentQuestion = question;
    },
    addNewQuestion() {
      const newQuestion = this.$utils.newQuestion(this.section.questions ? this.section.questions.length + 1 : 1);
      this.section.questions.push(newQuestion);
    },
    handleQuestionConfirm(questionCode) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this question!'
      }, () => {
        const index = this.section.questions.findIndex(q => q.code === questionCode);
        if (index !== -1) {
          this.section.questions.splice(index, 1);
          if (this.$questionnaire_store.currentQuestion && (this.$questionnaire_store.currentQuestion.code === questionCode)) {
            this.$questionnaire_store.currentQuestion = null;
          }
        } else {
          console.error(`Question with ID ${questionCode} not found.`);
        }
      })
    },
  },
}
</script>

<style>
.question-container {
  z-index: 99;
}

.v-expansion-panel-title:hover > .v-expansion-panel-title__overlay, .v-expansion-panel-title--active > .v-expansion-panel-title__overlay, .v-expansion-panel-title[aria-haspopup=menu][aria-expanded=true] > .v-expansion-panel-title__overlay {
  background: transparent;
}

.section .v-expansion-panel-title__icon {
  display: none;
}
</style>
