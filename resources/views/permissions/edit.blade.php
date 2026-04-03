@extends('layouts.app')

@section('content')
<h1>Edit Permission</h1>
<form action="{{ route('permissions.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Name</label>
    <input type="text" name="name" value="{{ old('name', $data->name) }}" required>
    <button type="submit">Update Permission</button>
</form>
@endsectionasd