<template>
  <v-row>
    <v-col v-for="type in ['min', 'max']" :key="type" cols="5">
      <v-menu :close-on-content-click="false">
        <template v-slot:activator="{ props }">
          <v-text-field v-model="formattedDate[type]"
                        :label="type === 'min' ? 'Min Date' : 'Max Date'"
                        :rules="[rules]"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="props"
          ></v-text-field>
        </template>
        <v-date-picker v-model="config.date[type]"></v-date-picker>
      </v-menu>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "Date",
  data() {
    return {
      formattedDate: {min: null, max: null},
    };
  },
  watch: {
    'config.date': {
      deep: true,
      handler() {
        this.initializeFormattedDate();
      },
    },
  },
  methods: {
    rules() {
      const {min, max} = this.config.date;

      return min && max && min > max ? 'Min date cannot be superior to end Max date' : true;
    },
    initializeFormattedDate() {
      ['min', 'max'].forEach((type) => {
        const date = this.$questionnaire_store.currentQuestion.config.date;
        if (date && date[type]) {
          this.formattedDate[type] = this.$utils.formatDate(date[type],'-');
        }
      });
    },
  },
  computed: {
    config: {
      get() {
        const config = this.$questionnaire_store.currentQuestion.config;
        config.date = {
          min: config.date?.min ? new Date(config.date.min) : undefined,
          max: config.date?.max ? new Date(config.date.max) : undefined,
        };
        ['min', 'max'].forEach((type) => {
          if (config.date && config.date[type] instanceof Date) {
            this.formattedDate[type] = this.$utils.formatDate(config.date[type],'-');
          }
        });
        return config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      },
    },
  },
};
</script>
