<template>
    <button @click="onClick" type="button" class="btn">{{tr('Confirm and Buy')}}</button>
</template>

<script>
export default {
    methods: {
        async onClick() {
            if (!this.$store.payment.nonce || !this.$store.payment.plan) {
                console.warn("missing nonce or plan id");
                return;
            }

            try {
                const payment = { ...this.$store.payment };
                const subscription = {
                    nonce: payment.nonce,
                    plan: payment.plan,
                }
                this.$store.payment.nonce = null;
                const { data } = await this.$axios.post(
                    "payment/subscribe",
                    subscription
                );

                console.log(data);

                // TODO: add routing to next page
            } catch (error) {
                // TODO: add better error handling
                console.error(error);
            }
        },
    }
}
</script>

<style scoped src="./styles/payment-button.css"></style>
