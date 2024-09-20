<template>
  <v-card v-if="currentQuestion" color="transparent" elevation="0">
    <v-row class="mt-2 pa-0">
      <v-col class="pa-0">
        <div class="text-left mb-3 mt-3 white-bottom-border pl-7 pr-7">
          <v-row class="pb-0">
            <v-col class="ma-auto pl-0 pr-0">
              <v-autocomplete
                  v-model="currentQuestion.questiontype"
                  :items="questiontypes"
                  append-inner-icon="mdi-arrow-down"
                  bg-color="primary"
                  class="rounded"
                  density="compact"
                  item-color="primary"
                  :item-title="translateLabel"
                  placeholder="Type"
                  menu-icon=""
                  return-object=""
                  rounded-shaped
              >
              </v-autocomplete>
            </v-col>
            <v-col class="d-flex flex-row-reverse" cols="6">
              <v-checkbox
                  v-model="currentQuestion.enabled"
                  color="primary"
                  label="Enabled"
              ></v-checkbox>
              <v-checkbox
                  v-model="currentQuestion.mandatory"
                  color="primary"
                  label="Required"
              ></v-checkbox>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <TargetField :label="'Go To'" :model="currentQuestion"></TargetField>
            </v-col>
            <v-col cols="3">
              <div class="primary-text">
                <p class="text-left">{{tr('Mapped by')}}</p>
                <v-select
                    v-if="$questionnaire_store.currentQuestionnaire.createDataPoint"
                    v-model="currentQuestion.config.mappedBy"
                    :items="mappedByItems"
                    append-inner-icon="mdi-arrow-down"
                    clearable
                    menu-icon=""
                ></v-select>
              </div>
              </v-col>
              <v-col cols="3">
                <div class="primary-text">
                  <p class="text-left">Last Question</p>
                  <v-checkbox v-model="currentQuestion.lastQuestion"></v-checkbox>
                </div>
              </v-col>
          </v-row>

          <translatable v-model="currentQuestion.label" :languages="$questionnaire_store.languages" :rows-number="4"/>
          <component-loader :componentName="questionType" :question="currentQuestion"></component-loader>
        </div>
      </v-col>
    </v-row>
  </v-card>
</template>
<script>
import TargetField from "@/web/components/QuestionTypes/TargetField.vue";
import Translatable from "@/web/components/Translatable.vue";

export default {
  name: "EditQuestion",
  components: {
    Translatable,
    TargetField,
    ComponentLoader: {
      props: {
        componentName: {
          type: String,
          required: true
        },
        question: {
          type: Object,
          required: true
        }
      },
      data() {
        return {
          component: null,
        };
      },
      watch: {
        componentName: {
          async handler(name) {
            if (name) {
              const componentModule = await import(`@/web/components/QuestionTypes/${name}.vue`);
              this.component = componentModule.default || componentModule;
            }
          },
          immediate: true
        }
      },
      template: '<component :is="component" v-if="component" :question="question"></component>'
    }
  },
  computed: {
    mappedByItems(){
      return this.$dataPoint_store.dataPointColumns;
    },
    questionType() {
      return this.currentQuestion.questiontype.label.toString()
    },
    questiontypes() {
      const types = this.$questionnaire_store.questionnaire_page_info.questionTypes;
      return types ?? {};
    },
    currentQuestion: {
      get() {
        return this.$questionnaire_store.currentQuestion;
      },
      set(question) {
        this.$questionnaire_store.currentQuestion = question;
      }
    }
  },
  methods: {
    translateLabel(item) {
      let types = {
        'FacilityList': 'Facility List',
        'QuestionnaireCrossCutting': 'Questionnaire',
        'RandomProducts': 'Random Products',
        'GeoPoint': 'Geo Point',
        'ListOfEntries': 'List Of Entries',
        'DropDowns': 'Drop Down Menu',
      }
      return types[item.label] ?? item.label
    }
  },
};
</script>
