<?php

include 'DBConn.php';

class MySqlClass {

    public static function MysqlInsert($param) {
        $tableName = $param["tableName"];
        $insertData = $param["insertData"];
        $db = DBConn::getConnection();
        $db->beginTransaction();
        try {
            $columnNameStrTemp = "(";
            $columnValueStrTemp = "(";
            foreach ($insertData as $columnName => $columnValue) {
                $columnNameStrTemp .= $columnName . ",";
                $columnValueStrTemp .= "'$columnValue',";
            }
            $columnNameStr = rtrim($columnNameStrTemp, ",") . ")";
            $columnValueStr = rtrim($columnValueStrTemp, ",") . ")";

            $sql = "INSERT INTO $tableName $columnNameStr VALUES $columnValueStr";
            $res = $db->query($sql);
            return $res;
        } catch (Exception $ex) {
            $db->rollBack();
            print_r($ex->getMessage());
        }
    }

    public static function MysqlSelect($param) {
        $selectData = array();
        try {
            $tableName = $param["table"];
            $column = $param["column"];

            $db = DBConn::getConnection();
            $query = "SELECT $column FROM $tableName ";

            if (!empty($param["where"])) {
                $query .= " WHERE " . $param["where"];
            }
            if (!empty($param["orderBy"])) {
                $query .= " ORDER BY " . $param["orderBy"];
            }
            if (!empty($param["limit"])) {
                $query .= $param["limit"];
            }
            $cursor = $db->query($query);

            if (!empty($cursor) && $cursor->rowCount() > 0) {
                while ($row = $cursor->fetch(PDO::FETCH_ASSOC)) {
                    $selectData[] = $row;
                }
            }
        } catch (Exception $ex) {
            print_r($ex->getMessage());
        }
        return $selectData;
    }

}
