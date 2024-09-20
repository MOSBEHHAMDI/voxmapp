<template>
  <div class="pa-5 secondary-bg">
    <h2 class="primary-text mb-5 text-left">Infrastructures</h2>
    <v-row>
      <v-col>
        <v-card :color="!showGalleries ? 'primary' : 'secondary'" elevation="0">
          <v-card-title v-if="dataPoint && !showGalleries" class="text-capitalize mt-2 mb-4">
            <h3>{{ dataPoint.name }}</h3>
          </v-card-title>
          <v-card-text class="text-left">
            <v-row color="transparent" elevation="0">
              <v-col cols="8">
                <v-row>
                  <v-col class="pl-0 pr-0" cols="2">
                    <v-btn v-if="showGalleries" class="text-capitalize main bg-white" color="white" elevation="0"
                           @click="showGalleries = false">
                      <span class="main"><v-icon>mdi-arrow-left</v-icon> {{ tr('General Info') }}</span>
                    </v-btn>
                  </v-col>
                  <v-col class="pl-0 pr-0"><h3 class="main text-capitalize pt-1">DataPoint {{ dataPoint.name }}
                    Gallery</h3></v-col>
                </v-row>
                <Slider ref="slider" :show-galleries="showGalleries" :downloads="downloads" />
                <div class="text-center">
                  <v-btn v-if="!showGalleries" class="text-capitalize main bg-white mt-4" color="white" elevation="0"
                         @click="showGalleriesPage()">
                    <span class="main">{{ tr('View gallery') }}</span>
                  </v-btn>
                </div>
                <v-card-text v-if="!showGalleries" class="text-left">
                  Created By
                  <v-list bg-color="primary">
                    <v-list-item :prepend-avatar="createdBy.prependAvatar"
                                 :subtitle="createdBy.subtitle"
                                 :title="createdBy.title"
                                 class="rounded-8"
                                 variant="outlined">
                      <template #append>
                        {{ $utils.formatDate(dataPoint.creationDate,'-') }}
                      </template>
                    </v-list-item>
                  </v-list>
                </v-card-text>
                <v-card-text v-if="!showGalleries" v-show="getUsersItems && getUsersItems.length" class=" text-left">
                  History
                  <v-list bg-color="primary">
                    <v-list-item v-for="(user, i) in getUsersItems"
                                 :key="i"
                                 :prepend-avatar="user.prependAvatar"
                                 :subtitle="user.subtitle"
                                 :title="user.title"
                                 class="rounded-8"
                                 variant="outlined">
                      <template v-slot:append>
                        {{ user.date }}
                      </template>
                    </v-list-item>
                  </v-list>
                </v-card-text>
              </v-col>
              <v-col>
                <data-point-columns v-if="!showGalleries" :gridLayout="true" class="mb-4"/>
                <div style="height:220px">
                  <!--Map v-if="!showGalleries && latLong" :latLong="latLong" :zoom="8" :zoom-control="true"/-->
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col v-if="!showGalleries">
        <DataPointActions/>
        <v-card class="pa-0 rounded-8 white mt-5 mb-5" color="primary" elevation="0">
          <v-card-title class="pa-2 text-left main">
            <span class="ml-4 text-white">{{ tr('Associated Questionnaires') }}</span>
          </v-card-title>
          <v-card-text class="text-left bg-white pa-2">
            <div v-for="(questionnaire, index) in questionnaires" v-if="questionnaires.length !== 0" :key="index"
                 class="users-list">
              <div
                  :class="index < questionnaires -1 ? 'border-bottom-main' : '', selected_questionnaire.id === questionnaire.id ? ' active ' : ''"
                  class="user-home user_info text-capitalize">
                <v-avatar color="#E4EFF4" size="25">Q</v-avatar>
                {{ questionnaire.name }}
                <span class="float-right">
                     <v-icon @click="editQuestionnaire(questionnaire.id)">mdi-pencil-outline</v-icon>
                </span>
              </div>
            </div>
            <h3 v-else class="pa-5 main text-center">
              No questionnaire associated
            </h3>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import Slider from "@/web/components/Slider.vue";
import DataPointColumns from "@/web/components/DataPointColumns.vue";
import Map from "../components/OpenLayerMap.vue";
import DataPointActions from "@/web/components/DataPointActions.vue";

export default {
  name: "DataPointDetails",
  components: {
    DataPointActions,
    Map,
    Slider,
    DataPointColumns,
  },
  data: () => ({
    selected_questionnaire: {},
    showGalleries: false,
    ownerAvatar: null,
    downloads: []
  }),
  created() {
    this.downloads = this.getDownloadedFiles(this.$dataPoint_store.currentDataPoint.uploads);
  },
  computed: {
    createdBy() {
      let owner = {}
      this.$store.downloadFile(this.dataPoint.createdBy?.profilePic).then((r) => {
        this.ownerAvatar = r
      })
      owner = {
        prependAvatar: this.ownerAvatar,
        title: this.dataPoint.createdBy.userName,
        subtitle: this.dataPoint.createdBy.email,
      }
      return owner
    },
    usersHistory() {
      return this.$dataPoint_store.currentDataPoint?.history?.filter(h => h.action !== 0);
    },
    dataPoint() {
      this.$dataPoint_store.currentDataPoint.project.name = this.$utils.getLabel(this.$dataPoint_store.currentDataPoint.project.name);
      return this.$dataPoint_store.currentDataPoint;
    },
    latLong() {
      return this.dataPoint.latLong ? this.dataPoint.latLong.split(',').map(Number) : null;
    },
    questionnaires() {
      let questionnaires = []
      this.dataPoint.users.forEach(u => {
        if (u.questionnaires) {
          questionnaires.push(...u.questionnaires)
        }
      })
      return questionnaires
    }
  },
  methods: {
    editQuestionnaire(q) {
      let questionnaire = this.$questionnaire_store.questionnairesList.find(f => f.id === q)
      this.$questionnaire_store.currentQuestion = null;
      this.$questionnaire_store.currentQuestionnaire = questionnaire;
      this.$router.push({path: '/questionnaire/edit/'});
    },
    showGalleriesPage() {
      if (this.dataPoint.uploads?.length) {
        this.showGalleries = true;
      } else {
        this.$utils.showSnackbar("No gallery found", 'warning')
      }
    },
  }
}
</script>

