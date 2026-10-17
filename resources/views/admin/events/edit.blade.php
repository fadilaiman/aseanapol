@extends('admin.layout')
@section('title', 'Edit Event')

@section('content')
@include('admin.events._form', [
    'action' => route('admin.events.update', $event),
    'method' => 'PUT',
    'submit' => 'Update Event',
])
@endsection
