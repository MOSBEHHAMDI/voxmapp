<template>
  <v-card class="grey-bg">
    <template #prepend>
      <v-icon class="main bg-white rounded-circle" @click="goTo('prev')">mdi-chevron-left</v-icon>
    </template>
    <template #append>
      <v-icon class="main bg-white rounded-circle" @click="goTo('next')">mdi-chevron-right</v-icon>
    </template>
    <template #title>
      <div class="text-center">
        <span>{{ $store.currentActions.lastDate }}</span>
      </div>
    </template>
  </v-card>
</template>
<script>
export default {
  name: "UserHistoryNavbar",
  methods: {
    goTo(dest) {
      const dates = Object.keys(this.$store.groupedUserActions);
      const currentIndex = dates.indexOf(this.$store.currentActions.lastDate);
      const newIndex = dest === 'next' ? currentIndex + 1 : dest === 'prev' ? currentIndex - 1 : null;

      if (newIndex !== null && newIndex >= 0 && newIndex < dates.length) {
        const newDate = dates[newIndex];
        this.$store.currentActions = {list: this.$store.groupedUserActions[newDate], lastDate: newDate};
      }
    }
  }
}
</script>
<style scoped>

</style>