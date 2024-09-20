<template>
  <v-form v-if="questionnaire" class="quick_edit" @submit.prevent="saveQuestionnaire">
    <v-card color="transparent" elevation="0">
      <v-card-title class="pl-0 pr-0 text-capitalize">{{ tr("Questionnaire's Quick Edit") }}: {{ questionnaire.name }}
      </v-card-title>
      <v-card-text class="pl-0 pr-0 pt-0 pb-5">
        <v-row class="pa-0">
          <v-col class="pb-0">
            <span>{{ tr('Name of questionnaire') }}</span>
            <translatable @click.stop v-model="questionnaire.name" placeholder="Questionnaire Name"
                          :languages="$store.home_page_info.languages" :rows-number="1"/>
          </v-col>
          <v-col class="pb-0">
            <span>Languages</span>
            <v-autocomplete
                v-model="questionnaire.languages"
                :items="languages_items"
                :rules="rules.languages"
                chips
                class="special_autocomplete"
                closable-chips
                item-title="name"
                multiple
                return-object
                variant="solo"
            >
              <template v-slot:append>
                <div class="ml-2 mr-2 cursor_pointer main-border pa-2 border-8 border-bottom-main"
                     @click="questionnaire.languages = languages_items">
                  <v-icon class="mr-1">mdi-check-all</v-icon>
                  Select All
                </div>
              </template>
            </v-autocomplete>
          </v-col>
        </v-row>
        <v-row class="pa-0">
          <v-col class="pb-0">
            <span>{{ tr('Type of questionnaire') }}</span>
            <v-autocomplete
                v-model="questionnaire.questionnaireType"
                :items="questionnaire_types_items"
                :rules="rules.qTypes"
                class="special_autocomplete"
                closable-chips
                item-title="label"
                return-object
                variant="solo"
            ></v-autocomplete>
          </v-col>
          <v-col class="pb-0">
            <span>{{ tr('Project') }}</span>
            <v-autocomplete
                v-model="questionnaire.project"
                :items="projects_items"
                :rules="rules.projects"
                class="special_autocomplete"
                closable-chips
                :item-title="getName"
                return-object
                variant="solo"
            ></v-autocomplete>
          </v-col>
        </v-row>
        <v-row class="pa-0">
          <v-col class="pb-0">
            <span>{{ tr('User List') }}</span>
            <v-autocomplete
                v-model="questionnaire.users"
                :items="users_items"
                chips
                class="special_autocomplete"
                closable-chips
                item-title="userName"
                item-value="id"
                multiple
                return-object
                variant="solo"
            >
              <template v-slot:append>
                <div class="ml-2 mr-2 cursor_pointer main-border pa-2 border-8 border-bottom-main"
                     @click="questionnaire.users = users_items">
                  <v-icon class="mr-1">mdi-check-all</v-icon>
                  Select All
                </div>
              </template>
            </v-autocomplete>
          </v-col>
          <v-col v-if="questionnaire.id" class="pb-0">
            <span>{{ tr('Unique token') }}</span>
            <v-text-field
                v-model="questionnaire.token"
                class="special_input"
                variant="solo"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <span>{{ tr('Email Configuration') }}</span>
            <v-text-field v-model="questionnaire.emailConfiguration"
                          :placeholder="tr('Email Configuration')"
                          :rules="rules.email"
                          class="special_input"
                          variant="solo"
            ></v-text-field>
          </v-col>
          <v-col>
            <span>{{ tr('Admins List') }}</span>
            <v-autocomplete
                v-model="questionnaire.admins"
                :items="users_items"
                chips
                class="special_autocomplete"
                closable-chips
                item-title="userName"
                multiple
                return-object
                variant="solo"
                item-value="id"
            >
            </v-autocomplete>
          </v-col>
        </v-row>
        <v-row>
          <v-col class="pb-0">
            <span>{{ tr('Metadata collected') }}</span>
            <v-autocomplete
                class="metadata"
                v-model="questionnaire.metadatas"
                :items="$questionnaire_store.questionnaire_page_info.metadata"
                :rules="rules.metadatas"
                chips
                closable-chips
                color="blue"
                item-title="label"
                multiple
                return-object
                variant="solo"
            >
              <template v-slot:append>
                <div class="ml-2 mr-2 cursor_pointer main-border pa-2 border-8 border-bottom-main"
                     @click="selectAll()">
                  <v-icon class="mr-1">mdi-check-all</v-icon>
                  Select All
                </div>
              </template>
            </v-autocomplete>
          </v-col>
        </v-row>
        <v-row class="bg-white rounded-8 pa-2 pl-10 text-center">
          <v-col class="pa-0 text-white">
            <v-checkbox
                v-model="questionnaire.requestAuthorization"
                hide-details
                label="Request Authorization"
            ></v-checkbox>
          </v-col>
          <v-col class="pa-0">
            <v-checkbox
                v-model="questionnaire.comment"
                hide-details
                label="Comment"
            ></v-checkbox>
          </v-col>
          <v-col class="pa-0">
            <v-checkbox
                hide-details
                v-model="questionnaire.enableGalleryPhoto"
                label="Enable Galley Photo"
            ></v-checkbox>
          </v-col>
               <v-col class="pa-0">
            <v-checkbox
                hide-details
                v-model="questionnaire.createDataPoint"
                label="Create datapoint"
            ></v-checkbox>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <span>{{ tr('Welcome Text') }}</span>
            <translatable @click.stop v-model="questionnaire.welcomeText" placeholder="Welcome Text"
                          :languages="$store.home_page_info.languages" :rows-number="4"/>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions v-if="questionnaire.id" class="pl-0 pb-0">
        <v-btn class="main-bg text-white text-capitalize pl-7 pr-7" elevation="0" type="submit">
          {{ tr('Update') }}
        </v-btn>
        <v-btn class="bg-grey text-white text-capitalize pl-7 pr-7" elevation="0" @click="cancel_quick_edit">
          {{ tr('Cancel') }}
        </v-btn>
      </v-card-actions>
      <v-row v-if="!questionnaire.id">
        <v-col class="text-center">
          <v-btn class="general-btn" type="submit">
            <v-icon>mdi-check</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-card>
  </v-form>
