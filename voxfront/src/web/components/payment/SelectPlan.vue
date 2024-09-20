<template>
    <button @click="selectPlan" type="button" class="btn">{{tr('Buy now')}}</button>
</template>

<script>
export default {
    props: [
        'planId'
    ],
    methods: {
        selectPlan() {
            this.$store.payment.plan = this.planId ?? null;
            // TODO: add routing to next page
        }
    }
}
</script>

<style scoped src="./styles/payment-button.css"></style>
