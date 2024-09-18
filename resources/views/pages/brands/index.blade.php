@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 70px;">
    <!-- Add padding to push content below the header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Brands</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">Add Brand</button>
    </div>

    <!-- Brands Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="custom-table-header">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->description }}</td>
                        <td>
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="Brand Image" width="50" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $brand->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editBrandModal"
                                    onclick="loadBrandData({{ json_encode($brand) }})">Edit</button>
                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Brand Modal -->
    <div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" id="createBrandForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createBrandModalLabel">Add New Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Brand Name -->
                        <div class="mb-3">
                            <label for="create-name" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="create-name" name="name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="create-description" class="form-label">Description</label>
                            <textarea class="form-control" id="create-description" name="description"></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="create-image" class="form-label">Brand Image</label>
                            <input type="file" class="form-control" id="create-image" name="image">
                        </div>

                        <!-- Active Switch -->
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="create-is_active" name="is_active" value="1">
                            <label class="form-check-label" for="create-is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Pass the brand ID to the update route -->
                <form action="" method="POST" enctype="multipart/form-data" id="editBrandForm">
                    @csrf
                    @method('PUT') <!-- Use PUT for update -->

                    <!-- Hidden field for the brand ID -->
                    <input type="hidden" name="id" id="edit-brand-id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Brand Name -->
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description"></textarea>
                        </div>

                        <!-- Display current image if available -->
                        <div class="mb-3" id="edit-image-section">
                            <label for="edit-image" class="form-label">Current Image</label>
                            <div>
                                <img id="edit-current-image" src="" alt="Brand Image" width="100" class="rounded">
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="edit-image" class="form-label">Change Brand Image</label>
                            <input type="file" class="form-control" id="edit-image" name="image">
                        </div>

                        <!-- Active Switch -->
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit-is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit-is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle the form for creating a new brand
    document.getElementById('createBrandForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        const form = this;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            }
        }).then(response => {
            if (response.ok) {
                const createModal = bootstrap.Modal.getInstance(document.getElementById('createBrandModal'));
                createModal.hide();
                window.location.reload(); // Reload page to update the table
            } else {
                alert('Error while saving the brand. Please try again.');
            }
        }).catch(error => console.error('Error:', error));
    });

    // Handle the form for editing an existing brand
    document.getElementById('editBrandForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        const form = this;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            }
        }).then(response => {
            if (response.ok) {
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editBrandModal'));
                editModal.hide();
                window.location.reload(); // Reload page to update the table
            } else {
                alert('Error while updating the brand. Please try again.');
            }
        }).catch(error => console.error('Error:', error));
    });

    // Populate the edit modal with the selected brand's data
    function loadBrandData(brand) {
        // Set the action URL dynamically with the brand ID
        const editForm = document.getElementById('editBrandForm');
        editForm.action = `/brands/${brand.id}`;

        document.getElementById('edit-brand-id').value = brand.id;
        document.getElementById('edit-name').value = brand.name;
        document.getElementById('edit-description').value = brand.description;
        document.getElementById('edit-is_active').checked = brand.is_active ? true : false;

        if (brand.image) {
            document.getElementById('edit-current-image').src = '/storage/' + brand.image;
            document.getElementById('edit-image-section').style.display = 'block';
        } else {
            document.getElementById('edit-image-section').style.display = 'none';
        }
    }
</script>

<style>
    /* Increase font size for table content */
    table {
        font-size: 1rem;
    }

    /* Customize table header background and font */
    .custom-table-header th {
        background-color: #fff;
        color: #000;
        font-weight: bold;
    }

    /* Increase font size for modal content */
    .modal-body, .modal-footer {
        font-size: 1.1rem;
    }
</style>
@endsection
