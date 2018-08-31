<?php

include 'MySqlClass.php';
include 'MongoDbClass.php';

class CommonClass {

    public static function createNewSalon($param) {
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

        $branchParam["br_id"] = $param["br_id"];
        $updateParam["updateFields"] = array('$push' => array("branch" => $branchParam));
        unset($branchParam);
        $updateParam["whereCondition"] = array("s_id" => $salonId);
        $updateRes = MongoDbClass::mongoUpdateOne($updateParam);

        if ($updateRes) {
            $updateParam["collectionName"] = "salon_branch";
            $updateParam["data"] = $param;
            $updateRes = MongoDbClass::MongoInsertOne($updateParam);
            return array("msgType" => "Branch added.");
        }
    }

    public static function updateBranchData($param) {
        $updateParam["collectionName"] = "salon_branch";
        $branchId = $param["br_id"];
        $updateDataArr = $param;
        $updateParam["updateFields"] = array('$set' => $updateDataArr);
        $updateParam["whereCondition"] = array("br_id" => $branchId);
        $updateParam["upsert"] = TRUE;
        $updateRes = MongoDbClass::mongoUpdateOne($updateParam);
        if ($updateRes) {
            return array("msgType" => "Branch details updated.");
        }
    }

    public static function addBranchServices($param) {
        $branchId = $param["br_id"];
        unset($param["br_id"]);
        $collectionName = "salon_branch";

        $collection = MongoDbClass::getCollection($collectionName);
        $cursor = $collection->find(array("services" => array('$exists' => true)));
        $data = iterator_to_array($cursor);
        $updateParam["collectionName"] = $collectionName;
        $updateParam["whereCondition"] = array("br_id" => $branchId);
        if (empty($data)) {
            $updateParam["updateFields"] = array('$set' => $param);
        } else {
            $updateParam["updateFields"] = array('$push' => array("services" => array('$each' => $param["services"])));
        }
        $updateRes = MongoDbClass::mongoUpdateOne($updateParam);
        if ($updateRes) {
            return array("msgType" => "Service added.");
        }
    }

    public static function updateBranchServiceData($param) {
        $updateParam["collectionName"] = "salon_branch";
        $branchId = $param["br_id"];
        $serviceId = $param["s_id"];
        unset($param["br_id"], $param["s_id"]);
        foreach ($param as $key => $value) {
            $insideKey = "services.$." . $key;
            $updateDataArr[$insideKey] = $value;
        }
        $updateParam["updateFields"] = array('$set' => $updateDataArr);
        $updateParam["whereCondition"] = array("br_id" => $branchId, 'services.s_id' => $serviceId);
        $updateRes = MongoDbClass::mongoUpdateOne($updateParam);
        if ($updateRes) {
            return array("msgType" => "Service details updated.");
        }
    }

    public static function branchServiceList($param) {
        $branchId = empty($param["br_id"]) ? "all" : $param["br_id"];
        $where = array();
        if ($branchId != "all") {
            $where["br_id"] = $branchId;
        }
        $selectParam["collectionName"] = "salon_branch";
        $selectParam["selectFields"] = array('_id' => 0, "services" => 1);
        $selectParam["whereCondition"] = $where;
        $cursor = MongoDbClass::MongoSelect($selectParam);
        $serviceData = array();
        if (!empty($cursor)) {
            foreach ($cursor as $document) {
                $serviceData[] = $document;
            }
        }
        unset($selectParam);
        return $serviceData;
    }

    public static function salonList($param) {
        $id = empty($param["s_id"]) ? "all" : $param["s_id"];
        $where = array();
        if ($id != "all") {
            $where["s_id"] = $id;
        }
        $selectParam["collectionName"] = "salon";
        $selectParam["selectFields"] = array('_id' => 0, "services" => 0);
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

    public static function saloonBranchList() {
        $id = empty($param["s_id"]) ? "all" : $param["s_id"];
        $where = array();
        if ($id != "all") {
            $where["s_id"] = $id;
        }
        $selectParam["collectionName"] = "salon";
        $selectParam["selectFields"] = array('_id' => 0, "branch" => 1);
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
