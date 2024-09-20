<template>
  <div class="border-top-main pt-5">
    <v-row>
      <v-col>
        <v-autocomplete
            v-model="config.choice.choiceType"
            :items="[{code:0,label:'Text'},{code:1,label:'Picture'}]"
            append-inner-icon="mdi-arrow-down"
            bg-color="primary"
            class="rounded"
            density="compact"
            item-color="primary"
            item-title="label"
            item-value="code"
            label="Choices Type"
            menu-icon=""
            rounded-shaped
        ></v-autocomplete>
      </v-col>
      <v-col>
        <v-checkbox
            v-model="config.choice.multiple"
            color="primary"
            label="Multiple Choices"
            class="mt-n2"
        ></v-checkbox>
      </v-col>
    </v-row>
    <v-expansion-panels>
      <Container
          dragHandleSelector=".choice-drag-handle"
          style="width: 100%"
          tag="div"
          @drop="onDrop($event)"

      >
        <Draggable v-for="(choice, index) in choices" :key="index">
          <v-expansion-panel
              class="mb-2"
              elevation="0"
          >
            <v-expansion-panel-title>
              {{ $utils.getLabel(choice.label) }}
              <template v-slot:actions="{ expanded }">
                <v-tooltip text="Drag choice">
                  <template v-slot:activator="{ props }">
                    <v-icon class="ml-1 mr-1 choice-drag-handle" color="primary" v-bind="props">mdi-drag-variant
                    </v-icon>
                  </template>
                </v-tooltip>
                <v-tooltip text="Delete choice">
                  <template v-slot:activator="{ props }">
                    <v-icon color="primary" icon="mdi-close-circle" v-bind="props" @click="handleConfirm(choice.id)"></v-icon>
                  </template>
                </v-tooltip>
              </template>
            </v-expansion-panel-title>
            <v-expansion-panel-text class="pl-5 pr-5 pb-0">
              <div>
                <translatable v-if="config.choice.choiceType !== 1"
                              v-model="choice.label"
                              :languages="$questionnaire_store.languages"
                              :rows-number="1" :variant="'tonal'"/>
                <v-card v-else
                        class="mx-auto main-border border-bottom-main text-center pa-0 mb-5"
                        elevation="0"
                >
                  <v-card-text class="pb-0">
                    <img :src="uploads[choice.id]?.src ?? uploads[choice.id]" height="100">
                  </v-card-text>
                  <v-card-actions>
                    <v-btn v-if="uploads[choice.id]" color="danger" variant="tonal" @click="removePicture(index, id)">
                      Remove Picture
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn :block="!uploads[choice.id]" class="main" variant="tonal" @click="openFileInput">Upload
                      Image
                    </v-btn>
                    <input ref="fileInput" accept="image/png, image/jpeg" style="display: none;" type="file"
                           @change="onFileChange($event, choice.id, index)">
                  </v-card-actions>
                </v-card>
              </div>
              <v-row class="pa-0">
                <v-col class="pa-0 mt-n2">
                  <v-checkbox
                      v-model="choice.numberOption"
                      color="primary"
                      hide-details
                      label="Number Option"
                  ></v-checkbox>
                </v-col>
                <v-col class="pa-0">
                  <v-text-field v-model="choice.code"
                                :label="tr('Code')"
                                class="rounded"
                                density="compact"
                                :rules="[global_rules.required]"
                                hide-details>
                  </v-text-field>
                </v-col>
              </v-row>
              <TargetField :label="'Target'" :model="choice" :name="'choice'"></TargetField>
            </v-expansion-panel-text>
          </v-expansion-panel>
        </Draggable>
      </Container>

    </v-expansion-panels>
    <v-btn
        block
        elevation="0"
        class="main secondary-bg border-dashed"
        prepend-icon="mdi-plus-circle"
        @click="addNewChoice()"
    >
      New choice
    </v-btn>
  </div>
</template>

<script>
import Translatable from "@/web/components/Translatable.vue";
import TargetField from "@/web/components/QuestionTypes/TargetField.vue"
import {Container, Draggable} from 'vue3-smooth-dnd'
export default {
  name: "Choices",
  components: {
    Translatable,
    TargetField,
    Container,
    Draggable,
  },
  computed: {
    choices: {
      get() {
        return this.$questionnaire_store.currentQuestion.choices
      },
      set(newVal) {
        this.$questionnaire_store.currentQuestion.choices = newVal
      }
    },
    config: {
      get() {
        let config = this.$questionnaire_store.currentQuestion.config;
        if (!config.choice) {
          config.choice = {
            multiple: false,
            choiceType: 0,
          };
        }
        return config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      }
    },
  },
  data: () => ({
    upload: {},
    uploads: [],
   }),
  created() {
    this.getFiles()
  },
  methods: {
    onDrop(dropResult) {
      this.choices = this.$questionnaire_store.onDrop(this.choices, dropResult)
    },
    getFiles() {
      this.choices.forEach(c => {
        this.$store.downloadFile(c.picture, "https://lirp.cdn-website.com/343f0986cb9d4bc5bc00d8a4a79b4156/dms3rep/multi/opt/1274512-placeholder-640w.png").then((response) => {
        this.uploads[c.id] = response
      });
      })
    },
    removePicture(index, id){
      this.choices[index].picture = null;
      this.uploads.splice(this.uploads.findIndex(a => a.id === id) , 1)
    },
    openFileInput() {
      this.$refs.fileInput[0].click();
    },
    async onFileChange(e, id, index) {
      const file = e.target.files[0];
      if (this.$utils.isValidFileType(file.type)) {
        try {
          const response = await this.$store.uploadFile(file, 'uploads');
          const fileData = await this.$store.downloadFile(response.mediaId);
          this.uploads[id] = {id: "/api/media/" + response.mediaId, src: fileData};
          this.choices[index].picture = {id: "/api/media/" + response.mediaId}
        } catch (error) {
          console.error("Error occurred:", error);
        }
      }
    },
    addNewChoice() {
      const currentLanguage = this.$utils.currentLanguage().code;
      const newChoice = {
        label: JSON.stringify({[currentLanguage]: 'New choice number ' + (this.choices.length + 1)}),
        orderBy: this.choices.length
      };
      this.choices.push(newChoice);
    },
    handleConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this choice!'
      }, () => {
        const index = this.choices.findIndex(choice => choice.id === id);
        if (index !== -1) {
          this.choices.splice(index, 1);
        } else {
          console.error(`Choice with ID ${choiceId} not found.`);
        }
      })
    },
  },

};
</script>
