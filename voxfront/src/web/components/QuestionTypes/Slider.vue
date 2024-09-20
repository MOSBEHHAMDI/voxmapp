<template>
  <v-row class="text-left align-center mt-2 mb-2">
    <v-col>
      <v-text-field v-model="config.slider.min" :placeholder="tr('min')" :rules="[rules]" variant="solo">
      </v-text-field>
    </v-col>
    <v-col>
      <v-text-field v-model="config.slider.max" :placeholder="tr('max')" :rules="[rules]" variant="solo">
      </v-text-field>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "SliderQuestion",
  data: () => ({}),
  computed: {
    config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (!currentQuestion.config.slider) {
          currentQuestion.config.slider = {min: null, max: null};
        }
        return currentQuestion.config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      }
    },
  },

  methods: {
    rules() {
      const {min, max} = this.config.slider;
      if (!/^\d+$/.test(min) || !/^\d+$/.test(max)) {
        return 'Slider properties must be numbers';
      }

      return parseFloat(min) < parseFloat(max) || 'Min value must be inferior to max value';
    }

  }
};
</script>
