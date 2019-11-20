<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Header:Access");
header("Access-Control-Allow-Methods:GET");
header("Access-Control-Allow-Credential:True");
header("Content-Type:Application/json");

include_once '../config/database.php';
include_once '../models/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$product->id = isset($_GET['id']) ? $_GET['id']: die();

//product details
$product->readOne();

if($product->name != null){

    $arr_product = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name,
    );

    http_response_code(200);

    echo json_encode($arr_product);

}
else{

    http_response_code(400);

    echo json_encode(array('message'=>'Product not exist'));

}