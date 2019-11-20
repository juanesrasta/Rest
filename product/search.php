<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type:Application/json');

include_once '../config/core.php';
include_once '../config/database.php';
include_once '../models/product.php';

$database = new dataBase();
$db = $database->getConnection();

$product = new  Product($db);

$keywords = isset($_GET['s']) ? $_GET['s']: "";

//query products
$smtm = $product->search($keywords);

$num = $smtm->rowCount();

if($num > 0){

    $arr_product = array();
    $arr_product["records"] = array();

    while($row = $smtm->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item = array(
            "id"=>$id,
            "name"=>$name,
            "price"=>$price,
            "description"=>$description,
            "category_id"=>$category_id,
            "category_name"=>$category_name,
        );

        array_push($arr_product["records"],$product_item);

    }

    http_response_code(200);

    echo json_encode($arr_product);

}
else{

    http_response_code(404);

    echo json_encode(array("message"=>"No products found."));

}