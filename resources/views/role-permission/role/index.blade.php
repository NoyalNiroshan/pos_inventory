@extends('layouts.app')

@section('content')
<div class="page-content">

    @include('role-permission.nav-links')
    <div class="row">
        <div class="col-md-12">

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h4>Roles
                        <a href="{{ url('roles/create') }}" class="btn btn-primary float-end">Add Role</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <a href="{{ route('roles.addPermissions', $role->id) }}" class="btn btn-success btn-sm">Add or Edit Role Permissions</a>

                                        <a href="{{ url('roles/'.$role->id.'/edit') }}" class="btn btn-success btn-sm">Edit</a>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ url('roles/'.$role->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this permission?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No roles found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
