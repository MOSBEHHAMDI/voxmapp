<template>
  <div class="">
    <div class="tablet">
      <div class="content">
        <Questionnaire></Questionnaire>
      </div>
    </div>
  </div>
</template>

<script>
import Questionnaire from '@/mobile/views/Questionnaire.vue'

export default {
  components: {
    Questionnaire
  }
}
</script>

<style>

/* The device with borders */
.tablet {
  position: relative;
  width: 450px;
  height: calc(100vh - 120px);
  margin: auto;
  border: 16px black solid;
  border-top-width: 60px;
  border-bottom-width: 60px;
  border-radius: 36px;
}

/* The horizontal line on the top of the device */
.tablet:before {
  content: '';
  display: block;
  width: 60px;
  height: 5px;
  position: absolute;
  top: -30px;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #333;
  border-radius: 10px;
}

/* The circle on the bottom of the device */
.tablet:after {
  content: '';
  display: block;
  width: 35px;
  height: 35px;
  position: absolute;
  left: 50%;
  bottom: -65px;
  transform: translate(-50%, -50%);
  background: #333;
  border-radius: 50%;
}

.tablet .content {
  overflow: hidden;
  overflow-y: auto !important;
  max-height: 100%;
}
</style>