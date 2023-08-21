require('./bootstrap');
window.$ = window.jQuery = require('jquery');

// Start jQuery functions
$(document).ready(function () {

    // List all the products on page load
    loadTableData();

    var allProducts = [];

    function loadTableData() {
        $.ajax({
            type: "GET",
            url: "/",
            dataType: 'json',
            success: function (response) {
                allProducts = response.products;
                if (allProducts.length === 0) {
                    displayNoProductsMessage();
                } else if (allProducts.length > 0) {
                    updateTable(allProducts);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
            }
        });
    }


    // Create the products table
    function updateTable(products) {
        var tableBody = $('#tableBody');
        tableBody.empty(); // Clear the existing table rows
        var totalValue = 0;

        console.log(products);

        $.each(products, function (index, product) {
            var row = '<tr>' +
                '<td class="product-name">' + product.product_name + '</td>' +
                '<td class="quantity">' + product.quantity + '</td>' +
                '<td class="price">' + formatAmount(product.price) + '</td>' +
                '<td>' + product.date_submitted + '</td>' +
                '<td>' + formatAmount((product.price * product.quantity)) + '</td>' +
                '<td><button type="button" class="btn btn-success btn-sm edit-btn mx-2" data-index="' + product.id + '">Edit</button>' +
                '<button type="button" class="btn btn-danger btn-sm delete-btn" data-index="' + product.id + '">Delete</button></td>' +
                '</tr>';

            tableBody.append(row);

            // Perform a summation of the product price * quantity to the get total value
            totalValue += parseFloat(product.price) * parseInt(product.quantity);
        });

        // Update the total value in the tfoot element
        var totalValueElement = $('#totalValue');
        totalValueElement.text(formatAmount(totalValue));
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle form submission
    $("#submit").click(function (e) {
        e.preventDefault();

        const product_name = $("#product-name");
        const quantity = $("#quantity");
        const price = $("#price");

        // Reset previous error messages and remove "is-invalid" class
        $(".invalid-feedback").hide();
        $(".form-control").removeClass("is-invalid");

        // Validate input fields
        let isValid = true;
        if (product_name.val().trim() === '') {
            product_name.addClass("is-invalid");
            $("#product-name-error").show();
            isValid = false;
        }
        if (quantity.val().trim() === '') {
            quantity.addClass("is-invalid");
            $("#quantity-error").show();
            isValid = false;
        }
        if (price.val().trim() === '') {
            price.addClass("is-invalid");
            $("#price-error").show();
            isValid = false;
        }

        const data = {
            product_name: product_name.val(),
            quantity: parseInt(quantity.val()),
            price: parseFloat(price.val()),
        };

        // Handle AJAX form submissions for creating and updating
        if (isValid && $(this).text() === 'Submit') {
            $.ajax({
                type: "POST",
                url: "/product/create",
                data: data,
                dataType: 'json',
                success: function (response) {
                    $('.no-products').hide();
                    $('table').show();
                    updateTable(response.products);
                    clearForm();
                    showSuccessMessage(response.message);
                }
            });
        } else if (isValid && $(this).text() === 'Update') {
            // Handle edit product
            const product_id = $(this).data("index");
            $.ajax({
                type: "PUT",
                url: `/product/${product_id}`,
                data: data,
                success: function (response) {
                    updateTable(response.products);
                    clearForm();
                    showSuccessMessage(response.message);

                    // Change button text to 'submit'
                    $('#submit').text('Update');
                }
            });
        }

    });

    // Handle edit button click and populate the form
    $(document).on("click", ".edit-btn", function () {

        const productNameTd = $(this).closest("tr").find(".product-name");
        const quantityTd = $(this).closest("tr").find(".quantity");
        const priceTd = $(this).closest("tr").find(".price");

        // Change button text to 'update'
        $('#submit').text('Update').attr("data-index", $(this).data("index"));

        // ... Populate form with editedProduct details
        $("#product-name").val(productNameTd.text());
        $("#quantity").val(quantityTd.text());
        $("#price").val(parseFloat(priceTd.text().replace(/[^\d.-]/g, '')));

    });

    // Handle delete button
    $(document).on("click", ".delete-btn", function () {

        const product_id = $(this).data("index");
        $.ajax({
            type: "DELETE",
            url: `/product/${product_id}`,
            success: function (response) {
                loadTableData();
                showSuccessMessage(response.message);
            }
        });
    });

    // Clear the form after submitting
    function clearForm() {
        $("#product-name").val('');
        $("#quantity").val('');
        $("#price").val('');
    }

    /**
     * Format the number to display in amount form.
     * Round off to two decimal places.
     * Include commas
     */
    function formatAmount(number) {
        return parseFloat(number).toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Show success message, Hide after 3 seconds.
    function showSuccessMessage(message) {
        const success_message = $("#success-message");
        success_message.text(message);
        success_message.fadeIn();
        setTimeout(function () {
            success_message.fadeOut();
        }, 3000);
    }

    // Hide the products table when there are no products.
    function displayNoProductsMessage() {
        $('table').hide();
        var message = '<div class="alert alert-success mt-3 text-center text-sm-center no-products">No products available.</div>';
        $('.products-list h3').append(message);
    }

});

