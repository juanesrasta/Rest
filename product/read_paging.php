<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type:Application/json');

include_once '../config/core.php';
include_once '../shared/Utilities.php';
include_once '../config/database.php';
include_once '../models/product.php';

$utilities = new  Utilities();

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$stmt = $product->readPaging($from_record_num, $records_per_page);

$num = $stmt->rowCount();

if($num > 0){

    $arr_product = array();
    $arr_product["records"] = array();
    $arr_product["paging"] = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item = array(
            "id"=>$id,
            "name"=>$name,
            "description"=>$description,
            "price"=>$price,
            "category_id"=>$category_id,
            "category_name"=>$category_name,
        );

        array_push($arr_product["records"],$product_item);
    }

    $total_rows = $product->count();
    
    $page_url = "{$home_url}product/read_paging.php?";

    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);

    $arr_product["paging"] = $paging;

    http_response_code(200);

    echo json_encode($arr_product);

}else{

    http_response_code(400);

    echo json_encode(array("message"=>"No products found"));

}