@extends('layouts.master')

@section('content')
    <h1>Products - {{ ucfirst(str_replace('-', ' ', $category)) }}</h1>
    <ul>
        @foreach($products as $product)
            <li>{{ $product }}</li>
        @endforeach
    </ul>
@endsection
