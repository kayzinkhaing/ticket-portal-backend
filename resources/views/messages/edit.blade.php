<!-- resources/views/messages/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Message</h2>

    <form action="{{ route('messages.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data->name) }}" required>
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <input type="text" name="message" id="message" class="form-control" value="{{ old('message', $data->message) }}" required>
            @error('message')
            <div class="text-danger">{{ $data->message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning">Update Message</button>

    </form>
</div>
@endsection