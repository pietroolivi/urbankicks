<?php
require_once("bootstrap.php");

// Define sizes per genre
$sizesRanges = [
    'kids' => range(28, 36),
    'man' => range(37, 45),
    'woman' => range(37, 45)
];

// Define available colors
$colors = ['black', 'blue', 'green', 'purple', 'red', 'white'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand-product-admin'];
    $model = $_POST['model-product-admin'];
    $genres = isset($_POST['genre']) ? $_POST['genre'] : [];
    $category = $_POST['category-product-admin'];
    $description = $_POST['description-product-admin'];
    $price = $_POST['price-product-admin'];

    // Validate required fields
    if (empty($brand) || empty($model) || empty($genres) || empty($category) || empty($description) || empty($price)) {
        $error = "All fields are required";
    } else {
        try {
            // Process variants data
            $variants = [];
            foreach ($genres as $genre) {
                foreach ($sizesRanges[$genre] as $size) {
                    foreach ($colors as $color) {
                        $quantity = isset($_POST["quantity-{$size}-{$color}"]) ? 
                                  intval($_POST["quantity-{$size}-{$color}"]) : 0;
                        $variants[] = [
                            'color' => ucfirst($color),
                            'size' => $size,
                            'quantity' => $quantity
                        ];
                    }
                }
            }

            // Add product using DatabaseHelper
            $productId = $dbh->addProductWithVariants(
                ucfirst($brand),
                $model,
                $genres,
                ucfirst($category),
                $description,
                $price,
                $variants
            );

            header("Location: admin_products.php");
            exit();
            
        } catch (Exception $e) {
            $error = "Error adding product: " . $e->getMessage();
        }
    }
}

// Setup template parameters
$templateParams["title"] = "Add Product";
$templateParams["name"] = "admin_add_product_content.php";

require 'Template/admin_base.php';
?>