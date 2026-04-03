@extends('layouts.app')

@section('content')
<h1>Create Role</h1>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <label for="name">Role Name</label>
    <input type="text" name="name" id="name" required>
    <button type="submit">Create</button>
</form>
@endsection