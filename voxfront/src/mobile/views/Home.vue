<template>
  <div style="height: 100% !important;">
    <div class="main-bg text-center pa-8" style="height: 50vh !important;" @click="getBaseline()">
      <h3 class="text-white mt-5">Move To</h3>
      <img src="@/assets/icons/baseline_icon.png" width="80">
      <br>
      <img src="@/assets/logo/logo_text_white.png" width="130">
      <p class="font-weight-bold text-white mt-n3" style="font-size: 30px">BASELINE</p>
    </div>
    <div class="secondary-bg text-center pa-8" style="height: 50vh !important;">
      <h3 class="text-white mt-5">Move To</h3>
      <img src="@/assets/icons/feedback_icon.png" width="80">
      <br>
      <img src="@/assets/logo/logo_text_white.png" width="130">
      <p class="font-weight-bold text-white mt-n3" style="font-size: 30px">FEEDBACK</p>
    </div>
  </div>
</template>
<script>
export default {
  name: "Home",
  created() {
    // we need to fetch only necessary data such as languages ...
    this.$store.getMobileInfo();
    },
  methods: {
    getBaseline(){
      this.$questionnaire_store.getQuestionnairesInfo()
      this.$questionnaire_store.getQuestionnairesMobile()
      this.$router.push('/baseline')
    }
  }
}
</script>

