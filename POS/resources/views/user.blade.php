@extends('layouts.master')

@section('content')
    <h1>User Profile</h1>
    <p>ID: {{ $id }}</p>
    <p>Name: {{ $name }}</p>
@endsection