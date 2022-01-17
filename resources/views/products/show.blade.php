@extends('layouts.master')

@section('content')
    <div class="col-md-12">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
            <div class="badge badge-pill badge-info">{{ $stock }}</div>
            <h5 class="mb-0">{{ $product->title }}</h5>
            <hr>
            <p class="mb-auto text-muted">{!! $product->description !!}</p>
            <strong class="mb-auto font-weight-normal text-secondary">{{ $product->getPrice() }}</strong>
            
            @if ($stock === 'Disponible')
                <form action="{{ route('cart.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-dark">Ajouter au panier</button>
                </form>
            @endif
        </div>
        <div class="col-auto d-none d-lg-block">
            <img src="{{ asset('storage/' . $product->image)}}" alt="">
            <div class="mt-2">
                @if ($product->images)
                <img src="{{ asset('storage/' . $product->image)}}" alt="" class="img-thumbnail" width="50">
                    @foreach (json_decode($product->images, true) as $image)
                        <img src="{{ asset('storage/' . $image)}}" width="50" class="img-thumbnail">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection

@section('extra-js')
    <script>
        var mainImage = document.querySelector('mainImage');
        var thumbnails = document.querySelectorAll('.img-thumbnail');

        thumbnails.forEach((element) => element.addEventListener('click', changeImage));

        function changeImage(e) {
            mainImage.src = this.src;
        }
    </script>
@endsection