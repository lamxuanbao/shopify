<div class="row justify-content-md-center">
    <div class="col-md-auto">
        <div class="card" style="width: 30rem;">
            <div class="card-body">
                <h5 class="card-title text-center">
                    WELLCOME
                </h5>
                <p class="card-text text-center">
                    Please enter your Shopify URL
                </p>
                <form id="form_install" action="{{ route('install') }}" method="post">
                    {{ csrf_field() }}
                    <p class="card-text">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Store Name"
                               name="domain" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">.myshopify.com</span>
                        </div>
                    </div>
                    </p>
                    <p class="card-text">
                        <button type="submit" class="btn btn-primary form-control">
                            <span class="spinner-border spinner-border-sm d-none"></span>
                            Login
                        </button>
                    </p>
                </form>
                <p class="card-text">
                    Don't have a Shopify store yet?
                    <a href="#">
                        Create Store
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
