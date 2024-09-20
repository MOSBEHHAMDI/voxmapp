<template>
  <div>
    <v-row class="pa-0 secondary-bg">
      <v-col class="text-left bg-white" cols="2">
        <h2 class="main">{{ tr('Data Points') }}</h2>
      </v-col>
      <v-col align="end" class="bg-white text-end" cols="4" style="border-right: var(--secondary-color) 3px solid">
        <v-btn class="bg-transparent" color="primary" elevation="0" variant="tonal"
               @click="createNewDataPoint()">
          Add
          <v-icon class="ml-1" size="25">mdi-plus-circle</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col class="white-bg pa-0">
        <v-row>
          <v-col>
            <List :config="config" :dynamic_data="dataPoints"
                  @triggerEvent="triggerEvent"
            ></List>
          </v-col>
        </v-row>
      </v-col>
      <v-col class="secondary-bg main pa-1 overflow-cols">
        <div v-if="!Object.keys($dataPoint_store.currentDataPoint).length" class="pa-10 text-center ma-auto bg-white">
          <v-img height="300"
                 src="https://cdn.dribbble.com/users/62525/screenshots/11277078/media/fa035e6d4d981a115216b3589b369f3b.png?resize=400x300&vertical=center"/>
        </div>
        <div v-else class="pa-10 secondary-bg pt-4">
          <v-card class="pa-0 rounded-8 white mb-5" color="primary" elevation="0">
            <v-card-title v-if="$dataPoint_store.currentDataPoint" class="pa-0 text-center">
              <Slider :show-galleries="false" :downloads="downloads"/>
              <h3 class="mt-3 mb-3 text-capitalize">{{ $dataPoint_store.currentDataPoint.name }}</h3>
            </v-card-title>
            <v-card-text class="text-left">
              <data-point-columns/>
            </v-card-text>
            <v-card-actions class="pb-8 mt-3 mb-3">
              <v-spacer></v-spacer>
              <v-btn class="text-capitalize main bg-white" color="primary" @click="completeProfile()">
                {{ tr('View Profile') }}
              </v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
          <DataPointActions/>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import List from "@/web/components/DataList";
import Slider from "@/web/components/Slider.vue";
import DataPointColumns from "@/web/components/DataPointColumns.vue";
import DataPointActions from "@/web/components/DataPointActions.vue";

export default {
  components: {DataPointActions, List, Slider, DataPointColumns},
  created() {
    this.$dataPoint_store.getDatapointsList();
    this.$dataPoint_store.getDatapointsInfo();
    this.$task_store.getTasksList();
    this.downloads = this.getDownloadedFiles(this.$dataPoint_store.currentDataPoint.uploads);
  },
  computed: {
    config() {
      return {
        title: '',
        header: [
          {
            name: 'name',
            label: 'Name',
            visible: true,
            title: 'name',
          },
          {
            name: 'dataPointType',
            visible: true,
            label: 'Type',
            property: 'name',
          },
          {
            name: 'actions',
            actions: [{
              name: 'Edit',
              icon: 'pencil-outline',
              event: 'editDataPoint'
            }, {
              name: 'Delete',
              icon: 'close',
              event: 'removeDataPoint'
            }],
            visible: false,
            label: 'Actions',
          }
        ],
        save_row: 'saveDataPoint'
      }
    },
    dataPoints: {
      get() {
        return this.$dataPoint_store.dataPointsList.map(dataPoint => {
          if (dataPoint.project) {
            dataPoint.project.name = this.$utils.getLabel(dataPoint.project.name)
          }
          return dataPoint
        });
      },
      set(list) {
        this.$dataPoint_store.dataPointsList = list
      }
    },
  },
  data: () => ({
    isSelecting: false,
    selectedFile: null,
    downloads: [],
  }),
  methods: {
    completeProfile() {
      this.$router.push('/datapoint/details')
    },
    createNewDataPoint() {
      this.$dataPoint_store.currentDataPoint = {}
      this.$router.push('/datapoint/create')
    },
    triggerEvent(params) {
      if (this[params.event]) {
        this[params.event](params.data)
      } else {
        console.log(params.event, ' method does not exist in DatapointList')
      }
    },
    saveDataPoint(dataPoint) {
      console.log(dataPoint)
    },
    rowSelected(dataPoint) {
      dataPoint.project.name= this.$utils.getLabel(dataPoint.project.name);
      this.downloads = this.getDownloadedFiles(dataPoint.uploads);
      this.$dataPoint_store.currentDataPoint = dataPoint;
    },
    editDataPoint(dataPoint) {
      this.$dataPoint_store.currentDataPoint = dataPoint;
      this.$router.push('/datapoint/edit');
    },
    removeDataPoint(dataPoint) {
      this.handleConfirm(dataPoint.id);
    },
    handleConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this datapoint?',
      }, () => {
        this.$dataPoint_store.deleteDatapoint(id);
      })
    },
    onFileChanged(e) {
      this.selectedFile = e.target.files[0];
      //ToDo: to be completed after
    },
  },
}
</script>

<style>
.new_dataPoint_btn {
  transition: background-color 0.3s ease;
  animation: scaleIn 0.5s ease-out 1s;
  color: white;
}

.new_dataPoint_btn:hover {
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
