<?php

include 'MySqlClass.php';

class CommonClass {

    public static function createNewSalon($param) {
        $insertParam["tableName"] = "saloon_details";
        $insertParam["insertData"] = $param;
        $res = MySqlClass::MysqlInsert($insertParam);
        if ($res) {
            return array("msgType" => "Saloon created.");
        }
    }

    public static function salonList($param) {
        $id = empty($param["s_id"]) ? "all" : $param["s_id"];

        $selectParam["table"] = "saloon_details";
        $selectParam["column"] = "*";
        $where = "";
        if ($id != "all") {
            $where = "s_id = " . $id;
        }
        $selectParam["where"] = $where;
        $salonData = MySqlClass::MysqlSelect($selectParam);
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
                $data = filter_input_array(INPUT_POST);
                break;
        }
        return $data;
    }

}
