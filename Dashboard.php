<?php

include 'classes/MongoDbClass.php';

$insertParam["collectionName"] = "salon";
$insertParam["data"] = array("s_id" => 4, "s_name" => "salon_4", "s_address" => "Jabalpur", "s_rating" => 4);

//$insertRes = MongoDbClass::MongoInsertOne($insertParam);
//print_r($insertRes);

echo "<br />";
echo "<br />";
echo "<br />";

$branch[] = array("br_id" => 1, "br_name" => "kotar", "br_address" => "ward no.3");
$branch[] = array("br_id" => 2, "br_name" => "satna city", "br_address" => "Pateri");
$branch[] = array("br_id" => 3, "br_name" => "Nagod", "br_address" => "rahikwara");
//array('$set' => array("dID" => "1", "availLogin" => "0"));

$updateParam["collectionName"] = "salon";
$updateParam["updateFields"] = array('$set' => array("branch" => $branch));
$updateParam["whereCondition"] = array("s_id" => 3);
$updateParam["whereCondition"] = array("s_id" => 3);
$updateRes = MongoDbClass::mongoUpdateOne($updateParam);
print_r($updateRes);
echo "<br />";
echo "<br />";
echo "<br />";

$param["collectionName"] = "salon";
$param["selectFields"] = array();
$param["whereCondition"] = array('s_id' => 3);
$cursor = MongoDbClass::MongoSelect($param);
foreach ($cursor as $document) {
    print_r($document);
    echo "<br />";
}
