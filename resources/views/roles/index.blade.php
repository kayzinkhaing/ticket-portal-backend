@extends('layouts.app')

@section('content')
<h1>Roles</h1>
<a href="{{ route('roles.create') }}">Create New Role</a>
<table>
    <thead>
        <tr>
            <th>Role Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $data)
        <tr>
            <td> {!! $data->name !!}</td>
            <td>
                <a href="{{ route('roles.edit', $data->id) }}">Edit</a>
                <form action="{{ route('roles.destroy', $data->id) }}" method="POST" style="display:inline;">
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