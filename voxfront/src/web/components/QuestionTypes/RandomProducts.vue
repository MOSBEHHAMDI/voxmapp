<template>
  <v-row class="text-left align-center mt-2 mb-2">
    <v-col>
      <v-text-field v-model="config.randomProduct.begin_interval" :placeholder="tr('begin interval')" :rules="[rules]"
                    variant="solo">
      </v-text-field>
    </v-col>
    <v-col>
      <v-text-field v-model="config.randomProduct.end_interval" :placeholder="tr('end interval')" :rules="[rules]"
                    variant="solo">
      </v-text-field>
    </v-col>
    <v-col>
      <v-text-field v-model="config.randomProduct.nb_products" :placeholder="tr('nb products')"
                    :rules="[nbProductsRules]" variant="solo">
      </v-text-field>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "RandomProducts",
  data: () => ({}),
  computed: {
    config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (!currentQuestion.config.randomProduct) {
          currentQuestion.config.randomProduct = {begin_interval: null, end_interval: null, nb_products: 1};
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
      const {begin_interval, end_interval} = this.config.randomProduct;
      if (!/^\d+$/.test(begin_interval) || !/^\d+$/.test(end_interval)) {
        return 'Product properties must be numbers';
      }

      return parseFloat(begin_interval) < parseFloat(end_interval) || 'Begin interval value must be inferior to end interval value';
    },
    nbProductsRules() {
      const {nb_products} = this.config.randomProduct;

      if (!nb_products) {
        return 'Products number required';
      }

      if (!/^\d+$/.test(nb_products) || nb_products < 1) {
        return 'nb products must be a positive number';
      }
    }

  }
};
</script>
