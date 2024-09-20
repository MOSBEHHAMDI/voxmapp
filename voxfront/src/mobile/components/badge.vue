<template>
  <div
    :class="{ 'pr-2 pl-2' :number<10   }"   class="main-bg rounded-circle text-white pt-1 pb-1  font-weight-bold">
    {{ getNumber(number) }}
  </div>
</template>
<script>
export default {
  name: "badge",
  props: ['number'],
  methods: {
    getNumber(number) {
      return number > 100 ? '100 + ' : number
    },
  }
}
</script>
<style scoped>

</style>