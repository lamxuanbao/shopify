@extends('layout')
@section('content')
@auth
@include('partial/_product_list')
@include('partial/_social')
@else
@include('partial/_login')
@endauth
@endsection
