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
                    <h4>Users
                        <a href="{{ url('users/create') }}" class="btn btn-primary float-end">Add User</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if(!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $rolename)
                                        <span class="badge bg-primary mx-1 p-2 text-uppercase fw-bold">
                                            {{$rolename}}
                                        </span>
                                        
                                            
                                        @endforeach
                                        @endif
                                          
                                    </td>
                                
                          <td>

                                        <a href="{{ url('users/'.$user->id.'/edit') }}" class="btn btn-success btn-sm">Edit</a>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ url('users/'.$user->id) }}" method="POST" style="display:inline-block;">
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
