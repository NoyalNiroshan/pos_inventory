@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4> Create Permissions
                        <a href="{{ url('permissions/create') }}" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Content goes here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
