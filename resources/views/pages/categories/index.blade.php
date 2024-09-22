@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Categories</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Add Category</button>
    </div>

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

    <!-- Categories Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-center align-middle">
            <thead class="custom-table-header bg-primary text-white">
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
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" width="50" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCategoryModal" onclick="viewCategoryData({{ json_encode($category) }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal" onclick="loadCategoryData({{ json_encode($category) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="createCategoryForm" novalidate>
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create-name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border border-dark" id="create-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="create-description" class="form-label">Description</label>
                            <textarea class="form-control border border-dark" id="create-description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="create-image" class="form-label">Category Image</label>
                            <input type="file" class="form-control border border-dark" id="create-image" name="image">
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
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

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" id="editCategoryForm" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-category-id">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border border-dark" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control border border-dark" id="edit-description" name="description"></textarea>
                        </div>
                        <div class="mb-3" id="edit-image-section">
                            <label for="edit-image" class="form-label">Current Image</label>
                            <div>
                                <img id="edit-current-image" src="" alt="Category Image" width="100" class="rounded">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-image" class="form-label">Change Category Image</label>
                            <input type="file" class="form-control border border-dark" id="edit-image" name="image">
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="edit-is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit-is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Category Modal -->
    <div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-labelledby="viewCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewCategoryModalLabel">View Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>ID:</strong>
                        <p id="view-category-id"></p>
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
                            <img id="view-image" src="" alt="Category Image" width="100" class="rounded">
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
        const toasts = ['successToast', 'warningToast', 'dangerToast'];
        toasts.forEach(id => {
            const toastElement = document.getElementById(id);
            if (toastElement) {
                let toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        });
    };

    // Load data into edit modal
    function loadCategoryData(category) {
        const editForm = document.getElementById('editCategoryForm');
        editForm.action = `/categories/${category.id}`;
        document.getElementById('edit-category-id').value = category.id;
        document.getElementById('edit-name').value = category.name;
        document.getElementById('edit-description').value = category.description;
        document.getElementById('edit-is_active').checked = category.is_active;

        if (category.image) {
            document.getElementById('edit-current-image').src = '/storage/' + category.image;
            document.getElementById('edit-image-section').style.display = 'block';
        } else {
            document.getElementById('edit-image-section').style.display = 'none';
        }
    }

    // Load data into view modal
    function viewCategoryData(category) {
        document.getElementById('view-category-id').innerText = category.id;
        document.getElementById('view-name').innerText = category.name;
        document.getElementById('view-description').innerText = category.description;
        document.getElementById('view-is_active').innerText = category.is_active ? 'Yes' : 'No';

        if (category.image) {
            document.getElementById('view-image').src = '/storage/' + category.image;
            document.getElementById('view-image').style.display = 'block';
        } else {
            document.getElementById('view-image').style.display = 'none';
        }
    }
</script>

<style>
    /* Table Styling */
    .table {
        font-size: 1rem;
    }

    .custom-table-header th {
        font-weight: bold;
    }

    /* Modal Styling */
    .modal-body, .modal-footer {
        font-size: 1.1rem;
        background-color: #f7f7f7;
    }

    /* Toaster Styling */
    .toast .toast-body {
        font-size: 1rem;
    }

    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }

    .btn-sm {
        margin-right: 5px;
    }
</style>
@endsection
