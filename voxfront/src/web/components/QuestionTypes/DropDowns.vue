<template>
  <v-row class="mt-2 pa-0">
    <v-col class="pa-0">
      <div class="text-left mb-3 mt-3 white-bottom-border">
        <div class="pa-7">
          <v-row>
            <v-col cols="4"><v-btn color="success" class="border-8 mr-3 text-capitalize" elevation="0" @click="create()">Create</v-btn>
            <v-btn color="primary" class="border-8 mr-3 text-capitalize" elevation="0" @click="edit()">Edit</v-btn></v-col>
            <v-col cols="6"><v-text-field variant="filled" v-model="keyword" placeholder="search" density="compact" @input="search()"></v-text-field></v-col>
            <v-col cols="2"><v-btn color="danger" class="border-8 float-end text-capitalize" elevation="0" @click="remove()">Remove</v-btn></v-col>
          </v-row>
          <vue-tree ref="tree" :tree="initTree" class="tree" @blur="blur" @create="updateTree"
                    @quitedit="updateTree" @move="updateTree" @remove="updateTree"
          :defaultAttrs="{ style: { fontSize: '18px', indent: '40px', hoverBgColor: '#83c6ee', selectedBgColor: '#ffffff',  dragOverBgColor: '#59b5cc'}}" :fnAfterCalculate="fnAfterCalculate" />
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import VueTree from '@tinywisp/vue-tree'

export default {
  name: 'DropDowns',
  components: {
    VueTree
  },
  data() {
    return {
      tree: [],
      initTree: []
    }
  },
  created() {
    this.initTree = Object.assign({}, {0: this.config.tree || []})[0];
  },
  computed:{
    config: {
      get() {
        const {currentQuestion} = this.$questionnaire_store;
        if (!currentQuestion.config.tree) {
          currentQuestion.config.tree = [];
        }
        return currentQuestion.config;
      },
      set(config) {
        this.$questionnaire_store.currentQuestion.config = config;
      }
    },

  },
  methods: {
     fnAfterCalculate(node) {
      let tree = this.$refs.tree
      if (node.__.isVisible) {
        let bgColor = node.__.dpos % 2 === 1
                    ? 'lightblue'
                    : 'transparent';
        tree.setAttr(node, 'style', 'bgColor', bgColor)
      }
    },
    search() {
      let tree = this.$refs.tree
      if (this.keyword === '') {
        tree.clearSearchResult()
      } else {
        tree.search(this.keyword)
      }
    },
    blur(node) {
      let tree = this.$refs.tree
      tree.quitEdit(node)
    },
    create() {
      let tree = this.$refs.tree
      let node = tree.getSelectedOne()
      let child = {
        id: +new Date(),
        title: 'Item',
        hasChild: false
      }
      tree.create(child, node)
    },
    remove() {
      let tree = this.$refs.tree
      let node = tree.getSelectedOne()
      if (node)
        tree.remove(node)
    },
    edit() {
      let tree = this.$refs.tree
      let node = tree.getSelectedOne()
      if (node)
        tree.edit(node)
    },

    updateTree() {
      this.config.tree = this.updateNode(this.$refs.tree.getNestedTree());
    },
    updateNode(node) {
      return node.map(item => {
        // Exclude the "__" property
        let copy = Object.assign({}, item);
        delete copy.__;
        if (copy.hasChild) {
          copy.children = this.updateNode(copy.children); // Recursively update child nodes
        }
        return copy;
      });
    }
  }
}
</script>

<style scoped>
.panel .tree {
  width: 50%;
}

.btn {
  width: 100px;
  margin-right: 20px;
}
</style>