<template>
  <div id="mobile_app">
    <MainLayout>
      <router-view/>
    </MainLayout>
  </div>
</template>

<script>
import MainLayout from "@/mobile/layouts/MainLayout.vue";

export default {
  components: {MainLayout}

}
</script>

<style>
#mobile_app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
