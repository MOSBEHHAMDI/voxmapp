<template>
  <v-window v-model="$store.MobileCurrentTab">
    <v-window-item value="Personal">
      <PersonalTasks />
    </v-window-item>
    <v-window-item value="Projects">
       <ProjectsTasks />
    </v-window-item>
  </v-window>

</template>
<script>
import PersonalTasks from '../components/PersonalTasks.vue'
import ProjectsTasks from '../components/ProjectsTasks.vue'

export default {
  name: "tasks",
  components:{PersonalTasks,ProjectsTasks}
}
</script>
