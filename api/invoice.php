<?php
include('../config/db.php');
$uuid =$_POST['value'];

if(!empty($uuid)){
	$sql_invoice ='SELECT * FROM invoice WHERE uuid= :uuid';
    $stmt_list = $PDOLink->prepare($sql_invoice);
    $stmt_list->bindParam(':uuid', $uuid);
    $stmt_list->execute();
    $invoice = $stmt_list->fetchAll(PDO::FETCH_ASSOC);
	foreach ($invoice as $row) {
	    $DetailData = $row['invDetail'];
	    $Detail = json_decode($DetailData, true);
	    $data = array(
	        "uuid" => $row['uuid'],
	        "invNo" => $row['invNo'],
	        "carrierID1" => $Detail['carrierID1'],
	        "invDate" => $row['invDate'].' '.$row['invTime'],
	        "randomNumber" => $row['randomNumber'],
	        "totalAmount" => $Detail['totalAmount']
	    );
	    
	    foreach ($Detail['Details'] as $detail) {
	        $data["Details"][] = array(
	            "amount" => number_format(round($detail['amount'],2),2),
	            "quantity" => number_format(round($detail['quantity'],2),2),
	            "unitprice" => number_format(round($detail['unitprice'],2),2),
	            "description" => $detail['description']
	        );
	    }
	}
}
$json = json_encode($data,JSON_PRETTY_PRINT);

header('Content-Type: application/json');

// 写入 JSON 文件
$file = 'invoice.json';
file_put_contents($file, $json);

?>