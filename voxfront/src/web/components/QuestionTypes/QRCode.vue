<template>
  <v-row class="text-left align-center mt-2 mb-2">
    <v-col>
      <v-text-field v-model="config.QRCode.scanPattern" :placeholder="tr('Scan Pattern')"
                    variant="solo">
      </v-text-field>
    </v-col>
    <v-col>
      <v-text-field v-model="config.QRCode.scanningLimit" :placeholder="tr('Scanning Limit')"
                    :rules="scanLimitRules"
                    variant="solo">
      </v-text-field>
    </v-col>
    <v-col>
      <v-checkbox
          v-model="config.QRCode.multipleScanning"
          color="primary"
          label="Multiple Scanning"
      ></v-checkbox>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "QRCode",
  data: () => ({
    scanLimitRules: [
      value => {
        if (!value || /^\d+$/.test(value)) {
          return true
        } else {
          return 'Scan Limit must be a number.'
        }
      }

    ],
  }),
  computed: {
     config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (!currentQuestion.config.QRCode) {
          currentQuestion.config.QRCode = {scanPattern: null, multipleScanning: false, scanningLimit: null};
        }
        return currentQuestion.config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      }
    },

  },

};
</script>
