<!-- resources/views/messages/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Messages</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('messages.create') }}" class="btn btn-primary mb-3">Create New Message</a>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $message)
            <tr>
                <td>{{ $message->id }}</td>
                <td>{{ $message->name }}</td>
                <td>{{ $message->message }}</td>
                <td>
                    <a href="{{ route('messages.edit', [$message->id, 'key' => $message->name]) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('messages.destroy', [$message->id, 'key' => $message->name]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection