<template>
  <v-list class="pale-bg">
    <v-list-item class="text-left text-white">
      <span class="font-weight-bold main">{{ tr('Your personal tasks') }}</span>
    </v-list-item>
    <v-list-item v-if="!$store.logged_user?.tasks?.length">
      <span class="main font-weight-bold">{{ tr(' Empty list') }}</span>
    </v-list-item>
  </v-list>
  <v-list class="pale-bg">
    <v-list-item v-for="(task, index) in $store.logged_user.tasks" :key="index"
                 class="text-left">
      <template #title>
        <span v-if="task.taskType?.name" class="main"> {{ task.taskType.name }} </span>
        <v-expansion-panels class="rounded mt-0 main">
          <v-radio-group v-model="selectedTaskId">
            <v-expansion-panel @click="selectTask(task.id)">
              <template #title>
                <v-icon :class="{ 'main':  task.state==='Done' }" class="mr-2">
                  {{ task.state === 'Done' ? 'mdi-check-circle' : 'mdi-circle-outline' }}
                </v-icon>
                <span class="mr-2">
                {{ $utils.getLabel(task.name) }}
                 </span>
                <span :class="{  'main': task.state==='Done'  }">  {{ '(' + task.state + ')' }}</span>
              </template>
              <template #text>
                <v-row>
                    <span class="ml-2 mt-6 mb-2 text-capitalize"
                          v-html="$utils.getLabel(task.description) "></span>
                </v-row>
                <v-row>
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
  name: "PersonalTasks",
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