@extends('admin.layout')
@section('title', 'Add Page')

@section('content')
@include('admin.custom-pages._form', [
    'action' => route('admin.custom-pages.store'),
    'method' => 'POST',
    'submit' => 'Save Page',
])
@endsection
