<template>
  <v-row v-if="showGalleries">
    <v-col cols="4">
      <v-virtual-scroll
          :height="900"
          :items="downloads_array"
      >
        <template v-slot:default="{ item }">
          <v-card
              :image="item"
              class="mx-auto"
              color="primary"
              height="240"
              rounded
              theme="light"
              @click="selected = item"
          ></v-card>
        </template>
      </v-virtual-scroll>

    </v-col>
    <v-col>
      <v-sheet
          color="secondary" height="100%" width="100%"
      >
        <v-card
            :image="selected"
            class="mx-auto"
            color="primary"
            height="480"
            rounded
            theme="light"
        ></v-card>
      </v-sheet>
    </v-col>
  </v-row>
  <v-carousel
      v-if="!showGalleries"
      height="400"
      hide-delimiter-background
      show-arrows="hover"
      style="background: #bdc3c899"
  >
    <v-carousel-item v-if="downloads_array.length === 0">
      <div class="pa-2 bg-white">
        <v-img src="https://uploads-ssl.webflow.com/61b3866703ef91bc73f008c9/61c2162f8a6c4f1002917caf_about-hero1.svg"/>
      </div>
    </v-carousel-item>
    <v-carousel-item v-for="(upload) in downloads_array"
                     v-else
    >
      <div class="text-h2 bg-white">
        <v-img
            :src="upload"
        ></v-img>
      </div>
    </v-carousel-item>
  </v-carousel>
</template>
<script>
export default {
  name: "Slider",
  props: ['showGalleries', 'downloads'],
  components: {},
  data: () => ({
    selected: null,
  }),
  computed: {
    downloads_array() {
      const files = Object.entries(this.downloads).map(file => file[1]);
      if (!this.selected && files.length) {
        this.selected = files[0]
      }
      return files
    }
  }
}
</script>
