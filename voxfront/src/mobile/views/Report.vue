<template>
  <v-form class="pale-bg" @submit.prevent="saveReport">
    <v-card class="pl-5 pr-5 rounded-xl pt-4 pb-4 mt-2 text-left pale-bg" elevation="0">
      <v-card-text class="bg-white rounded-xl pa-2">
        <span class="ml-3 font-weight-bold">{{ tr('Report Type') }}</span>
        <v-select
            v-model="$chat_store.currentReport.type"
            :items="reportTypes"
            :placeholder="tr('Choose...')"
            :rules="[global_rules.required]"
            append-inner-icon="mdi-chevron-down"
            class="mr-3 ml-3 mt-2"
            clearable
            density="compact"
            item-title="type"
            menu-icon=""
            return-object
            variant="outlined"
        ></v-select>
      </v-card-text>
    </v-card>
    <v-card class="pl-5 pr-5 rounded-xl pt-1 pb-4 mt-2 text-left pale-bg" elevation="0">
      <v-card-text class="bg-white rounded-xl pa-2">
        <span class="ml-3 font-weight-bold">{{ tr('Report Description') }}</span>
        <v-textarea v-model="$chat_store.currentReport.description"
                    :placeholder="tr('Describe your report')"
                     :rules="[global_rules.required]"
                    class="mt-2"
                    density="compact"
                    variant="outlined"
        />
      </v-card-text>
    </v-card>
    <v-card class="pl-5 pr-5 rounded-xl pt-1 pb-4 mt-2 text-left pale-bg" elevation="0">
      <v-card-text class="bg-white rounded-xl pa-2">
        <div class="ml-3 mb-4 font-weight-bold">{{ tr('Upload photo/video') }}</div>
        <span class="ml-3 mb-3">
         {{ tr('Short description of image type required') }}
       </span>
        <v-card class="mt-3 text-white bg-orange rounded-xl mb-2 ml-2 mr-2">
          <template #append>
            <v-icon size="35">mdi-camera-outline</v-icon>
          </template>
          <template #title>
     <span>  Choose
      Photo/Video</span>
          </template>
        </v-card>
      </v-card-text>
    </v-card>

    <v-card class="pl-5 pr-5 rounded-xl pt-1 pb-15 mt-2 text-left pale-bg" elevation="0">
      <v-card-text class="bg-white rounded-xl pa-2">
        <div class="ml-3 mb-4 font-weight-bold">{{ tr('Upload file') }}</div>
        <span class="ml-3 mb-3">
         {{ tr('Short description') }}
       </span>
        <v-card class="mt-3 text-white bg-orange rounded-xl mb-2 ml-2 mr-2">
          <template #append>
            <v-icon size="35">mdi-file-outline</v-icon>
          </template>
          <template #title>
            <span>  Choose a file</span>
          </template>
        </v-card>
      </v-card-text>
    </v-card>
    <v-card class="fixedBtn bg-orange ml-6" max-width="90%">
      <v-btn block class="rounded-8 text-white pa-5" elevation="0" type="submit" variant="solo">
        {{ tr('Send') }}
      </v-btn>
    </v-card>
  </v-form>
</template>
<script>

import Translatable from "@/web/components/Translatable.vue";

export default {
  name: "Report",
  components: {Translatable},
  created() {
    const currentLanguage = this.$utils.currentLanguage()
  },
  data: () => ({}),
  computed: {
    reportTypes() {
      return ["Timing", "Remaing task", "Wrong location"]
    }
  },
  methods: {
    async saveReport(event) {
      const result = await event;
      if (result && result.valid) {
        this.$chat_store.saveReport();
        this.$utils.goBack();
      }
    }
  }
}
</script>
<style scoped>
.fixedBtn {
  padding: 5px 20px;
  width: 100%;
  position: fixed;
  bottom: 5px;
  text-align: center;
}

.fixedText {
  position: fixed;
}
</style>
