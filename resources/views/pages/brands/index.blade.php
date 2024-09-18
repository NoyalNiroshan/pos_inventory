@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding-top: 70px;"> <!-- Add padding to push content below the header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Brands</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal">Add Brand</button>
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
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#brandModal"
                                        onclick="editBrand({{ $brand }})">Edit</button>
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

        <!-- Brand Modal (Create/Edit) -->
        <div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="brandModalLabel">Add/Edit Brand</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="brand-id">

                            <!-- Brand Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Brand Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>

                            <!-- Display current image if available -->
                            <div class="mb-3" id="image-display-section" style="display:none;">
                                <label for="stored-image" class="form-label">Current Image</label>
                                <div>
                                    <img id="stored-image" src="" alt="Brand Image" width="100" class="rounded">
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Brand Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <!-- Active Switch -->
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
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
    </div>

    <script>
        function editBrand(brand) {
            document.getElementById('brand-id').value = brand.id;
            document.getElementById('name').value = brand.name;
            document.getElementById('description').value = brand.description;

            // Set the is_active switch state
            document.getElementById('is_active').checked = brand.is_active ? true : false;

            // Show the current image if available
            if (brand.image) {
                document.getElementById('stored-image').src = '/storage/' + brand.image;
                document.getElementById('image-display-section').style.display = 'block';
            } else {
                document.getElementById('image-display-section').style.display = 'none';
            }

            // Open the modal
            const brandModal = new bootstrap.Modal(document.getElementById('brandModal'));
            brandModal.show();
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
