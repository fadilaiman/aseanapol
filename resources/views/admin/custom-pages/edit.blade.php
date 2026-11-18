@extends('admin.layout')
@section('title', 'Edit Page')

@section('content')
@include('admin.custom-pages._form', [
    'action' => route('admin.custom-pages.update', $page),
    'method' => 'PUT',
    'submit' => 'Update Page',
])
@endsection
