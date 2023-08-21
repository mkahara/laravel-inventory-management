<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $file_path;

    public function __construct()
    {
        // Set the file path for the JSON data
        $this->file_path = storage_path('app/products.json');
    }

    /**
     * Display the add product form and the products list.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Load existing products
        $existing_products = $this->loadProducts();

        // If JSON response is requested, return JSON data
        if ($request->wantsJson()) {
            return response()->json(['products' => $existing_products]);
        }

        // Return HTML view with product data
        return view('product.index', ['products' => $existing_products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Load existing products
        $existing_products = $this->loadProducts();

        // Create new product information
        $product_info = [
            'id' => Str::uuid(),
            'product_name' => $request->input('product_name'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'date_submitted' => Carbon::now()->toDateTimeString(),
        ];

        // Add the new product to the existing list
        $existing_products[] = $product_info;

        // Save the updated products list
        $this->saveProducts($existing_products);

        // Re-sort the products by date_submitted in descending order
        usort($existing_products, function ($a, $b) {
            return strtotime($b['date_submitted']) - strtotime($a['date_submitted']);
        });

        // Return success message and updated products list
        return response()->json(['message' => 'Product saved successfully!', 'products' => $existing_products]);
    }

    /**
     * Update the specified product in the JSON data.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Load existing products
        $existing_products = $this->loadProducts();

        // Find the index of the product with the specified ID
        $product_index = $this->findProductIndex($existing_products, $id);

        if ($product_index !== false) {
            // Update product attributes
            $existing_products[$product_index] = array_merge($existing_products[$product_index], [
                'product_name' => $request->input('product_name'),
                'quantity' => $request->input('quantity'),
                'price' => $request->input('price'),
            ]);

            // Save the updated products list
            $this->saveProducts($existing_products);

            // Return success message and updated products list
            return response()->json(['message' => 'Product updated successfully!', 'products' => $existing_products]);
        }

        // Product with specified ID not found
        return response()->json(['message' => 'Product not found'], 404);
    }

    /**
     * Delete the specified product in the JSON data.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $existing_products = $this->loadProducts();

        $product_index = $this->findProductIndex($existing_products, $id);

        if ($product_index !== false) {
            array_splice($existing_products, $product_index, 1);

            $this->saveProducts($existing_products);

            return response()->json(['message' => 'Product deleted successfully!', 'products' => $existing_products]);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }


    /**
     * Load products from the JSON file and sort by date_submitted in descending order.
     */
    private function loadProducts()
    {
        if (File::exists($this->file_path)) {
            $existing_products = json_decode(File::get($this->file_path), true);

            if (empty($existing_products)) {
                $existing_products = [];
            }

            // Sort products by date_submitted in descending order
            usort($existing_products, function ($a, $b) {
                return strtotime($b['date_submitted']) - strtotime($a['date_submitted']);
            });
        } else {
            $existing_products = [];
        }

        return $existing_products;
    }

    /**
     * Save products to the JSON file.
     *
     * @param array $products
     * @return void
     */
    private function saveProducts($products)
    {
        $jsonData = json_encode($products, JSON_PRETTY_PRINT);
        File::put($this->file_path, $jsonData);
    }

    /**
     * Find the index of a product by its ID.
     *
     * @param array $products
     * @param string $id
     * @return int|false
     */
    private function findProductIndex($products, $id)
    {
        foreach ($products as $index => $product) {
            if ($product['id'] === $id) {
                return $index;
            }
        }

        return false;
    }
}
