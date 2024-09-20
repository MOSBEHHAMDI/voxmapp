<template>
  <div>
    <vue-editor v-model="value"
                ref="editor"
                :editorOptions="editorOptions"
                :editorToolbar="toolbar"
                v-on:text-change="textChange">
    </vue-editor>
  </div>
</template>
<script>
import {VueEditor} from "vue3-editor";
import 'quill/dist/quill.bubble.css'

export default {
  data: () => ({
    value: '',
    editorOptions: {theme: 'bubble'},
    toolbar: [[{
      header: [false, 1, 2, 3, 4, 5, 6]
    }], ["bold", "italic", "underline", "strike"], // toggled buttons
      [{
        align: ""
      }, {
        align: "center"
      }, {
        align: "right"
      }, {
        align: "justify"
      }], ["blockquote", "code-block"], [{
        list: "ordered"
      }, {
        list: "bullet"
      }, {
        list: "check"
      }], [{
        indent: "-1"
      }, {
        indent: "+1"
      }], // outdent/indent
      [{
        color: []
      }, {
        background: []
      }], // dropdown with defaults from theme
      ["link", "image"], ["clean"] // remove formatting button
    ]
  }),
  components: {
    VueEditor
  },
  created() {
    this.value = this.modelValue
  },
  methods: {
    textChange() {
      this.$emit('update:modelValue', this.$refs.editor.quill.getHTML())
    }
  },
  props: ['modelValue']
}
</script>

<style>
.ql-tooltip {
  left: 0 !important;
  z-index: 100;
}
</style>