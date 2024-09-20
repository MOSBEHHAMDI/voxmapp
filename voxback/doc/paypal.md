# PayPal Braintree

## Development: Braintree Sandbox

### Setting up the backend

1. Create a sandbox account in [Braintree](https://www.braintreepayments.com/sandbox)
1. Copy your merchant id, public key and private key from the [Braintree Gateway](https://sandbox.braintreegateway.com/)
    - from the home page when you open the dashboard before any transactions or
    - from Settings (gear icon) > API > API Keys > Private Key > View
1. Add them to .env.local

### Link Braintree Sandbox with PayPal Sandbox

1. Create a PayPal account (or use an existing account)
1. Visit the [PayPal Developer Dashboard](https://developer.paypal.com/dashboard/)
1. Go to Testing Tools > Sandbox Accounts
1. Create a Buisness Sandbox account having France as its country (click Create account)
1. Go to Apps & Credentials
1. Create an app having Merchant as its type and using the Buisness Sandbox account created (click Create App)
1. Open [Braintree Gateway](https://sandbox.braintreegateway.com/) (in a new tab)
1. Navigate to Settings (gear icon) > Processing
1. Click Link Sandbox in the PayPal section
1. Fill the fields with the details from the PayPal Rest API app
    - from the page that you were redirected to after creating the app in PayPal or
    - from Apps & Credentials > REST API apps > the app created > the three dots menu > Edit
1. Confirm (click Link PayPal Sandbox) (the linking could take a while)

### Adding a plan

1. Visit the braintree dashboard
1. Navigate to Subscriptions > Plans
1. Add a new plan (+ New Plan)
1. Copy the id of the new plan
1. Replace the existing value for the prop plan-id for the component SelectPlan in the view PaymentView (voxfront/src/web/views/PaymentView.vue)

### Testing

1. Run the servers
1. Login to the website (Voxmapp)
1. Visit the [payment page](http://localhost:3000/payment)
1. Choose a plan (Buy Now button)
1. Choose payment method (PayPal or credit card)
1. Fill the fields
    - PayPal persoanl sandbox account or
    - Credit card from Braintree's [Testing and Go Live](https://developer.paypal.com/braintree/docs/guides/credit-cards/testing-go-live/php) page
1. Confirm (click Confirm and Buy) if the operation was successful an object with the key subscription will be logged to the console

## Production: Braintree Direct

### Creating an Account

1. Contact [Braintree's sales team](https://www.braintreepayments.com/contact/sales) for more information

### Adjusting the code

1. Change the Braintree ENVIRONMENT to production in the .env file
1. Add the merchant id, public key and private key of the production account to the .env file
1. remove env: "sandbox" in the PaymentForm component (voxfront/src/web/components/payment/PaymentForm.vue) there is a TODO next to the line to be removed
