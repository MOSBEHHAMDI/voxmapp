<template>
  <v-row>
    <v-col v-if="$dataPoint_store.currentDataPoint.history">
      <v-card class="main">
        <v-card-text v-show="getUsersItems && getUsersItems.length" class=" text-left">
          History
          <v-list>
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
      </v-card>
    </v-col>
    <v-col v-else>
      <img src="../../assets/history.png"/>
    </v-col>
  </v-row>
</template>
<script>
export default {
  name: "history",
  computed: {
    usersHistory() {
      return this.$dataPoint_store.currentDataPoint?.history?.filter(h => h.action !== 0);
    },
  },
}
</script>

<style scoped>

</style>