<template>
  <div>
    <v-autocomplete
        v-model="config.entry"
        :items="entries"
        append-inner-icon="mdi-arrow-down"
        bg-color="primary"
        class="rounded"
        density="compact"
        item-color="primary"
        label="Entries List"
        menu-icon=""
        rounded-shaped
    ></v-autocomplete>

  </div>
</template>

<script>

export default {
  name: "ListOfEntries",
  data: () => ({
    entries: ["Project", "Question", "Group", "Language"]
  }),
  computed: {
    config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (!currentQuestion.config.entry) {
          currentQuestion.config.entry = null

        }
        return currentQuestion.config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
        //Todo need list of entries that will be displayed in mobile
        this.$questionnaire_store.currentQuestion.config.entriesList = [];
      }
    },

  },
  methods: {},

};
</script>
