@extends('layouts.app')

@section('content')
<div class="form-control">

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
</div>
<h1>Permissions</h1>
<a href="{{ route('permissions.create') }}">Create New Permission</a>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permissions as $data)
        <tr>
            <td>{{ $data->name }}</td>
            <td>
                <a href="{{ route('permissions.edit', $data->id) }}">Edit</a>
                <form action="{{ route('permissions.destroy', $data->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection