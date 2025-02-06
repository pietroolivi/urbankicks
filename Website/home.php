<?php
require_once("bootstrap.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$products = $dbh->getFilteredProducts();

if (!empty($search)) {
    $products = array_filter($products, function($product) use ($search) {
        $searchLower = strtolower($search);
        return (
            str_contains(strtolower($product['Nome']), $searchLower) || 
            str_contains(strtolower($product['Descrizione']), $searchLower) ||
            str_contains(strtolower($product['Marca']), $searchLower)
        );
    });
}

$templateParams["title"] = "Home";
$templateParams["name"] = "home_content.php";
$templateParams["products"] = $products;
$templateParams["brands"] = $dbh->getDistinctBrands();
$templateParams["js"] = [
    "JS/Objects/listenerObjectSetting.js",
    "JS/Functions/listeners.js"/*,
    "JS/home.js"*/
];

require_once("Template/base.php");
?>