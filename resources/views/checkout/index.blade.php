@extends('layouts.master')

@section('extra-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('extra-script')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="col-md-12">
        <h1>Page de paiement</h1>
        <div class="row">
            <div class="col-md-6">
               <form action="{{ route('checkout.store')}}" method="POST" class="my-4" id="payment-form">
                @csrf
                <div id="card-element"><!--Stripe.js injects the Card Element-->
                
                </div>
                <div id="card-error" role="alert"></div>

                <button id="submit" class="btn btn-success mt-4">Procéder au paiement ({{ getPrice($total) }})</button>              
               </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        var stripe = Stripe("pk_test_51I8TcLCkVjIrwo7CiOUjQvfK5xrSiBE4fmtHXlF7gBGFyVzMIubaVl2GlJib9h8JM9AlGVPsSFN3BUfk23otKDhP00Qa5hFL7P");
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                color: "#32325d"
                }
                },
            invalid: {
                fontFamily: 'Arial, sans-serif',
                color: "#fa755a",
                iconColor: "#fa755a"
                    }
                    };
    var card = elements.create("card", { style: style });
    // Stripe injects an iframe into the DOM
    card.mount("#card-element");
    card.addEventListener('change', ({error}) => {
    const displayError = document.getElementById('card-errors');
        if(error) {
            displayError.classList.add('alert','alert-warning');
            displayError.textContent = error.message;
        } else {
            displayError.classList.remove('alert', 'alert-warning');
            displayError.textContent = '';
        }
    });

    var submitButton = document.getElementById('submit');

    submitButton.addEventListener('click', function(ev) {
        ev.preventDefault();
        submitButton.disabled = true;
        stripe.confirmCardPayment("{{ $clientSecret}}", {
            payment_method: {
                card: card,
            }
        }).then(function(result) {
            if(result.error) {
                submitButton.disabled = false;
                console.log(result.error.message);
            } else {
                if(result.paymentIntent.status === 'succeeded') {
                    var paymentIntent = result.paymentIntent;
                    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    var form = document.getElementById('payment-form');
                    var url = form.action;
                    var redirect = '/merci';

                    fetch(
                        url,
                        {
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json, text-plan, */*",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": token
                            },
                            method: 'post',
                            body: JSON.stringify({
                                paymentIntent: paymentIntent
                            })
                        }).then((data) => {
                            if (data.status === 400) {
                                var redirect = '/boutique';
                            } else {
                                var redirect = '/merci';
                            }
                        console.log(data);
                        form.reset();
                        window.location.href = redirect;
                    }).catch((error) => {
                        console.log(error)
                    })
                }
            }
        });
    });

    </script>
@endsection