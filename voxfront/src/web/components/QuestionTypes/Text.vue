<template>
  <v-row>
    <v-col>
      <v-text-field v-model="config.text.regExp" placeholder="Expression..." label="Regular expression validation">
      </v-text-field>
    </v-col>
    <v-col>
      <v-text-field v-model="config.text.validation_error_msg" placeholder="Message..." label="Validation error message">
      </v-text-field>
    </v-col>
  </v-row>
</template>

<script>
import Translatable from "@/web/components/Translatable.vue";

export default {
  name: "Text",
  components: {
    Translatable
  },

  data: () => ({
    newQuestionText: null,
  }),
  computed: {
    config() {
      if (!this.$questionnaire_store.currentQuestion.config.text) {
        this.$questionnaire_store.currentQuestion.config = {text : {regExp: '', validation_error_msg: ''}}
      }
      return this.$questionnaire_store.currentQuestion.config
    },
  },
  methods: {},


};
</script>
