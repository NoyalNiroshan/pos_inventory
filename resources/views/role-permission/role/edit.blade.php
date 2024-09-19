@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h4>Edit Role
                        <a href="{{ url('roles') }}" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('roles/'.$role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
