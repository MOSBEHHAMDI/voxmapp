<template>
  <v-form class="quick_edit" @submit.prevent="saveTask">
    <v-row>
      <v-col class="text-left overflow-cols">
        <v-card class="text-capitalize" color="transparent" elevation="0">
          <v-card-title class="text-left main">{{ tr('Task Info') }}</v-card-title>
          <v-card-text>
            <translatable v-model="currentTask.name"
                          :placeholder="tr('Task Name')"
                          :rows-number="1"
                          class="pa-0 mb-2" />
            <TextEditor v-model="currentTask.description" class="mb-7"></TextEditor>
            <div class="bg-secondary pa-2 border-8 mt-n2">
              <DynamicList v-slot="slotProps" v-model:list="uploads" :item-name="'File'" :item-value="inputFile"
                           :sortable="false"
                           :title="'Media'">
                <v-row class="boorder">
                  <v-col>
                    <v-file-input
                        :clearable="false"
                        :label="uploads[slotProps.index]['label']"
                        :rules="rules.uploads"
                        accept="image/png, image/jpeg"
                        density="compact"
                        variant="filled"
                        @change="onFileChange($event,slotProps.index )">
                    </v-file-input>
                  </v-col>
                  <v-col>
                    <v-card
                        v-if="uploads[slotProps.index]['src']"
                        :image="uploads[slotProps.index]['src']"
                        class="mx-auto"
                        height="80"
                        rounded
                        theme="light"
                        width="80"
                    ></v-card>
                  </v-col>
                </v-row>
              </DynamicList>
            </div>
          </v-card-text>
          <v-row>
            <v-col class="text-center">
              <v-btn class="general-btn" type="submit">
                <v-icon>mdi-check</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
      <v-col class="bg-primary text-left overflow-cols">
        <div class="mt-3"><span class="font-weight-boldF">{{ tr('Task editor') }}</span></div>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            Users associated
          </v-card-title>
          <v-card-text class="text-left pa-0">
            <v-row class="pa-0 pt-4">
              <v-col class="pb-0">
                <div class="pl-4 pr-4">
                  <div class="main">Who should do this</div>
                  <v-autocomplete
                      v-model="currentTask.users"
                      :items="users_items"
                      chips
                      closable-chips
                      color="blue-grey-lighten-2"
                      item-title="userName"
                      item-value="id"
                      label="Select users"
                      multiple
                      return-object=""
                  >
                    <template v-slot:chip="{ props, item }">
                      <v-chip
                          :prepend-avatar="getUserAvatar(item.raw.id)"
                          :text="item.raw.userName"
                          v-bind="props"
                      ></v-chip>
                    </template>
                    <template v-slot:item="{ props, item }">
                      <v-list-item
                          :prepend-avatar="getUserAvatar(item.raw.id)"
                          :title="item.raw.userName"
                          v-bind="props"
                      ></v-list-item>
                    </template>
                  </v-autocomplete>
                  <div class="main ">Choose a group</div>
                  <v-autocomplete
                      v-model="currentTask.groups"
                      :items="groups_items"
                      :placeholder="tr('Choose group')"
                      chips=""
                      closable-chips
                      density="compact"
                      item-title="label"
                      multiple
                      return-object
                  ></v-autocomplete>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            Tasks Info
          </v-card-title>
          <v-card-text>
            <v-select
                v-model="currentTask.taskType"
                :items="$task_store.task_page_info.taskTypes"
                :label="tr('Task Type')"
                :placeholder="tr('Task type')"
                :rules="rules.taskType"
                chips
                clearable
                color="primary"
                item-title="name"
                return-object>
            </v-select>
            <v-select
                v-model="currentTask.state"
                :items="taskStates"
                :label="tr('Task State')"
                :placeholder="tr('Task state')"
                chips
                clearable
                color="primary"
            >
            </v-select>
            <DynamicList v-slot="slotProps" v-model:list="currentTask.tags" :item-name="'Tag'"
                         :item-value="tagInput" :sortable="false"
                         :title="'Tags'" class="rounded">
              <Translatable
                  v-model="currentTask.tags[slotProps.index]" :hide_details="true"
                  :rows-number="1" class="pa-0"/>
            </DynamicList>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            DataPoints
          </v-card-title>
          <v-card-text>
            <v-autocomplete
                v-model="currentTask.dataPoints"
                :items="$store.home_page_info.dataPoints"
                :label="tr('Task DataPoints')"
                chips
                clearable
                color="primary"
                item-title="name"
                item-value="id"
                multiple
                return-object>
            </v-autocomplete>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            Project
          </v-card-title>
          <v-card-text>
            <v-select
                v-model="currentTask.project"
                :items="projects_items"
                :label="tr('Task Projects')"
                clearable
                color="primary"
                item-title="name"
                return-object>
            </v-select>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            Time
          </v-card-title>
          <v-card-text class="text-left">
            <v-row>
              <v-col v-for="type in ['startDate', 'endDate']" :key="type" cols="6">
                <v-menu :close-on-content-click="false">
                  <template v-slot:activator="{ props }">
                    <v-text-field v-model="formattedDate[type]"
                                  :label="type === 'startDate' ? 'Start Date' : 'End Date'"
                                  :rules="rules.date"
                                  prepend-icon="mdi-calendar"
                                  readonly
                                  v-bind="props"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="currentTask[type]"></v-date-picker>
                </v-menu>
              </v-col>

            </v-row>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">Repetition
          </v-card-title>
          <v-card-text class="text-left">
            <v-row>
              <v-col>
                <v-select
                    v-model="currentTask.repetition"
                    :items="repetitions"
                    :label="tr('Repetitions')"
                    chips
                    clearable
                    color="primary"
                    item-title="name"
                    return-object>
                </v-select>
              </v-col>
              <v-col class="pb-0">
                <v-menu :close-on-content-click="false">
                  <template v-slot:activator="{ props }">
                    <v-text-field v-model="formattedDate.endRepetition"
                                  label="End repetition"
                                  prepend-icon="mdi-calendar"
                                  readonly
                                  v-bind="props"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="currentTask.endRepetition"></v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        <v-card class="pa-0 text-capitalize pa-0 ma-5 rounded" color="white" elevation="0">
          <v-card-title class="text-left main">
            Status
          </v-card-title>
          <v-card-text class="text-left">
            <v-row class="main mb-2">
              <v-col class="text-left" cols="5">0 %</v-col>
              <v-col class="text-center secondary-bg main font-weight-bold border-8">{{ Math.ceil(currentTask.status) }}
                %
              </v-col>
              <v-col class="text-right" cols="5">100 %</v-col>
            </v-row>
            <v-slider v-model="currentTask.status" r></v-slider>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-form>
