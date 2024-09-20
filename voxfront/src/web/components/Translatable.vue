<template>
  <v-row>
     <v-overlay
        :model-value="overlay"
        class="align-center justify-center"
    >
      <v-progress-circular
          color="primary"
          indeterminate
          size="64"
      ></v-progress-circular>
    </v-overlay>
    <v-col class="ma-auto">
      <v-textarea
          v-model="internalText"
          :rows="rowsNumber"
          :rules="[rules]"
          :hide-details="hide_details"
          class="rounded special_input"
          density="compact"
          :placeholder="placeholder"
          variant="solo"
          @change="checkTranslation()">
        <template v-slot:append-inner>
          <div class="d-flex justify-space-around">
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-icon color="primary" size="25" v-bind="props">mdi-web</v-icon>
                <span class="custom-icon pl-1 main" v-bind="props"> {{ translatable_current_language.toUpperCase() }}</span>
              </template>
              <v-list>
                <v-list-item v-for="(language, index) in languagesList"
                             :key="index"
                             :value="index"
                             @click="translate(language)">
                  <v-list-item-title>{{ language.name }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </div>
        </template>
      </v-textarea>
    </v-col>
  </v-row>
</template>
<script>
export default {
  name: "Translatable",
  props: ['languages', 'rowsNumber', "variant", "placeholder", 'hide_details'],
  data() {
    return {
      translatable_current_language: "",
      languagesList: [],
      translationObject: null,
      overlay: false,
    };
  },
  async created() {
    await this.$store.getHomePageInfo()
    this.translatable_current_language = this.$utils.currentLanguage().code;
    this.languagesList = this.languages && this.languages.length ? this.languages : this.$store.home_page_info.languages;
    if (this.$attrs.modelValue) {
      this.translationObject = this.translations.find(tr => tr.id === this.$attrs.modelValue.id)
    }
    console.log('In translatable')
    console.log(this.$attrs.modelValue)
    if (!this.translationObject) {
      this.translationObject = this.$utils.setNewTranslation()
    }
    console.log(this.translationObject)
  },
  computed: {
    translations() {
      return this.$store.home_page_info.translations
    },
    internalText: {
      get() {
        if (!this.translationObject) {
          return ''
        }
        const parsedObj = this.translationObject.translation;
        return parsedObj && parsedObj[this.translatable_current_language] ? parsedObj[this.translatable_current_language] : "";
      },
      set(value) {
        this.translationObject.translation[this.translatable_current_language] = value
      }
    },
    rules() {
      const currentLanguage = this.$utils.currentLanguage();
      if (this.translatable_current_language === currentLanguage.code && this.internalText === '') {
        return this.tr(`You need to define the ${currentLanguage.name} Translation`)
      }
      return true
    }
  },
  methods: {
    translate(language) {
      this.translatable_current_language = language.code
    },
    async checkTranslation() {
      let found = false;
      for (const [key, tr] of Object.entries(this.translations)) {
        if (tr.translation[this.$utils.currentLanguage().code] === this.translationObject.translation[this.$utils.currentLanguage().code]) {
          console.log('found in position: ', key)
          this.translationObject = tr
          found = true;
          break;
        }
      }
      if (!found) {
        this.overlay = true;
        await this.$store.saveTranslation(this.translationObject).then(() => {
          this.overlay = false;
        });
      }
      this.$emit('update:modelValue', {id: this.translationObject.id});
    }
  }

};
</script>
<style scoped>
.custom-icon {
  font-size: 18px;
  color: #1976d2;
}
</style>