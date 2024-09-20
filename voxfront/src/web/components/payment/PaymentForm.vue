<template>
    <div class="payment-frame">
        <div class="payment-method-radio">
            <div>
                <input type="radio" name="payment-method" id="credit-card" v-model="paymentMethod" value="credit-card"
                    class="radio" checked>
                <label for="credit-card"><img src="https://upload.wikimedia.org/wikipedia/commons/f/f3/Credit_card.svg"
                        alt="Credit Card" class="label-logo"></label>
            </div>
            <div>
                <input type="radio" name="payment-method" id="paypal" v-model="paymentMethod" value="paypal" class="radio">
                <label for="paypal"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="Paypal"
                        class="label-logo"></label>
            </div>
        </div>
        <form action="/" method="post" class="form" v-show="paymentMethod === 'credit-card'">
            <div class="form-group full-width">
                <label class="hosted-fields--label" for="card-number">{{tr('Card Number')}}</label>
                <div id="card-number" class="hosted-field"></div>
            </div>

            <div class="form-group">
                <label class="hosted-fields--label" for="expiration-date">{{tr('Expiration Date')}}</label>
                <div id="expiration-date" class="hosted-field"></div>
            </div>

            <div class="form-group">
                <label class="hosted-fields--label" for="cvv">{{tr('CVV')}}</label>
                <div id="cvv" class="hosted-field"></div>
            </div>

            <div class="form-group">
                <label class="hosted-fields--label" for="input-11">{{tr('Country')}}</label>
                <div id="country-selector" class="country-field">
                    <CountrySelector v-if="isLoaded" v-model="country" class-name="country" />
                </div>
            </div>

            <div class="form-group">
                <label class="hosted-fields--label" for="postal-code">{{tr('Postal Code')}}</label>
                <div id="postal-code" class="hosted-field"></div>
            </div>

            <div class="full-width">
                <button @click="hostedFieldsHandler(hostedFieldsInstance)" type="button" class="btn" id="purchase-button"
                    :disabled="!isLoaded">{{tr('Next')}}</button>
            </div>
        </form>
        <div class="button-container" id="paypal-button-container" v-show="paymentMethod === 'paypal'"></div>
        <div class="error" v-show="error !== null">{{ error }}</div>
    </div>
</template>

<script>
import { client, hostedFields, paypalCheckout } from "braintree-web";
import { Button } from "paypal-checkout";
import CountrySelector from "../CountrySelector.vue";

