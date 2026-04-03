@extends('layouts.app')

@section('content')
<div class="container">

    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf

        <div class="form-group">
            <label for="gateway">Choose a payment method:</label>
            <select name="gateway" id="gateway" class="form-control">
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
            </select>
        </div>

        <!-- PayPal payment method details (you can customize these fields as needed) -->
        <div id="paypal-details" class="payment-details" style="display: none;">
            <h3>PayPal Details</h3>
            <p>You will be redirected to PayPal to complete your payment.</p>
        </div>

        <!-- Stripe payment method details -->
        <div id="stripe-details" class="payment-details" style="display: none;">
            <h3>Stripe Details</h3>
            <div id="stripe-button-container">
                <!-- Stripe button will be dynamically injected by Stripe.js -->
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Proceed</button>
        </div>
    </form>
</div>
@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Show/hide payment details based on the selected gateway
    document.getElementById('gateway').addEventListener('change', function() {
        let selectedGateway = this.value;

        if (selectedGateway === 'paypal') {
            document.getElementById('paypal-details').style.display = 'block';
            document.getElementById('stripe-details').style.display = 'none';
        } else if (selectedGateway === 'stripe') {
            document.getElementById('stripe-details').style.display = 'block';
            document.getElementById('paypal-details').style.display = 'none';

            // Initialize Stripe Elements for Stripe payment form
            var stripe = Stripe("{{ env('STRIPE_PUBLIC_KEY') }}");
            var elements = stripe.elements();
            var style = {
                base: {
                    color: "#32325d",
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4"
                    }
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            };

            var card = elements.create("card", {
                style: style
            });
            card.mount("#stripe-button-container");

            // When the form is submitted, handle the Stripe payment
            document.querySelector("form").addEventListener("submit", function(event) {
                event.preventDefault();

                // Call Stripe's createPaymentMethod method
                stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                }).then(function(result) {
                    if (result.error) {
                        // Handle error here (e.g., invalid card details)
                        alert(result.error.message);
                    } else {
                        // Add the PaymentMethod ID to the form before submission
                        var paymentMethodInput = document.createElement("input");
                        paymentMethodInput.type = "hidden";
                        paymentMethodInput.name = "payment_method_id"; // Ensure the name matches the input you're accessing in the controller
                        paymentMethodInput.value = result.paymentMethod.id;
                        document.querySelector("form").appendChild(paymentMethodInput);

                        // Now submit the form
                        document.querySelector("form").submit();
                    }
                });
            });
        }
    });

    // Trigger change to show the correct payment details based on default selection
    document.getElementById('gateway').dispatchEvent(new Event('change'));
</script>
@endsection