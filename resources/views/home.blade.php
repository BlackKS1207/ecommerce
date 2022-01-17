@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach (Auth()->user()->orders as $order)
                        <div class="card">
                            <div class="card-header">
                                Commande passé le {{ Carbon\Carbon::parse($ooder->payment_created_at)->format('d/m/Y à H:h')}} d'un montant de <strong>{{ getPrice($order->amount) }}</strong>
                            </div>
                            <div class="card-body">
                                <h6>Liste des produits</h6>
                                @foreach (unserialize($order->products) as $product)
                                    <div>Nom du produit: {{ $ptoduct[0] }}</div>
                                    <div>Prix: {{ getPice($product[1]) }}</div>
                                    <div>Quantité: {{ $product[2] }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
