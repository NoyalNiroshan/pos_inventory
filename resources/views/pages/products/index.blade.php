@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Products</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">Add Product</button>
    </div>

    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            @if (session('success'))
                <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('warning'))
                <div id="warningToast" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('warning') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="custom-table-header bg-primary text-white">
                <tr>
                    <th>ID</th>
                    <th>Brand</th>

                    <th>Image</th>
                    <th>Name</th>
                    <th>Stock Type</th>
                    <th>Serial Number</th>
                    <th>Active</th>
                    <th>In Stock</th>
                    <th>On Sale</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->brand->name }}</td>
                        
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="50" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ ucfirst($product->stock_type) }}</td>
                        <td>{{ $product->serial_number }}</td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->in_stock ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->in_stock ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->on_sale ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->on_sale ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="loadProductData({{ json_encode($product) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewProductModal" onclick="viewProductData({{ json_encode($product) }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
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

    <!-- Create Product Modal -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select border border-dark" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subcategory_id" class="form-label">Subcategory</label>
                                    <select class="form-select border border-dark" name="subcategory_id" required>
                                        <option value="">Select Subcategory</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control border border-dark" id="name" name="name" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock_type" class="form-label">Stock Type</label>
                                    <select class="form-select border border-dark" name="stock_type" id="stock_type" required>
                                        <option value="">Select Stock Type</option>
                                        <!-- Add new types here -->
                                        @foreach(['liquid', 'solid', 'dress', 'powder', 'gas', 'electronics', 'medicine', 'furniture', 'cosmetics', 'food', 'beverage'] as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="serial_number" class="form-label">Serial Number (Optional)</label>
                                    <input type="text" class="form-control border border-dark" id="serial_number" name="serial_number">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control border border-dark" id="image" name="image">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>

                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" value="1" checked>
                                    <label class="form-check-label" for="in_stock">In Stock</label>
                                </div>

                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="on_sale" name="on_sale" value="1">
                                    <label class="form-check-label" for="on_sale">On Sale</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control border border-dark" id="description" name="description"></textarea>
                            </div>
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

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" id="editProductForm" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-product-id">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="edit-brand_id" class="form-label">Brand</label>
                                    <select class="form-select border border-dark" id="edit-brand_id" name="brand_id" required>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="edit-subcategory_id" class="form-label">Subcategory</label>
                                    <select class="form-select border border-dark" id="edit-subcategory_id" name="subcategory_id" required>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="edit-name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control border border-dark" id="edit-name" name="name" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="edit-stock_type" class="form-label">Stock Type</label>
                                    <select class="form-select border border-dark" id="edit-stock_type" name="stock_type" required>
                                        @foreach(['liquid', 'solid', 'dress', 'powder', 'gas', 'electronics', 'medicine', 'furniture', 'cosmetics', 'food', 'beverage'] as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6 mb-3">
                                    <label for="edit-serial_number" class="form-label">Serial Number (Optional)</label>
                                    <input type="text" class="form-control border border-dark" id="edit-serial_number" name="serial_number">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="edit-image" class="form-label">Change Product Image</label>
                                    <input type="file" class="form-control border border-dark" id="edit-image" name="image">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit-is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit-is_active">Active</label>
                                </div>

                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit-in_stock" name="in_stock" value="1">
                                    <label class="form-check-label" for="edit-in_stock">In Stock</label>
                                </div>

                                <div class="col-md-4 mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit-on_sale" name="on_sale" value="1">
                                    <label class="form-check-label" for="edit-on_sale">On Sale</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit-description" class="form-label">Description</label>
                                <textarea class="form-control border border-dark" id="edit-description" name="description"></textarea>
                            </div>

                            <div class="mb-3" id="edit-image-section">
                                <label for="edit-image" class="form-label">Current Image</label>
                                <div>
                                    <img id="edit-current-image" src="" alt="Product Image" width="100" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewProductModalLabel">View Product Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="view-brand" class="form-label">Brand</label>
                            <p id="view-brand"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="view-subcategory" class="form-label">Subcategory</label>
                            <p id="view-subcategory"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="view-name" class="form-label">Product Name</label>
                            <p id="view-name"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="view-stock_type" class="form-label">Stock Type</label>
                            <p id="view-stock_type"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="view-serial_number" class="form-label">Serial Number</label>
                            <p id="view-serial_number"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="view-image" class="form-label">Product Image</label>
                            <img id="view-image" src="" alt="Product Image" width="100" class="rounded">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="view-description" class="form-label">Description</label>
                        <p id="view-description"></p>
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
        if (document.getElementById('errorToast')) {
            let errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
            errorToast.show();
        }
    };

    // Populate the view modal with the selected product's data
    function viewProductData(product) {
        document.getElementById('view-brand').textContent = product.brand.name;
        document.getElementById('view-subcategory').textContent = product.subcategory.name;
        document.getElementById('view-name').textContent = product.name;
        document.getElementById('view-stock_type').textContent = product.stock_type;
        document.getElementById('view-serial_number').textContent = product.serial_number;
        document.getElementById('view-description').textContent = product.description;
        if (product.image) {
            document.getElementById('view-image').src = '/storage/' + product.image;
        } else {
            document.getElementById('view-image').src = '';
        }
    }

    // Populate the edit modal with the selected product's data
    function loadProductData(product) {
        const editForm = document.getElementById('editProductForm');
        editForm.action = `/products/${product.id}`;
        document.getElementById('edit-product-id').value = product.id;
        document.getElementById('edit-name').value = product.name;
        document.getElementById('edit-stock_type').value = product.stock_type;
        document.getElementById('edit-serial_number').value = product.serial_number;
        document.getElementById('edit-description').value = product.description;
        document.getElementById('edit-is_active').checked = product.is_active;
        document.getElementById('edit-in_stock').checked = product.in_stock;
        document.getElementById('edit-on_sale').checked = product.on_sale;

        document.getElementById('edit-brand_id').value = product.brand_id;
        document.getElementById('edit-subcategory_id').value = product.subcategory_id;

        if (product.image) {
            document.getElementById('edit-current-image').src = '/storage/' + product.image;
            document.getElementById('edit-image-section').style.display = 'block';
        } else {
            document.getElementById('edit-image-section').style.display = 'none';
        }
    }
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }

    .modal-body {
        font-size: 1.1rem;
    }

    .btn-sm {
        margin-right: 5px;
    }

    /* Toast styling */
    .toast-container .toast {
        margin-bottom: 1rem;
    }
</style>
@endsection
