@extends('layout')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form id="form_message" method="post" action="{{route('postMessage')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <h5 class="card-title">Set message to product</h5>
                        <p class="card-text">{{$product->title}}</p>
                        <p class="card-text">
                            <textarea class="form-control" required name="message"></textarea>
                        </p>
                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <table id="example" class="display" style="width:100%">
                <thead>
                <tr>
                    <td>Message</td>
                </tr>
                </thead>
                <tbody>
                @foreach($product->message as $message)
                    <tr>
                        <td>{{$message->message}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endpush
@push('scripts')
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable({
                "searching": false,
                "ordering": false,
                "info": false,
                "lengthChange": false,
                "drawCallback": function (settings) {
                    $("#example thead").remove();
                }
            });
        });
    </script>
@endpush
