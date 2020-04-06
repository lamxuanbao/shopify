<div class="row justify-content-md-center">
    @foreach($products as $product)
        <div class="col-md-4">
            <a href="{{route('detail', ['slug' => $product->handle])}}">
                <div class="card">
                    <img class="card-img-top" src="{{$product->main_image['src'] ?? null}}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{$product->title}}</h5>
                        <p class="card-text">{{$product->price}}</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
