<?php

include 'MySqlClass.php';
include 'MongoDbClass.php';

class CommonClass {

    public static function createNewSalon($param) {
        print_r($param);
        $insertParam["collectionName"] = "salon";
        $insertParam["data"] = $param;
        $res = MongoDbClass::MongoInsertOne($insertParam);
        if ($res) {
            return array("msgType" => "Saloon created.");
        }
    }

    public static function addNewBranch($param) {
        $salonId = $param["salonId"];
        unset($param["salonId"]);
        $updateParam["collectionName"] = "salon";
        $updateParam["updateFields"] = array('$set' => array("branch" => $param));
        $updateParam["whereCondition"] = array("s_id" => $salonId);
        $updateRes = MongoDbClass::mongoUpdateOne($updateParam);
        if ($updateRes) {
            return array("msgType" => "Branch added.");
        }
    }

    public static function salonList($param) {
        $id = empty($param["s_id"]) ? "all" : $param["s_id"];
        $where = array();
        if ($id != "all") {
            $where["s_id"] = $id;
        }
        $selectParam["collectionName"] = "salon";
        $selectParam["selectFields"] = array('_id' => 0);
        $selectParam["whereCondition"] = $where;
        $cursor = MongoDbClass::MongoSelect($selectParam);
        $salonData = array();
        if (!empty($cursor)) {
            foreach ($cursor as $document) {
                $salonData[] = $document;
            }
        }
        unset($selectParam);
        return $salonData;
    }

    public static function unsetVariableFromRequest($request, $removeArr = array('action')) {
        foreach ($removeArr as $keyName) {
            unset($request[$keyName]);
        }
        return $request;
    }

    public static function setApiResponse($response) {
        header("Content-Type: application/json; charset=UTF-8");
        return json_encode($response);
    }

    public static function getRequestVariables() {
        $httpMethod = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        $data = array();
        echo $httpMethod;
        switch ($httpMethod) {
            case "GET":
                $data = filter_input_array(INPUT_GET);
                break;
            case "POST":
            case "PUT":
                $data = json_decode(file_get_contents('php://input'), TRUE);
                break;
        }
        return $data;
    }

}
