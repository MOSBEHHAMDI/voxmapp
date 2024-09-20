<template>
  <v-row>
    <v-col
        v-for=" (file,n) in downloadedFiles"
        class="d-flex child-flex"
        cols="4"
    >
      <v-img
          :src="file"
          aspect-ratio="16/9"
          cover
      ></v-img>

    </v-col>
  </v-row>
</template>
<script>
export default {
  name: "galleries",
    data: () => ({
      downloadedFiles: [],
  }),
  created() {
    this.downloadedFiles = this.getDownloadedFiles(this.$dataPoint_store.currentDataPoint.uploads)
  }
}
</script>

