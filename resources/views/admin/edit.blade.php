{{-- resources/views/admin/edit_user_permissions.blade.php --}}

@extends('layouts.admin') {{-- Assuming you have a layout for the admin panel --}}

@section('content')
<div class="container">
    <h2>Edit Permissions for User: {{ $user->name }}</h2>

    <form action="{{ route('admin.update_user_permissions', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h4>Permissions</h4>
            </div>
            <div class="card-body">
                {{-- Loop through all possible permissions --}}
                <div class="form-group">
                    <label for="permissions">Select Permissions</label>
                    <div class="checkbox-group">
                        @foreach($permissions as $permission)
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                class="form-check-input"
                                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $permission->name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Permissions</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </form>
</div>
@endsection