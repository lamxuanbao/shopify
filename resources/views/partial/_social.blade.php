<div class="row justify-content-md-center">
    <div class="col-md-12">
        <a href="{{route('social',['provider'=>'facebook'])}}">Facebook</a>
    </div>
    @foreach($facebookPages as $page)
        <div class="col-md-4">
            <a href="{{route('facebookPage', ['pageId' => $page->id])}}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{$page->name}}</h5>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
