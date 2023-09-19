@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        @lang('product/texts.you_will_be_charged') ${{ number_format($product->price, 2) }}
                        @lang('product/texts.for_product') {{ $product->name }}
                        <h3>{{$product->product_type}}</h3>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'purchase.create', 'method' => 'POST', 'id' => 'payment-form']) !!}
                        {{ csrf_field() }}
                        {{ Form::hidden('product', $product->id, ['id' => 'product']) }}
                        <div class="row">
                            <div class="col-xl-4 col-lg-4">
                                <div class="form-group">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', old('name'), [
                                              'id' => 'card-holder-name',
                                              'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                              'placeholder' => 'Name on the card']) }}
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                    <div id="name-error-message" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">@lang('product/texts.card_details')</label>
                                    <div id="card-element"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <hr>
                                {{ Form::button('Purchase', [
                                     'type' => 'submit', 'class' => 'btn btn-primary',
                                     'id' => 'card-button', 'data-secret' => $intent->client_secret]) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
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

        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            const nameFieldValue = cardHolderName.value;
            if (!nameFieldValue.trim()) {
                const errorElement = document.getElementById('name-error-message')
                errorElement.textContent = 'Name is required'
                return;
            }
            const errorElement = document.getElementById('name-error-message')
            errorElement.textContent = ''

            cardBtn.disabled = true
            const {setupIntent, error} = await stripe.confirmCardSetup(
                cardBtn.dataset.secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            )

            if (error) {
                cardBtn.disabled = false
            } else {
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
