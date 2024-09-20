<template>


  <div class="pale-bg">
    <v-row class=" text-left pale-bg">
      <v-col>
        <div class="mt-4 ml-4 font-weight-bold main">Project general info</div>
      </v-col>
    </v-row>
    <v-row class="ml-6 mr-6 bg-white rounded">
      <v-col>
        <v-card class="pl-0 text-left ml-4 mr-4" color="transparent" elevation="0">
          <v-card-title class="pl-0">Duration</v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="2">
                <div class="main">{{ tr('START') }}</div>
                <div>{{ $store.MobileCurrentProject.startsOn }}</div>
              </v-col>
              <v-col cols="8">
                <v-range-slider
                    :model-value="[0,3]"
                    class="main"
                    disabled="true"
                    max="3"
                    min="0"
                    tick-size="4"
                >
                </v-range-slider>
              </v-col>
              <v-col cols="2">
                <div class="main">{{ tr('END') }}</div>
                <div>{{ $store.MobileCurrentProject.endsOn }}</div>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-title class="pl-0">Progress</v-card-title>
          <v-card-text>
            <v-progress-linear
                :model-value="$store.MobileCurrentProject.progress"
                color="amber"
                height="25"
                rounded
            >
              <template v-slot:default="{ value }">
                <strong>{{ Math.ceil(value) }}%</strong>
              </template>
            </v-progress-linear>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row v-for="group in tasksGroups" :key="group.id" class="ml-4 mr-4 mb-4">
      <v-col>
        <v-card class="pl-0" color="transparent" elevation="0">
          <v-card-title class="pl-0">
            <h5 class="main text-left text-capitalize">{{ $utils.getLabel(group.label) }}</h5>
          </v-card-title>
          <v-card-text class="bg-white border-8 main-border pa-0">
            <v-expansion-panels>
              <v-expansion-panel v-for="task in  group.tasks"
                                 class="text-left main-border-bottom pa-3"

              >
                <template #text>
                  <span class="ml-2 mt-4 mb-2 font-weight-bold text-capitalize"
                        v-html="$utils.getLabel(task.description) "></span>
                </template>
                <template #title>
                  <div>
                    <v-icon :class="{ 'main':  task.state==='Done' }" class="mr-2">
                      {{ task.state === 'Done' ? 'mdi-check-circle' : 'mdi-circle-outline' }}
                    </v-icon>
                    <span class="ml-2 font-weight-bold text-capitalize">{{ $utils.getLabel(task.name) }}</span>
                  </div>
                </template>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

  </div>
</template>
<script>
export default {
  name: "info",
  computed: {
    tasksGroups() {
      const userGroupsIds = this.$store.logged_user.groups.map(group => group.id);
      const project_tasks_ids = this.$store.MobileCurrentProject.tasks.map(task => task.id);
      const tasks_groups = [];
      this.$store.MobileCurrentProject.tasks.forEach(task => {
        task.groups.forEach(group => {
          if (!tasks_groups.map(group => group.id).includes(group.id) && userGroupsIds.includes(group.id)) {
            tasks_groups.push({
              ...group,
              tasks: group.tasks?.filter(task => project_tasks_ids.includes(task.id))
            });
          }
        });
      });

      return tasks_groups


    }
  },
}
</script>
