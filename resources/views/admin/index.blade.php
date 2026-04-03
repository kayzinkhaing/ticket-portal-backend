@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage User Roles & Permissions</h2>

    <!-- Table to display users and their roles & permissions -->

    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Roles</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    <!-- Display current roles of the user with checkboxes -->
                    @foreach($user->roles as $role)
                    <div>
                        <input type="checkbox" disabled {{ $role->pivot->role_id ? 'checked' : '' }}>
                        <label>{{ $role->name }}</label>
                    </div>
                    @endforeach
                </td>
                <td>

                    <!--   @foreach($user->roles as $role) -->
                    <ul>
                        @foreach ($user->permissions as $permission)
                        <li>{{ $permission->name }}</li>
                        @endforeach
                    </ul>
                    <!--  @endforeach -->
                </td>
                <td>
                    <!-- Check if the user is admin and if so, skip the delete button -->
                    @if(!$user->hasRole('Admin')) <!-- Assuming "admin" role is assigned to the admin user -->
                    <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE') <!-- This makes the form use the DELETE method -->
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <h3>Assign Permissions and Roles</h3>

    <form action="{{ route('admin.assign') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user">Select User</label>
            <select name="user_id" id="user" class="form-control" required>
                <option value="">Select a User</option>
                @foreach($users as $user)
                {{-- Exclude users with the 'admin' role --}}

                <option value="{{ $user->id }}">{{ $user->name }}</option>
                <!-- @if(!$user->roles->contains('name', 'Admin')) -->
                <!-- @endif -->
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="roles">Select Roles</label>
            <select name="roles[]" id="roles" class="form-control" multiple required>
                @foreach($roleDropdown as $id => $role) <!-- Using the key as $id and the value as $role -->
                <option value="{{ $id }}">{{ $role }}</option> <!-- Display the role name -->
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="permissions">Select Permissions</label>
            <select name="permissions[]" id="permissions" class="form-control" multiple required>
                @foreach($permissionDropdown as $id => $permission)
                <option value="{{ $id }}">{{ $permission }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success" style="margin-top:10px">Assign Roles and Permissions</button>
    </form>
</div>

@section('scripts')
<script type="text/javascript">
    // Confirm deletion of user
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from being submitted immediately

            // Show confirmation dialog
            if (confirm("Are you sure you want to delete this user?")) {
                // If confirmed, submit the form
                form.submit();
            } else {
                // If canceled, do nothing
                console.log("User deletion canceled.");
            }
        });
    });
</script>
@endsection

@endsection