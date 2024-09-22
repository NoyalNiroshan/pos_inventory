@extends('layouts.app')

@section('content')
<div class="page-content">

    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
    <h3>Assign Permissions to Role: {{$role->name}}</h3>

    <form action="{{url('roles/'.$role->id.'/give-permissions')}}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">


            @error('permission')
            <span class="class-textdanger">{{ $message }}</span>
                
            @enderror
            <label for="">Permissions</label>
            <div class="row">
                @foreach($permissions as $permission)
                <div class="col-md-3">
                    <label>
                        <input 
                        type="checkbox"
                         name="permission[]" 
                         value="{{ $permission->name }}"
                         @if(in_array($permission->id, $rolePermissions)) checked @endif
                         
                       
                        />
                        {{ $permission->name }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Permissions</button>
    </form>
</div>
@endsection
