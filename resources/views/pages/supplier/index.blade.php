@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-top: 90px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Suppliers</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">Add Supplier</button>
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

    <!-- Suppliers Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="custom-table-header bg-primary text-white">
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->id }}</td>
                        <td>{{ $supplier->company_name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="bi bi-star{{ $supplier->rating >= $i ? '-fill' : '' }}"></span>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewSupplierModal" onclick="viewSupplierData({{ json_encode($supplier) }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal" onclick="loadSupplierData({{ json_encode($supplier) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
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


  <!-- Add New Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSupplierModalLabel">Add New Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-4">
                            <!-- Company Information -->
                            <div class="col-12">
                                <h6 class="mb-3">Company Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control border border-dark" id="company_name" name="company_name" required>
                                            <div class="invalid-feedback">
                                                Please enter the company name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registerNum" class="form-label">Registration Number</label>
                                            <input type="text" class="form-control border border-dark" id="registerNum" name="registerNum">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control border border-dark" id="email" name="email" required>
                                            <div class="invalid-feedback">
                                                Please enter a valid email address.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone_numbers" class="form-label">Phone Numbers <small class="text-muted">(comma separated)</small></label>
                                            <input type="text" class="form-control border border-dark" id="phone_numbers" name="phone_numbers[]" placeholder="e.g., 1234567890,0987654321">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control border border-dark" id="address" name="address" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier Details -->
                            <div class="col-12">
                                <h6 class="mb-3">Supplier Details</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="supplier_type" class="form-label">Supplier Type <span class="text-danger">*</span></label>
                                            <select class="form-select border border-dark" id="supplier_type" name="supplier_type" required>
                                                <option value="">Select Type</option>
                                                <option value="local">Local</option>
                                                <option value="international">International</option>
                                                <option value="manufacturer">Manufacturer</option>
                                                <option value="distributor">Distributor</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a supplier type.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="delivery_methods" class="form-label">Delivery Methods</label>
                                            <select class="form-select border border-dark" id="delivery_methods" name="delivery_methods">
                                                <option value="">Select Method</option>
                                                <option value="courier">Courier</option>
                                                <option value="own_vehicle">Own Vehicle</option>
                                                <option value="drop_shipping">Drop Shipping</option>
                                                <option value="pickup">Pickup</option>
                                                <option value="postal_service">Postal Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Rating <small class="text-muted">(0-5)</small></label>
                                            <input type="number" class="form-control border border-dark" id="rating" name="rating" min="0" max="5" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tax_identification_number" class="form-label">Tax Identification Number</label>
                                            <input type="text" class="form-control border border-dark" id="tax_identification_number" name="tax_identification_number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount Information -->
                            <div class="col-12">
                                <h6 class="mb-3">Discount Information</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="has_discount" value="0">
                                            <input class="form-check-input border border-dark" type="checkbox" id="has_discount" name="has_discount" value="1" onchange="toggleDiscountFields(this)">
                                            <label class="form-check-label" for="has_discount">Has Discount</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3" id="discountTypeField" style="display:none;">
                                            <label for="discount_type" class="form-label">Discount Type</label>
                                            <select class="form-select border border-dark" id="discount_type" name="discount_type">
                                                <option value="">Select Type</option>
                                                <option value="percentage">Percentage</option>
                                                <option value="amount">Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3" id="discountValueField" style="display:none;">
                                            <label for="discount_value" class="form-label">Discount Value</label>
                                            <input type="number" class="form-control border border-dark" id="discount_value" name="discount_value" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banking Details -->
                            <div class="col-12">
                                <h6 class="mb-3">Banking Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="account_name" class="form-label">Account Name</label>
                                            <input type="text" class="form-control border border-dark" id="account_name" name="account_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="account_number" class="form-label">Account Number</label>
                                            <input type="text" class="form-control border border-dark" id="account_number" name="account_number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bank_name" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control border border-dark" id="bank_name" name="bank_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ifsc_code" class="form-label">IFSC Code</label>
                                            <input type="text" class="form-control border border-dark" id="ifsc_code" name="ifsc_code">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- JavaScript to Handle Discount Fields Toggle and Form Validation -->
<script>
    function toggleDiscountFields(checkbox) {
        const discountTypeField = document.getElementById('discountTypeField');
        const discountValueField = document.getElementById('discountValueField');
        if (checkbox.checked) {
            discountTypeField.style.display = 'block';
            discountValueField.style.display = 'block';
        } else {
            discountTypeField.style.display = 'none';
            discountValueField.style.display = 'none';
        }
    }

    // Bootstrap 5 Form Validation
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('form')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>


    <!-- Edit Supplier Modal -->
   <!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data" id="editSupplierForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-supplier-id">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-4">
                            <!-- Company Information -->
                            <div class="col-12">
                                <h6 class="mb-3">Company Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control border border-dark" id="edit-company_name" name="company_name" required>
                                            <div class="invalid-feedback">
                                                Please enter the company name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-registerNum" class="form-label">Registration Number</label>
                                            <input type="text" class="form-control border border-dark" id="edit-registerNum" name="registerNum">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control border border-dark" id="edit-email" name="email" required>
                                            <div class="invalid-feedback">
                                                Please enter a valid email address.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-phone_numbers" class="form-label">Phone Numbers <small class="text-muted">(comma separated)</small></label>
                                            <input type="text" class="form-control border border-dark" id="edit-phone_numbers" name="phone_numbers[]" placeholder="e.g., 1234567890,0987654321">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="edit-address" class="form-label">Address</label>
                                            <textarea class="form-control border border-dark" id="edit-address" name="address" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier Details -->
                            <div class="col-12">
                                <h6 class="mb-3">Supplier Details</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="edit-supplier_type" class="form-label">Supplier Type <span class="text-danger">*</span></label>
                                            <select class="form-select border border-dark" id="edit-supplier_type" name="supplier_type" required>
                                                <option value="">Select Type</option>
                                                <option value="local">Local</option>
                                                <option value="international">International</option>
                                                <option value="manufacturer">Manufacturer</option>
                                                <option value="distributor">Distributor</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a supplier type.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="edit-delivery_methods" class="form-label">Delivery Methods</label>
                                            <select class="form-select border border-dark" id="edit-delivery_methods" name="delivery_methods">
                                                <option value="">Select Method</option>
                                                <option value="courier">Courier</option>
                                                <option value="own_vehicle">Own Vehicle</option>
                                                <option value="drop_shipping">Drop Shipping</option>
                                                <option value="pickup">Pickup</option>
                                                <option value="postal_service">Postal Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="edit-rating" class="form-label">Rating <small class="text-muted">(0-5)</small></label>
                                            <input type="number" class="form-control border border-dark" id="edit-rating" name="rating" min="0" max="5" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-tax_identification_number" class="form-label">Tax Identification Number</label>
                                            <input type="text" class="form-control border border-dark" id="edit-tax_identification_number" name="tax_identification_number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount Information -->
                            <div class="col-12">
                                <h6 class="mb-3">Discount Information</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="has_discount" value="0">
                                            <input class="form-check-input border border-dark" type="checkbox" id="edit-has_discount" name="has_discount" value="1" onchange="toggleEditDiscountFields(this)">
                                            <label class="form-check-label" for="edit-has_discount">Has Discount</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3" id="edit-discountTypeField" style="display:none;">
                                            <label for="edit-discount_type" class="form-label">Discount Type</label>
                                            <select class="form-select border border-dark" id="edit-discount_type" name="discount_type">
                                                <option value="">Select Type</option>
                                                <option value="percentage">Percentage</option>
                                                <option value="amount">Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3" id="edit-discountValueField" style="display:none;">
                                            <label for="edit-discount_value" class="form-label">Discount Value</label>
                                            <input type="number" class="form-control border border-dark" id="edit-discount_value" name="discount_value" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banking Details -->
                            <div class="col-12">
                                <h6 class="mb-3">Banking Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-account_name" class="form-label">Account Name</label>
                                            <input type="text" class="form-control border border-dark" id="edit-account_name" name="account_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-account_number" class="form-label">Account Number</label>
                                            <input type="text" class="form-control border border-dark" id="edit-account_number" name="account_number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-bank_name" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control border border-dark" id="edit-bank_name" name="bank_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-ifsc_code" class="form-label">IFSC Code</label>
                                            <input type="text" class="form-control border border-dark" id="edit-ifsc_code" name="ifsc_code">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewSupplierModalLabel"><i class="bi bi-eye-fill"></i> View Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Company Information Section -->
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-building"></i> Company Information</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>ID:</strong>
                            <p id="view-supplier-id" class="mb-0">#12345</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Company Name:</strong>
                            <p id="view-company-name" class="mb-0">ABC Supplies Ltd.</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Registration Number:</strong>
                            <p id="view-registerNum" class="mb-0">REG-67890</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong>
                            <p id="view-email" class="mb-0">contact@abcsupplies.com</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone Numbers:</strong>
                            <p id="view-phone_numbers" class="mb-0">+1-234-567-8901, +1-098-765-4321</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Address:</strong>
                            <p id="view-address" class="mb-0">1234 Elm Street, Springfield, IL, USA</p>
                        </div>
                    </div>
                </div>

                <!-- Supplier Details Section -->
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-person-lines-fill"></i> Supplier Details</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Supplier Type:</strong>
                            <p id="view-supplier_type" class="mb-0">Local</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Delivery Methods:</strong>
                            <p id="view-delivery_methods" class="mb-0">Courier, Pickup</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Rating:</strong>
                            <p id="view-rating" class="mb-0">4.5</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Tax Identification Number:</strong>
                            <p id="view-tax_identification_number" class="mb-0">TIN-11223344</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Has Discount:</strong>
                            <p id="view-has_discount" class="mb-0">Yes</p>
                        </div>
                        <div class="col-md-6 mb-3" id="view-discountTypeField" style="display:none;">
                            <strong>Discount Type:</strong>
                            <p id="view-discount_type" class="mb-0">Percentage</p>
                        </div>
                        <div class="col-md-6 mb-3" id="view-discountValueField" style="display:none;">
                            <strong>Discount Value:</strong>
                            <p id="view-discount_value" class="mb-0">10%</p>
                        </div>
                    </div>
                </div>

                <!-- Banking Details Section -->
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-credit-card"></i> Banking Details</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Account Name:</strong>
                            <p id="view-account_name" class="mb-0">ABC Supplies Ltd.</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Account Number:</strong>
                            <p id="view-account_number" class="mb-0">1234567890</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Bank Name:</strong>
                            <p id="view-bank_name" class="mb-0">First National Bank</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>IFSC Code:</strong>
                            <p id="view-ifsc_code" class="mb-0">FNBI0001234</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Optional: Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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

    // Toggle discount fields for create modal
    function toggleDiscountFields(checkbox) {
        const discountTypeField = document.getElementById('discountTypeField');
        const discountValueField = document.getElementById('discountValueField');
        if (checkbox.checked) {
            discountTypeField.style.display = 'block';
            discountValueField.style.display = 'block';
        } else {
            discountTypeField.style.display = 'none';
            discountValueField.style.display = 'none';
        }
    }

    // Toggle discount fields for edit modal
    function toggleEditDiscountFields(checkbox) {
        const discountTypeField = document.getElementById('edit-discountTypeField');
        const discountValueField = document.getElementById('edit-discountValueField');
        if (checkbox.checked) {
            discountTypeField.style.display = 'block';
            discountValueField.style.display = 'block';
        } else {
            discountTypeField.style.display = 'none';
            discountValueField.style.display = 'none';
        }
    }

    // Load data into edit modal
    function loadSupplierData(supplier) {
        const editForm = document.getElementById('editSupplierForm');
        editForm.action = `/suppliers/${supplier.id}`;
        document.getElementById('edit-supplier-id').value = supplier.id;
        document.getElementById('edit-company_name').value = supplier.company_name;
        document.getElementById('edit-registerNum').value = supplier.registerNum;
        document.getElementById('edit-email').value = supplier.email;
        document.getElementById('edit-address').value = supplier.address;
        document.getElementById('edit-phone_numbers').value = supplier.phone_numbers.join(', '); // Assuming phone_numbers is an array
        document.getElementById('edit-has_discount').checked = supplier.has_discount;

        // Toggle fields based on has_discount
        toggleEditDiscountFields(document.getElementById('edit-has_discount'));
        document.getElementById('edit-discount_type').value = supplier.discount_type;
        document.getElementById('edit-discount_value').value = supplier.discount_value;
        document.getElementById('edit-rating').value = supplier.rating;
        document.getElementById('edit-tax_identification_number').value = supplier.tax_identification_number;
        document.getElementById('edit-supplier_type').value = supplier.supplier_type;
        document.getElementById('edit-delivery_methods').value = supplier.delivery_methods;
        document.getElementById('edit-account_name').value = supplier.account_name;
        document.getElementById('edit-account_number').value = supplier.account_number;
        document.getElementById('edit-bank_name').value = supplier.bank_name;
        document.getElementById('edit-ifsc_code').value = supplier.ifsc_code;
    }

    // Load data into view modal
    function viewSupplierData(supplier) {
        document.getElementById('view-supplier-id').innerText = supplier.id;
        document.getElementById('view-company-name').innerText = supplier.company_name;
        document.getElementById('view-email').innerText = supplier.email;
        document.getElementById('view-address').innerText = supplier.address;
        document.getElementById('view-phone_numbers').innerText = supplier.phone_numbers;
        document.getElementById('view-has_discount').innerText = supplier.has_discount? 'Yes' : 'No';
        document.getElementById('view-discountTypeField').style.display = supplier.has_discount? 'block' : 'none';
        document.getElementById('view-discount_type').innerText = supplier.discount_type;
        document.getElementById('view-rating').innerText = supplier.rating
        document.getElementById('view-tax_identification_number').innerText = supplier.tax_identification_number;
        document.getElementById('view-supplier_type').innerText = supplier.supplier_type;
        document.getElementById('view-delivery_methods').innerText = supplier.delivery_methods;
        document.getElementById('view-account_name').innerText = supplier.account_name;
        document.getElementById('view-account_number').innerText = supplier.account_number;
        document.getElementById('view-bank_name').innerText = supplier.bank_name;
        document.getElementById('view-ifsc_code').innerText = supplier.ifsc_code;
        document.getElementById('view-discountValueField').style.display = supplier.has_discount? 'block' : 'none';
        document.getElementById('view-discount_value').innerText = supplier.discount_value;


    }
</script>

<style>

.star-rating {
    .star-rating {
        color: #FFD700; /* Gold color for filled stars */
    }
    .star-rating .bi-star {
        font-size: 1.25rem; /* Adjust star size */
    }
    .table {
        border-radius: 0.5rem; /* Rounded corners for the table */
        overflow: hidden; /* Prevent overflow */
    }
    .table-light th {
        background-color: #f8f9fa; /* Light background for header */
        font-weight: bold;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f2f2f2; /* Light gray background for odd rows */
    }
    .table-striped tbody tr:hover {
        background-color: #eaeaea; /* Highlight on hover */
    }}
</style>
@endsection