export default {
    data() {
        return {
            hostedFieldsInstance: null,
            paymentMethod: "credit-card",
            isLoaded: false,
            country: null,
            error: null,
        };
    },
    async created() {
        try {
            const authorization = await this.getAuthorization();

            const clientInstance = await client.create({
                authorization: authorization
            });

            this.hostedFieldsInstance = await hostedFields.create({
                client: clientInstance,
                styles: {
                    "input": {
                        "font-size": "16px",
                        "font-family": "courier, monospace",
                        "font-weight": "lighter",
                        "color": "#ccc"
                    },
                    ":focus": {
                        "color": "black"
                    },
                    ".valid": {
                        "color": "black",
                        "font-weight": "600"
                    }
                },
                fields: {
                    number: {
                        selector: "#card-number",
                        placeholder: "4111 1111 1111 1111"
                    },
                    cvv: {
                        selector: "#cvv",
                        placeholder: "123"
                    },
                    expirationDate: {
                        selector: "#expiration-date",
                        placeholder: "MM/YYYY"
                    },
                    postalCode: {
                        selector: "#postal-code",
                        placeholder: "11111"
                    }
                }
            });

            this.isLoaded = true;

            const paypalCheckoutInstance = await paypalCheckout.create({
                client: clientInstance
            });

            Button.render({
                env: "sandbox", // TODO: remove in production
                style: {
                    label: "paypal",
                    size: "responsive",
                    shape: "rect"
                },
                payment: () => {
                    return paypalCheckoutInstance.createPayment({
                        flow: "vault",
                        intent: "capture",
                    });
                },
                onAuthorize: this.paypalCheckoutHandler(paypalCheckoutInstance),
                // onCancel: (data) => {
                //     console.log(data);
                //     console.log("Payment Cancelled");
                // },
                onError: (err) => {
                    console.error(err);
                    this.error = "An error occurred while processing the paypal payment.\nPlease contact the administration if the problem persists.";
                }
            }, "#paypal-button-container");
        } catch (error) {
            console.error(error);
            this.error = "An error occurred while creating the payment interface.\nPlease contact the administration if the problem persists.";
        }
    },
    methods: {
        async getAuthorization() {
            try {
                const { data: { authorization } } = await this.$axios.get("payment/authorization");
                return authorization;
            } catch (error) {
                console.error(error);
                this.error = "Could not acquire an authorization token please make sure you are logged in or try again shortly.\nPlease contact the administration if the problem persists."
                return "";
            }
        },
        async hostedFieldsHandler(instance) {
            if (instance === null) {
                return;
            }
            this.error = null;

            try {
                const { nonce } = await instance.tokenize();
                this.$store.payment.nonce = nonce;

                // TODO: move to confirmation page
            } catch (error) {
                // TODO: add better error handling
                // console.error(error);
                this.error = "Sorry could not process the payment with the credit card.\nPlease contact the administration if the problem persists."
            }
        },
        paypalCheckoutHandler(instance) {
            if (instance === null) {
                return async () => { };
            }
            this.error = null;

            return async (data) => {
                try {
                    const { nonce } = await instance.tokenizePayment(data);
                    this.$store.payment.nonce = nonce;

                    // TODO: move to confirmation page
                } catch (error) {
                    // TODO: add better error handling
                    // console.error(error);
                    this.error = "Sorry could not process the payment with PayPal.\nPlease contact the administration if the problem persists."
                }
            };
        }
    },
    components: {
        CountrySelector: CountrySelector,
    }
}
</script>

<style scoped src="./styles/payment-button.css"></style>

<style scoped>
.payment-frame {
    display: grid;
    margin: auto
}

.payment-method-radio {
    display: flex;
    margin-bottom: 1.5em;
    justify-content: center;
}

.radio {
    appearance: none;
    border: 1px solid #1d1818;
    width: 1rem;
    aspect-ratio: 1;
    border-radius: 50%;
    transition: border 100ms linear;
}

.radio:hover {
    border-width: 2px;
}

.radio:checked {
    border-width: 0.3rem;
}

.label-logo {
    height: 1.5em;
    object-fit: cover;
    margin-inline: 0.5rem;
    transform: translateY(0.25rem);
}

.form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
    justify-self: center;
}

.form-group {
    display: grid;
}

.full-width {
    grid-column: 1 / -1;
}

.country-field {
    height: 2.5rem;
    border: 1px solid #707070;
    border-radius: 0.5rem;
    background: #fcfcfc;
    overflow-y: hidden;
    transition: all 300ms ease-in-out;
}

.country {
    --main-color: black;
    --v-input-padding-start: 0.5rem;
    font-family: courier, monospace;
    font-weight: 600;
}

@media (width < 40rem) {
    .form {
        grid-template-columns: 1fr;
        justify-self: auto;
    }
}

.hosted-field {
    height: 2.5rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid #707070;
    background: #fcfcfc;
    transition: all 300ms ease-in-out;
}

.hosted-fields--label {
    width: fit-content;
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
}

.braintree-hosted-fields-focused,
.country-field:focus-within {
    border: 1px solid #64d18a;
    outline: transparent;
    border-radius: 1px;
}

.braintree-hosted-fields-invalid {
    border: 1px solid #ed574a;
}

/* .braintree-hosted-fields-valid {} */

.error {
    color: black;
    background-color: tomato;
    max-width: 40rem;
    padding: 0.5rem;
    margin-inline: auto;
    margin-block: 0.5rem;
}
</style>