</template>
<script>
import Translatable from "@/web/components/Translatable.vue";

export default {
  name: 'QuickEdit',
  components: {Translatable},
  data: () => ({}),
  computed: {
    rules() {
      let obj = {
        languages: [
          value => {
            if (value && value.length) {
              const currentLanguage = this.$utils.currentLanguage().name;
              const languages = value.map(language => language.name);
              const included = languages.includes(currentLanguage)
              if (included) {
                return true
              } else {
                return 'The default language was not included .'
              }
            }
            return 'You must choose a language at least + default language must be selected.'
          },
        ],
        qTypes: [
          value => {
            if (value) return true
            return 'You must choose a type at least.'
          },
        ],
        projects: [
          value => {
            if (value) return true
            return 'You must choose a project'
          },
        ],
        metadatas: [
          value => {
            if (value) return true
            return 'You must choose metadatas'
          },
        ],
        email: [
          value => {
            if (!value) {
              return 'Please enter an email address';
            } else if (!/^\S+@\S+\.\S+$/.test(value)) {
              return 'Please enter a valid email address';
            } else {
              return true;
            }
          }
        ],
      }
      console.log(obj)
      return obj
    },
    questionnaire() {
      return this.$questionnaire_store.currentQuestionnaire;
    },
    users_items() {
      return this.$store.home_page_info.users;
    },
    languages_items() {
      return this.$store.home_page_info.languages;
    },
    questionnaire_types_items() {
      return this.$questionnaire_store.questionnaire_page_info.questionnaireTypes;
    },
    projects_items() {
      return this.$questionnaire_store.questionnaire_page_info.projects;
    }
  },
  created() {
    /*this.$store.getHomePageInfo(false);*/
  },
  methods: {
    getName(param) {
      return param ? this.$utils.getLabel(param.name) : ''
    },
    selectAll() {
      this.questionnaire.metadatas = this.$questionnaire_store.questionnaire_page_info.metadata
    },
    cancel_quick_edit() {
      delete this.$questionnaire_store.currentQuestionnaire.quickEdit;
      this.$questionnaire_store.currentQuestionnaire = {};
    },
    async saveQuestionnaire(event) {
      const result = await event;
      if (result && result.valid) {
        delete this.$questionnaire_store.currentQuestionnaire.quickEdit
        this.$questionnaire_store.save(this.questionnaire, true);
        this.$emit('update:newQuestDialog', false);
      }
    }
  }
}
</script>
