<template>
    <button @click="resetPlan" type="button" class="btn">{{tr('Reset plan')}}</button>
</template>

<script>
export default {
    methods: {
        resetPlan() {
            this.$store.payment.plan = null;
        }
    }
}
</script>

<style scoped src="./styles/payment-button.css"></style>
