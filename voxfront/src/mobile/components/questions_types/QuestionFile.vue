<template>
  <div>
    <div v-if="question.config.file.type === 'File'" class="text-white text-left bg-orange pa-2 border-10 mt-2">
      Upload File
      <v-icon class="float-end">mdi-file-outline</v-icon>
    </div>
    <div v-else-if="question.config.file.type === 'Audio'" class="text-left pa-2 border-10 mt-2"> Audio Not supported yet
    </div>
    <div v-else class="text-white text-left bg-orange pa-2 border-10 mt-2" @click="uploadPicture= true">Choose
      Photo/Video
      <v-icon class="float-end">mdi-camera-outline</v-icon>
    </div>
    <v-bottom-sheet v-model="uploadPicture">
      <v-list lines="one">
        <v-list-item>
          <template v-slot:prepend>
            <v-icon>mdi-camera</v-icon>
          </template>
          <v-list-item-title>Take a photo</v-list-item-title>
        </v-list-item>
        <v-list-item>
          <template v-slot:prepend>
            <v-icon>mdi-image-outline</v-icon>
          </template>
          <v-list-item-title>Upload from device</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-bottom-sheet>
  </div>
</template>
<script>
export default {
  props: {
    question: {},
  },
  data() {
    return {
      uploadPicture: false
    }
  }
}
</script>

<style>

</style>