<template>
  <v-carousel
      height="200"
      hide-delimiter-background
      show-arrows="hover"
      style="background: #bdc3c899"
  >
    <v-carousel-item v-if="downloadedFiles.length === 0">
      <div class="pa-2 bg-white">
        <v-img src="https://uploads-ssl.webflow.com/61b3866703ef91bc73f008c9/61c2162f8a6c4f1002917caf_about-hero1.svg"/>
      </div>
    </v-carousel-item>
    <v-carousel-item v-for="(file, i) in downloadedFiles"
                     v-else
                     :key="i"
    >
      <div class="text-h2 bg-white">
        <v-img
            :src="file"
        ></v-img>
      </div>
    </v-carousel-item>
  </v-carousel>
</template>
<script>
export default {
  data: () => ({
    downloadedFiles: [],
  }),
  created() {
    this.downloadedFiles = this.getDownloadedFiles(this.$dataPoint_store.currentDataPoint.uploads);
  }
}
</script>
