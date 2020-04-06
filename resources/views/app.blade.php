@extends('layout')
@section('content')
@auth
@include('partial/_product_list')
@else
@include('partial/_login')
@endauth
@endsection
