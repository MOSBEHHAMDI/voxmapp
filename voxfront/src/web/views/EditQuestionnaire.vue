<template>
  <div v-resize="onResize">
    <div v-if="windowSize.x <= 1100" class="justify-center pl-2 pr-2 text-capitalize text-center mt-10">
      <v-alert
          border
          prominent
          type="warning"
          variant="outlined"
      >
        Questionnaire cannot be edited on smaller screens.
      </v-alert>
    </div>
    <div v-else>
      <h2 class="main text-left pa-0 ml-0">Edit Questionnaire</h2>
      <v-form @submit.prevent="validate">
        <v-row class="pa-0" no-gutters>
          <v-col v-if="questionnaire &&  sections" class="overflow-cols">
            <Container class="h-full"
                       group-name="sections"
                       dragHandleSelector=".section-drag-handle"
                       tag="div"
                       @drop="onDrop">
              <Draggable class="text-left white-bottom-border main-bg"
                         v-for="(section, indexS) in  sections" :key="indexS">
                <Section       @handleSectionConfirm="handleSectionConfirm" :section="section" :section-number="indexS"/>
              </Draggable>
            </Container>
            <div class="pa-2 main-bg mb-2">
              <v-btn block class="white-dashed-border text-white" color="transparent" elevation="0"
                     @click="addNewSection">
                Add Section
              </v-btn>
            </div>
            <v-row>
              <v-col class="text-center mb-4">
                <v-btn class="general-btn" type="submit">
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-col>
          <v-col class="secondary-bg main pa-3 overflow-cols">
            <edit-question/>
          </v-col>
        </v-row>
      </v-form>
    </div>
  </div>
</template>

<script>
import Section from "@/web/components/Section";
import EditQuestion from "@/web/components/EditQuestion.vue";
import {Container, Draggable} from 'vue3-smooth-dnd'

export default {
  name: "EditQuestionnaire",
  components: {EditQuestion, Section, Container, Draggable},
  data: () => ({
    selectedQuestionId: null,
    windowSize: {
      x: 0,
      y: 0,
    }
  }),
  created() {
    this.onResize();
    this.$dataPoint_store.getDataPointColumns()
  },
  computed: {
    questionnaire: {
      get() {
        return this.$questionnaire_store.currentQuestionnaire;
      },
      set(newQuestionnaire) {
        this.$questionnaire_store.currentQuestionnaire = newQuestionnaire
      }
    }
    ,
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
    onDrop(dropResult) {
      this.sections = this.$questionnaire_store.onDrop(this.sections, dropResult)
    },
    async validate(event) {
      const result = await event;
      if (result.valid) {
        this.$questionnaire_store.save();
      }
    },
    handleSectionConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this section ?',
      }, () => {
        const index = this.questionnaire.sections?.findIndex(s => s.id === id);
        if (index !== -1) {
          const currentSection = this.questionnaire.sections[index];
          const q = currentSection.questions.find(q => q.code === this.$questionnaire_store.currentQuestion?.code);
          if (q) {
            this.$questionnaire_store.currentQuestion = null;
          }
          this.questionnaire.sections.splice(index, 1);
        } else {
          console.error(`Section with ID ${id} not found.`);
        }
      })
    },
    addNewSection() {
      const length = this.questionnaire.sections ? this.questionnaire.sections.length : 0;
      const currentLanguage = this.$utils.currentLanguage().code;
      const obj = {[currentLanguage]: ""};
      const newSection = {
            "name": JSON.stringify(obj),
            "questions": [
              this.$utils.newQuestion(1)
            ],
            "orderBy": length,
          }
      ;
      this.questionnaire.sections.push(newSection);
    },
    onResize() {
      this.windowSize = {x: window.innerWidth, y: window.innerHeight}
    },
  },
}
</script>

<style>

</style>