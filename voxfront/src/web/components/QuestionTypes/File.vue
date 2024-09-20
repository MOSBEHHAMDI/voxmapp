<template>
  <div>
    <v-select
        v-model="config.file.type"
        label="Select File Type"
        :items="['Image/Video', 'Audio', 'File']"
    ></v-select>
  </div>
</template>

<script>
export default {
  name: "File",
  data: () => ({}),
  computed: {
    config() {
      if (!this.$questionnaire_store.currentQuestion.config.file) {
        this.$questionnaire_store.currentQuestion.config = {file: {type: 'Image'}}
      }
      return this.$questionnaire_store.currentQuestion.config
    },
  },

  methods: {},


};
</script>
