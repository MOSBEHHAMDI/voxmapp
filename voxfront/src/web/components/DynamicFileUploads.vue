<template>
  <v-card class="main-bg pa-0 text-capitalize text-white pa-5 mb-8" color="transparent" elevation="0">
    <v-card-title class="text-center pb-8">
      <v-btn elevation="0" variant="tonal" @click="addNewItem"> New upload</v-btn>
    </v-card-title>
    <v-card-text class="pl-0 pr-0 pt-0 pb-5">
      <v-row class="pa-0">
        <v-container>
          <Container class="mt-1 mb-1" dragHandleSelector=".column-drag-handle"  @drop="(event) => $utils.applyDrag( fileInputList, event)">
            <Draggable v-for="(item, i) in fileInputList" :key="i">
              <v-row>
                <v-col cols="1">
                  <v-icon class="column-drag-handle mt-1" size="35">mdi-menu</v-icon>
                </v-col>
                <v-col cols="8">
                  <v-file-input
                      v-model="item.content"
                      :label="item.name"
                      variant="solo"
                  ></v-file-input>
                </v-col>
                <v-col>
                  <v-btn block color="secondary" elevation="0" min-height="41" variant="tonal"
                         @click="removeItem(i)">
                    {{ tr('Remove') }}
                    <v-icon class="ml-1" size="25">mdi-close-circle-outline</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </Draggable>
          </Container>
        </v-container>
        <v-col></v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import {Container, Draggable} from "vue3-smooth-dnd";

export default {
  name: "DynamicFileUploads",
  components: {Container, Draggable},
  data: () => ({
    fileInputList: [{name: "Upload file", content: null}],
  }),

  methods: {
    addNewItem() {
      const newFileInput = {name: "Upload the new file " + (this.fileInputList.length + 1), content: null};
      this.fileInputList.push(newFileInput)
    },
    removeItem(index) {
      this.fileInputList.splice(index, 1);
    },


  }
}
</script>

