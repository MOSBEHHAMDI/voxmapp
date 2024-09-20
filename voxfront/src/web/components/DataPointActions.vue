<template>
  <div>
    <v-row>
      <v-col cols="4">
        <v-btn block color="primary" @click="info = 'task'"
        >{{ tr('Tasks') }}
        </v-btn>
      </v-col>
      <v-col cols="4">
        <v-btn block color="primary" @click="info = 'dashboard'"
        >{{ tr('Dashboards') }}
        </v-btn>
      </v-col>
      <v-col cols="4">
        <v-btn block color="primary" @click="info = 'report'"
        >{{ tr('Reports') }}
        </v-btn>
      </v-col>
    </v-row>
    <div>
      <v-card v-if="info === 'task'" class="pa-0 rounded-8 white mt-5 mb-5" color="primary" elevation="0">
        <v-card-title class="pa-2 text-left main">
          <span class="ml-4 text-white">{{ tr('Associated tasks') }}</span>
          <v-btn @click="$task_store.createNewTask()" variant="tonal" elevation="0" color="white"
                 class="float-end white-dashed-border">
            <v-icon>mdi-plus</v-icon>
            Add
          </v-btn>
        </v-card-title>
        <v-card-text class="text-left bg-white">
          <v-list bg-color="white">
            <v-list-item v-if="!$dataPoint_store.currentDataPoint?.tasks?.length">
              <span class="main font-weight-bold">{{ tr(' Empty list') }}</span>
            </v-list-item>
            <v-list-item v-for="(task, i) in $dataPoint_store.currentDataPoint.tasks" :key="i"
                         class="rounded-8 mt-2 main" variant="outlined">
              {{ 'Task ' + (i + 1) + ': ' + $utils.getLabel(task.name) }}
              <template #append>
                <v-btn class="mr-2 main" elevation="0" @click="editTask(task.id)">Edit</v-btn>
                <v-btn class="mr-1" color="primary" elevation="0" icon size="20"
                >
                  <v-icon color="white" size="15" @click="removeTask(task.id)">mdi-close</v-icon>
                </v-btn>
              </template>
              <v-list-item>
                <v-row class="main">
                  <v-col class="text-left">0 %</v-col>
                  <v-col v-if="task.status" class="text-center">{{ task.status }} %</v-col>
                  <v-col class="text-right">100 %</v-col>
                </v-row>
                <div>
                  <v-progress-linear
                      :model-value="task.status"
                      class="mr-4"
                      color="primary"
                      height="10"
                      rounded
                  ></v-progress-linear>
                </div>
              </v-list-item>
            </v-list-item>
          </v-list>
        </v-card-text>
      </v-card>
      <v-card v-else-if="info === 'dashboard'" class="pa-0 rounded-8 white mt-5 mb-5" color="primary" elevation="0">
        <v-card-title class="pa-2 text-left main">
          <span class="ml-4 text-white">{{ tr('Associated Dashboards') }}</span>
        </v-card-title>
        <v-card-text class="text-left bg-white pa-2">
          <h3 class="main text-center">Not Available yet</h3>
        </v-card-text>
      </v-card>
      <v-card v-else class="pa-0 rounded-8 white mt-5 mb-5" color="primary" elevation="0">
        <v-card-title class="pa-2 text-left main">
          <span class="ml-4 text-white">{{ tr('Reports') }}</span>
        </v-card-title>
        <v-card-text class="text-left bg-white pa-2">
          <h3 class="main text-center">Not Available yet</h3>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      info: 'task'
    }
  },
  methods:{
    editTask(id) {
      this.$task_store.currentTask = this.$task_store.tasksList.find(task => task.id === id);
      this.$router.push('/task/edit');
    },
    removeTask(id) {
       this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this data point task !'
      }, () => {
        this.$dataPoint_store.currentDataPoint.tasks = this.$dataPoint_store.currentDataPoint.tasks.filter((task) => task.id !== id)
        this.$dataPoint_store.save();
      });
    },
  },
  props: {
    dataPoint: {
      default: null,
    }
  }
}
</script>