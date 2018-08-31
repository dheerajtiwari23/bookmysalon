<?php

require_once "./vendor/autoload.php";

class MongoDbClass {

    public static function getCollection($collectionName) {
        $db = "sal";
        $collection = (new MongoDB\Client)->$db->$collectionName;
        return $collection;
    }

    public static function MongoInsertOne($param) {
        try {
            $collectionName = $param['collectionName'];
            $data = $param['data'];
            $collection = MongoDbClass::getCollection($collectionName);
            $insertOneResult = $collection->insertOne($data);
            return $insertOneResult;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public static function MongoInsertMany($param) {
        try {
            $collectionName = $param['collectionName'];
            $data = $param['data'];
            $options = array();
            if (!empty($param['order'])) {
                $options['ordered'] = $param["order"];
            }
            if (!empty($param['writeConcern'])) {
                $options['writeConcern'] = $param["writeConcern"];
            }
            $collection = MongoDbClass::getCollection($collectionName);
            $insertOneResult = $collection->insertMany($data, $options);
            return $insertOneResult;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public static function MongoSelect($param) {
        try {
            $collectionName = $param["collectionName"];
            $selectFields = $param["selectFields"];
            $whereCondition = $param["whereCondition"];
            $options = array();

            if (!empty($param["limit"])) {
                $options['limit'] = $param["limit"];
            }
            if (!empty($param["skip"])) {
                $options['skip'] = $param["skip"];
            }
            if (!empty($param["sort"])) {
                $options["sort"] = $param["sort"];
            }
            $collection = MongoDbClass::getCollection($collectionName);
            $cursor = $collection->find($whereCondition, array("projection" => $selectFields));
            return $cursor;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public static function MongoSelectOne($param) {
        try {
            $collectionName = $param["collectionName"];
            $selectFields = $param["selectFields"];
            $whereCondition = $param["whereCondition"];
            $collection = MongoDbClass::getCollection($collectionName);
            $cursor = $collection->findOne($whereCondition, array("projection" => $selectFields));
            return $cursor;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public static function mongoUpdateOne($param) {
        try {
            $collectionName = $param["collectionName"];
            $selectFields = $param["updateFields"];
            $whereCondition = $param["whereCondition"];
            $options = array();
            if (!empty($param["upsert"])) {
                $options['upsert'] = $param["upsert"];
            }

            $collection = MongoDbClass::getCollection($collectionName);
            $response = $collection->updateOne($whereCondition, $selectFields, $options);
            return $response;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public static function mongoUpdateMany($param) {
        try {
            $collectionName = $param["collectionName"];
            $selectFields = $param["updateFields"];
            $whereCondition = $param["whereCondition"];
            $options = array();
            if (!empty($param["upsert"])) {
                $options['upsert'] = $param["upsert"];
            }

            $collection = MongoDbClass::getCollection($collectionName);
            $response = $collection->updateMany($whereCondition, $selectFields, $options);
            return $response;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }
}
