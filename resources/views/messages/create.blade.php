<!-- resources/views/messages/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Message</h2>

    <form action="{{ route('messages.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Key</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="message">Value</label>
            <input type="text" name="message" id="value" class="form-control" value="{{ old('message') }}" required>
            @error('message')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Create Message</button>
    </form>
</div>
@endsection