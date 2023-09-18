@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        You will be charged ${{ number_format($product->price, 2) }} for Product - {{ $product->name }}
                        <h3>{{$product->product_type}}</h3>
                    </div>

                    <div class="card-body">

                        <form id="payment-form" action="{{ route('purchase.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product" id="product" value="{{ $product->id }}">
                            <input type="text" name="card-number-55555" id="card-number-55555" value="4242424242424242">
                            <input type="text" name="last-four-digits" id="last-four-digits" value="">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="">Card details</label>
                                        <div id="card-element"></div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <hr>
                                    <button type="submit" class="btn btn-primary" id="card-button" data-secret="{{ $intent->client_secret }}">Purchase</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('cashier.stripe.key')}}')

        const elements = stripe.elements()
        const cardElement = elements.create('card')

        cardElement.mount('#card-element')

        const form = document.getElementById('payment-form')
        const cardBtn = document.getElementById('card-button')
        const cardHolderName = document.getElementById('card-holder-name')
        const lastFourDigitsInput = document.getElementById('last-four-digits');
        const lastFourDigitsDisplay = document.getElementById('last-four-digits-display');

        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            cardBtn.disabled = true
            const { setupIntent, error } = await stripe.confirmCardSetup(
                cardBtn.dataset.secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            )

            if(error) {
                cardBtn.disable = false
            } else {

                const lastFourDigits = setupIntent.payment_method.substr(-4);
                lastFourDigitsInput.value = lastFourDigits;
                console.log(lastFourDigits);
              /*  // Use setupIntent.payment_method to submit the payment
                const paymentMethodId = setupIntent.payment_method;

                // Retrieve the payment method details from Stripe
                const paymentMethod = await stripe.retrievePaymentMethod(paymentMethodId);

                if (paymentMethod && paymentMethod.card && paymentMethod.card.last4) {
                    // Extract the last 4 digits of the card number
                    const lastFourDigits = paymentMethod.card.last4;

                    // Set the last 4 digits in the hidden input field
                    lastFourDigitsInput.value = lastFourDigits;

                    // Display the last 4 digits in the HTML element
                    lastFourDigitsDisplay.textContent = `Last 4 Digits: ${lastFourDigits}`;
                    console.log(lastFourDigits);
                }*/

                let token = document.createElement('input')
                token.setAttribute('type', 'hidden')
                token.setAttribute('name', 'token')
                token.setAttribute('value', setupIntent.payment_method)
                form.appendChild(token)
                form.submit();
            }
        })
    </script>
@endsection
