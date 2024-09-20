<template>
    <PaymentForm />
    <div class="btn-wrapper">
        <SubscriptionConfirmation />
        <SelectPlan plan-id="5fc2" />
        <ResetPlan />
    </div>
</template>

<!-- TODO: run `npm install braintree-web paypal-checkout` -->

<script>
import { PaymentForm, SubscriptionConfirmation, SelectPlan, ResetPlan } from '../components/payment';

export default {
    components: {
        PaymentForm,
        SubscriptionConfirmation,
        SelectPlan,
        ResetPlan,
    }
}
</script>

<style scoped>
.btn-wrapper {
    display: grid;
    justify-items: center;
    gap: 0.5rem;
}
</style>