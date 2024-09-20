<template>
  <div style="width: 100%; height: 100%">
    <v-img :src="cached" v-bind="props"/>
  </div>
</template>

<script>
export default {
  name: "CachedImg",
  data() {
    return {
      cached: '',
      props: {}
    }
  },
  created() {
    const defaultPic = this.$attrs.defaultPic
    const src = this.$attrs.src
    this.props = JSON.parse(JSON.stringify(this.$attrs))
    delete this.props.src
    delete this.props.defaultPic
    this.$store.downloadFile(src, defaultPic).then((v) => {
      this.cached = v
    });
  }
}
</script>
