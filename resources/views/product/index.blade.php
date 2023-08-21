@extends('layouts.app')

@section('page-title', 'Laravel Inventory Management')

<div class="container my-5">
    <h1 class="text-center">@yield('page-title', 'Laravel Inventory Management')</h1>
    <div class="products-form p-3">
        <h3 class="text-primary">Add Products</h3>
        <form name="add-product" id="add-product">
            <input type="hidden" id="create-route" value="">
            <div class="mb-3">
                <label for="product-name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product-name" placeholder="Enter product name" required>
                <div class="invalid-feedback" id="product-name-error">Product name is required.</div>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity in Stock</label>
                <input type="number" class="form-control" id="quantity" placeholder="Enter quantity" required>
                <div class="invalid-feedback" id="quantity-error">Quantity is required.</div>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price per Item</label>
                <input type="number" class="form-control" id="price" placeholder="Enter price" required>
                <div class="invalid-feedback" id="price-error">Price is required.</div>
            </div>
            <button type="submit" class="btn btn-primary text-capitalize" id="submit">Submit</button>
            <div id="success-message" class="alert alert-success mt-3" style="display: none;"></div>
            <div class="alert alert-success mt-3" id="success-message" style="display: none;"></div>
        </form>
    </div>

    <div class="products-list p-3 mt-5">
        <h3 class="text-primary">Products List</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in Stock</th>
                <th>Price per Item</th>
                <th>Date Submitted</th>
                <th>Total Value</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <!-- Table rows will be dynamically populated here using JavaScript -->
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th id="totalValue">0.00</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
