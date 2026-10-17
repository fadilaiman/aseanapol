@extends('admin.layout')
@section('title', 'Add Event')

@section('content')
@include('admin.events._form', [
    'action' => route('admin.events.store'),
    'method' => 'POST',
    'submit' => 'Save Event',
])
@endsection
