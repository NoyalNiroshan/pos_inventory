@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <!-- Success Toast -->
            @if (session('success'))
                <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Warning Toast -->
            @if (session('warning'))
                <div id="warningToast" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('warning') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Danger Toast -->
            @if (session('error'))
                <div id="dangerToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Subcategories</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubcategoryModal">Add Subcategory</button>
    </div>

    <!-- Subcategories Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="custom-table-header">
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $subcategory)
                    <tr>
                        <td>{{ $subcategory->id }}</td>
                        <td>{{ $subcategory->category->name }}</td>
                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->description }}</td>
                        <td>
                            @if($subcategory->image)
                                <img src="{{ asset('storage/' . $subcategory->image) }}" alt="Subcategory Image" width="50" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <input type="checkbox" class="form-check-input" id="edit-toggle-{{ $subcategory->id }}" {{ $subcategory->is_active ? 'checked' : '' }} disabled>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewSubcategoryModal" onclick="viewSubcategoryData({{ json_encode($subcategory) }})">View</button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal" onclick="loadSubcategoryData({{ json_encode($subcategory) }})">Edit</button>
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline-block;">
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

    <!-- Create Subcategory Modal -->
    <div class="modal fade" id="createSubcategoryModal" tabindex="-1" aria-labelledby="createSubcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('subcategories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSubcategoryModalLabel">Add New Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Subcategory Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
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

    <!-- Edit Subcategory Modal -->
    <div class="modal fade" id="editSubcategoryModal" tabindex="-1" aria-labelledby="editSubcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" id="editSubcategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-subcategory-id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubcategoryModalLabel">Edit Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-category_id" class="form-label">Category</label>
                            <select class="form-select" id="edit-category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description"></textarea>
                        </div>

                        <div class="mb-3" id="edit-image-section">
                            <label for="edit-image" class="form-label">Current Image</label>
                            <div>
                                <img id="edit-current-image" src="" alt="Subcategory Image" width="100" class="rounded">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-image" class="form-label">Change Subcategory Image</label>
                            <input type="file" class="form-control" id="edit-image" name="image">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
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

    <!-- View Subcategory Modal -->
    <div class="modal fade" id="viewSubcategoryModal" tabindex="-1" aria-labelledby="viewSubcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSubcategoryModalLabel">View Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>ID:</strong>
                        <p id="view-subcategory-id"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Category:</strong>
                        <p id="view-category-name"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p id="view-name"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p id="view-description"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Active:</strong>
                        <p id="view-is_active"></p>
                    </div>
                    <div class="mb-3" id="view-image-section">
                        <strong>Image:</strong>
                        <div>
                            <img id="view-image" src="" alt="Subcategory Image" width="100" class="rounded">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toast initialization on page load
    window.onload = function() {
        if (document.getElementById('successToast')) {
            let successToast = new bootstrap.Toast(document.getElementById('successToast'));
            successToast.show();
        }
        if (document.getElementById('warningToast')) {
            let warningToast = new bootstrap.Toast(document.getElementById('warningToast'));
            warningToast.show();
        }
        if (document.getElementById('dangerToast')) {
            let dangerToast = new bootstrap.Toast(document.getElementById('dangerToast'));
            dangerToast.show();
        }
    };

    // Load data into edit modal
    function loadSubcategoryData(subcategory) {
        const editForm = document.getElementById('editSubcategoryForm');
        editForm.action = `/subcategories/${subcategory.id}`;
        document.getElementById('edit-subcategory-id').value = subcategory.id;
        document.getElementById('edit-name').value = subcategory.name;
        document.getElementById('edit-description').value = subcategory.description;
        document.getElementById('edit-is_active').checked = subcategory.is_active;

        document.getElementById('edit-category_id').value = subcategory.category_id;

        if (subcategory.image) {
            document.getElementById('edit-current-image').src = '/storage/' + subcategory.image;
            document.getElementById('edit-image-section').style.display = 'block';
        } else {
            document.getElementById('edit-image-section').style.display = 'none';
        }
    }

    // Load data into view modal
    function viewSubcategoryData(subcategory) {
        document.getElementById('view-subcategory-id').innerText = subcategory.id;
        document.getElementById('view-category-name').innerText = subcategory.category.name;
        document.getElementById('view-name').innerText = subcategory.name;
        document.getElementById('view-description').innerText = subcategory.description;
        document.getElementById('view-is_active').innerText = subcategory.is_active ? 'Yes' : 'No';

        if (subcategory.image) {
            document.getElementById('view-image').src = '/storage/' + subcategory.image;
            document.getElementById('view-image').style.display = 'block';
        } else {
            document.getElementById('view-image').style.display = 'none';
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
        font-weight: bold;
    }

    /* Increase font size for modal content */
    .modal-body, .modal-footer {
        font-size: 1.1rem;
    }

    /* Toaster Styling */
    .toast .toast-body {
        font-size: 1rem;
    }
</style>
@endsection