</template>
<script>
import DynamicList from "@/web/components/DynamicList.vue";
import Translatable from "@/web/components/Translatable.vue";
import {VueEditor} from "vue3-editor";
import TextEditor from "@/web/components/TextEditor.vue";

export default {
  name: 'CreateTask',
  components: {TextEditor, Translatable, DynamicList, VueEditor},
  data: () => ({
    taskStates: ['Backlog', 'Develop', 'Blocking', 'Review', 'Test', 'Staging', 'Done'],
    repetitions: ['Weekly', 'Daily', 'Monthly'],
    uploads: [],
    formattedDate: {startDate: null, endDate: null, endRepetition: null},
    windowSize: {
      x: 0,
      y: 0,
    },
    associated_users: [],
    usersAvatars: [],
  }),
  watch: {
    'currentTask.startDate': {
      deep: true,
      handler() {
        this.initializeFormattedDate();
      },
    },
    'currentTask.endDate': {
      deep: true,
      handler() {
        this.initializeFormattedDate();
      },

    },
    'currentTask.endRepetition': {
      deep: true,
      handler() {
        this.initializeFormattedDate();
      }
    },
  },
  computed: {
    projects_items() {
      return this.$store.home_page_info.projects?.map(project => {
        return {id: project.id, name: this.$utils.getLabel(project.name)};
      });
    }
    ,
    currentTask: {
      get() {
        const currentTask = this.$task_store.currentTask ? this.$task_store.currentTask : {};
        //fetch task project name from json data
        if (currentTask.project) {
          currentTask.project = {
            id: currentTask.project.id,
            name: this.$utils.getLabel(currentTask.project.name)
          };
        }
        if (currentTask && Object.keys(currentTask).length) {
          currentTask.startDate = currentTask.startDate ? new Date(currentTask.startDate) : undefined,
              currentTask.endDate = currentTask.endDate ? new Date(currentTask.endDate) : undefined,
              currentTask.endRepetition = currentTask.endRepetition ? new Date(currentTask.endRepetition) : undefined,
              ['startDate', 'endDate', 'endRepetition'].forEach((type) => {
                if (currentTask && currentTask[type] instanceof Date) {
                  this.formattedDate[type] = this.$utils.formatDate(currentTask[type],'-');
                }
              });
        }
        return currentTask;
      },
      set(task) {
        this.$task_store.currentTask = task;
      },
    },
    users_items() {
      return this.$task_store.task_page_info.users || []
    },
    groups_items() {
      const items = this.$task_store.task_page_info.groups;
      return items ? items : [];
    },
    inputFile() {
      const newFileInput = {
        label: "Upload the new file " + (this.uploads.length + 1),
        id: null,
        src: null,
      };
      return newFileInput
    },
    tagInput() {
      const currentLanguage = this.$utils.currentLanguage().code;
      return JSON.stringify({[currentLanguage]: "New tag "});
    },

    rules() {
      const rules_validation = {
        taskType: [
          value => {
            if (this.currentTask.taskType) {
              return true
            }
            return 'You must choose the task type.'
          }
        ],
        date: [
          value => {
            const {startDate, endDate} = this.currentTask;
            return startDate && endDate && startDate > endDate ? 'Starting date cannot be superior to end date' : true;
          }
        ],
        uploads: [
          value => {
            if (value && value[0] && typeof value[0] !== 'undefined') {
              const file = value[0];
              if (this.$utils.isValidFileType(file.type)) {
                return true
              } else {
                this.$utils.showSnackbar("Choose a valid image", 'warning')
                return false
              }
            }
          },
        ],
      };
      return rules_validation
    },
  },
  async created() {
    await this.$task_store.getTasksInfo();
    this.fetchUserAvatars();
    if (this.currentTask?.uploads?.length) {
      for (const upload of this.currentTask.uploads) {
        try {
          const fileData = await this.$store.downloadFile(upload.id);
          const media = {id: upload.id, src: fileData, label: 'Upload file'};
          this.uploads.push(media);
        } catch (error) {
          console.error('Error downloading file:', error);
        }
      }
    } else {
      this.uploads = [{
        id: null, src: null, label: 'Upload file',
      }];
    }
  },
  methods: {
    initializeFormattedDate() {
      ['startDate', 'endDate', 'endRepetition'].forEach((type) => {
        const task = this.$task_store.currentTask;
        if (task && task[type]) {
          this.formattedDate[type] = this.$utils.formatDate(task[type],'-');
        }
      });
    },
    async fetchUserAvatars() {
      this.usersAvatars = await Promise.all(this.users_items.map(async user => {
        let profile_pic = null;
        try {
          profile_pic = await this.$store.downloadFile(user.profilePic, "https://avatars0.githubusercontent.com/u/9064066?v=4&s=460");
        } catch (error) {
          console.error('Error fetching profile picture:', error);
        }
        return {
          id: user.id,
          pic: profile_pic
        };
      }));
    }
    ,
    getUserAvatar(userId) {
      const userAvatar = this.usersAvatars.find(avatar => avatar.id === userId);
      return userAvatar ? userAvatar.pic : null;
    },
    onResize() {
      this.windowSize = {x: window.innerWidth, y: window.innerHeight}
    },
    async onFileChange(e, index) {
      const file = e.target.files[0];
      if (this.$utils.isValidFileType(file.type)) {
        try {
          const response = await this.$store.uploadFile(file, 'uploads');
          const fileData = await this.$store.downloadFile(response.mediaId);
          const label = "Upload the new file " + (this.uploads.length + 1);
          const media = {id: "/api/media/" + response.mediaId, src: fileData, label: label};
          this.uploads[index] = media;
        } catch (error) {
          console.error("Error occurred:", error);
        }
      }
    },
    async saveTask(event) {
      const result = await event;
      // Check if result is valid
      if (result.valid) {
        // Set the task creator
        this.currentTask.createdBy = {id: this.$store.logged_user.id};

        // remove useless uploads (default upload)
        this.currentTask.uploads = this.uploads.filter(upload => upload.id !== null).map(upload => upload.id);

        // Delete unnecessary properties
        delete this.currentTask.customStatus;
        delete this.currentTask.period;

        // Round status to nearest integer
        this.currentTask.status = Math.ceil(this.currentTask.status);

        this.$task_store.save();
        // update task in the current dataPoint
        const dataPointId = this.$dataPoint_store.currentDataPoint.id;
        this.$dataPoint_store.getDatapointsList(dataPointId);
      }


    }

  }
}
</script>
