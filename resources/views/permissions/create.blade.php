@extends('layouts.app')

@section('content')
<h1>Create Permission</h1>
<form action="{{ route('permissions.store') }}" method="POST">
    @csrf
    <label for="name">Name</label>
    <input type="text" name="name" required>
    <button type="submit">Create Permission</button>
</form>
@endsection