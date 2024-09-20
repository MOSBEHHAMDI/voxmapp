<template>
  <v-list class="pale-bg">
    <v-list-item class="text-left text-white">
      <span class="font-weight-bold main">{{ tr('Your active projects') }}</span>
    </v-list-item>
    <v-list-item v-if="!$store.logged_user.projects.length">
      <span class="main font-weight-bold">{{ tr(' Empty list') }}</span>
    </v-list-item>
  </v-list>

  <v-list class="pale-bg">
    <v-list-item v-for="project in $store.logged_user.projects">
      <v-card class="main-border rounded" elevation="0" @click="showProject(project)">
        <v-card-title>
          <v-row>
            <v-col>
              <span class="ml-2 font-weight-bold">{{ $utils.getLabel(project.name) }}</span>
            </v-col>
            <v-col>
              <v-btn v-if="project.id === $store.logged_user.currentProject?.id" class="main-bg text-white custom-button"
                     min-width="92"
                     rounded>
                Current project
              </v-btn>
              <v-btn v-else class="pale-bg text-white custom-button" min-width="92" rounded
                     @click="setAsCurrent(project)"
                     @click.stop>
                Set as current
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col>
              <span class="ml-2 font-weight-bold"> Tasks</span>
            </v-col>
            <v-col v-if="tasksInfo(project).completedProject">
              <span class="font-weight-bold text-green"> {{ tasksInfo(project).completedTasks }}</span>
              <span class="font-weight-bold">  {{ '/' + tasksInfo(project).allTasks }}</span>
              <span class="font-weight-bold text-green">{{ tr(' Completed') }}</span>
            </v-col>
            <v-col v-else>
              <span class="main font-weight-bold"> {{ tasksInfo(project).completedTasks }}</span>
              <span class="font-weight-bold">  {{ '/' + tasksInfo(project).allTasks }}</span>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <span class="ml-2 font-weight-bold"> Ends on</span>
            </v-col>
            <v-col>
              <span class="ml-2 font-weight-bold"> {{ tasksInfo(project).endsOn }}</span>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-list-item>
  </v-list>


</template>

<script>
export default {
  name: "PersonalTasks",
  data() {
    return {};
  },
  computed: {},
  methods: {
    setAsCurrent(project) {
      this.$store.logged_user.currentProject = project
      this.$store.saveUser(this.$store.logged_user, false, true);
    },
    showProject(project) {
      this.$store.MobileCurrentProject = Object.assign(project, this.tasksInfo(project));
      this.$store.MobileDynamicTitle = this.$utils.getLabel(this.$store.MobileCurrentProject.name);
      this.$router.push(`/projectInfo/`);
    },
    tasksInfo(project) {
      const {tasks} = project;

      const completedTasks = tasks.filter(task => task.state === 'Done').length;
      const allTasks = tasks.length;
      const endDates = tasks.map(task => new Date(task.endDate));
      const startDates = tasks.map(task => new Date(task.startDate));

      const lastDate = endDates.reduce((maxDate, currentDate) => maxDate ? (currentDate > maxDate ? currentDate : maxDate) : currentDate, null);
      const firstDate = startDates.reduce((minDate, currentDate) => minDate ? (currentDate < minDate ? currentDate : minDate) : currentDate, null);

      const progress = allTasks === 0 ? 0 : (completedTasks / allTasks) * 100;

      return {
        completedTasks,
        allTasks,
        endsOn: this.$utils.formatDate(lastDate,'-'),
        startsOn: this.$utils.formatDate(firstDate,'-'),
        completedProject: completedTasks === allTasks,
        progress
      };
    }


  },


}
</script>
<style scoped>
.custom-button {
  position: relative;
  top: -15px;
}
</style>