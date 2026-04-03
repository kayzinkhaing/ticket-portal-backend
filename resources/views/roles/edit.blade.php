@extends('layouts.app') <!-- This extends your main layout -->

@section('content')
<div class="container">
    <h2>Edit Role</h2>

    <!-- Display validation errors -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form to update an existing role -->
    <form action="{{ route('roles.update', $data->id) }}" method="POST">
        @csrf <!-- CSRF token for protection -->
        @method('PUT') <!-- Indicating the form is for updating -->

        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter role name" value="{{ old('name', $data->name) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection