<template>
  <div id="web_app">
    <MainLayout>
      <router-view/>
    </MainLayout>
  </div>
</template>

<script>
import MainLayout from './layouts/MainLayout'

export default {
  components:{
    MainLayout
  }
}
</script>

<style>
#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
