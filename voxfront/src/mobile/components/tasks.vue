<template>
  <v-list v-if="!$dataPoint_store.currentDataPoint?.tasks?.length">
    <v-list-item>
      <span class="main font-weight-bold">{{ tr(' Empty list') }}</span>
    </v-list-item>
  </v-list>
  <v-list class="pale-bg">
    <v-list-item v-for="(task, index) in $dataPoint_store.currentDataPoint.tasks" :key="index"
                 class="text-left pale-bg">
      <template #title>
        <span v-if="task.taskType?.name" class="main"> {{ task.taskType.name }} </span>
        <v-expansion-panels class="rounded mt-0 main">
          <v-radio-group v-model="selectedTaskId">
            <v-expansion-panel class="mt-4" @click="selectTask(task.id)">
              <template #title>
                <v-radio :label="'Task ' + (index + 1) + ': ' + $utils.getLabel(task.name)"
                         :value="task.id"></v-radio>
              </template>
              <template #text>
                <v-row>
                  <span class="text-left mt-6" v-html="$utils.getLabel(task.description)"></span>
                </v-row>
                <v-row class="main">
                  <v-col class="text-left">0 %</v-col>
                  <v-col v-if="task.status" class="text-center">{{ task.status }} %</v-col>
                  <v-col class="text-right">100 %</v-col>
                </v-row>
                <v-progress-linear :model-value="task.status" class="mr-4" color="primary" height="10"
                                   rounded></v-progress-linear>
              </template>
            </v-expansion-panel>
          </v-radio-group>
        </v-expansion-panels>

      </template>
    </v-list-item>
  </v-list>
</template>
<script>
export default {
  name: "tasks",
  methods: {
    selectTask(taskId) {
      this.selectedTaskId = taskId;
    }
  },
  data() {
    return {
      expandedTask: null,
      selectedTaskId: null,
    };
  }
}
</script>
