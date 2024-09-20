<template>
  <v-row>
    <v-col>
      <span>
        <span class="main" style="font-size: 20px">{{title ?? ''}}</span>
        <v-btn elevation="0" color="primary" class="float-end" @click="addNewItem">Add New {{ itemName }}</v-btn>
      </span>
    </v-col>
  </v-row>
  <v-container class="pl-0 pr-0">
    <Container dragHandleSelector=".column-drag-handle" @drop="onDrop" class="mt-1 mb-1">
      <Draggable v-for="(item, index) in list" :key="index">
        <v-row>
          <v-col cols="1" v-if="sortable">
            <v-icon class="column-drag-handle mt-1" size="35">mdi-menu</v-icon>
          </v-col>
          <v-col cols="8">
           <slot :item="item" :index="index"></slot>
          </v-col>
          <v-col>
            <v-tooltip :text=" tr('Remove') ">
              <template v-slot:activator="{ props }">
                <v-icon v-bind="props" @click="removeItem(index)">
                  mdi-close-circle
                </v-icon>
              </template>
            </v-tooltip>
          </v-col>
        </v-row>
      </Draggable>
    </Container>
  </v-container>
</template>
<script>
import {Container, Draggable} from "vue3-smooth-dnd";

export default {
  name: "DynamicList",
  components: {
    Container, Draggable
  },
  props: ["list", "itemName","itemValue","sortable", 'title'],
  emits: ['update:list'],
  data: () => ({
    title: ''
  }),
  methods: {
    onDrop(dropResult) {
      const updatedList = this.applyDrag(this.list, dropResult);
      this.$emit('update:list', updatedList);
    },
    applyDrag(arr, dragResult) {
      const {removedIndex, addedIndex, payload} = dragResult;
      if (removedIndex === null && addedIndex === null) return arr;
      const result = [...arr];
      let itemToAdd = payload;
      if (removedIndex !== null) {
        itemToAdd = result.splice(removedIndex, 1)[0];
      }
      if (addedIndex !== null) {
        result.splice(addedIndex, 0, itemToAdd);
      }
      return result;
    },
    addNewItem() {
      this.$emit('update:list', [...this.list,this.itemValue]);
    },
    removeItem(index) {
      const updatedTableData = [...this.list];
      updatedTableData.splice(index, 1);
      this.$emit('update:list', updatedTableData);
    },
  },
};
</script>

<style>
.column-drag-handle{
  cursor: grab;
}
.column-drag-handle:active{
  cursor: grabbing;
}
</style>
