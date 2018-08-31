<?php

include_once("config/config.php");
include 'classes/CommonClass.php';

class Controller {

    public $request;

    public function __construct($req) {
        /* @var $_REQUEST type */
        $this->request = CommonClass::unsetVariableFromRequest($req);
    }

    function salonList() {
        $res = CommonClass::salonList($this->request);
        return $res;
    }

    function createSalon() {
        $res = CommonClass::createNewSalon($this->request);
        return $res;
    }

    function addBranch() {
        $res = CommonClass::addNewBranch($this->request);
        return $res;
    }

    function updateBranch() {
        $res = CommonClass::updateBranchData($this->request);
        return $res;
    }

    function addService() {
        $res = CommonClass::addBranchServices($this->request);
        return $res;
    }

    function updateService() {
        $res = CommonClass::updateBranchServiceData($this->request);
        return $res;
    }

    function getBranchList() {
        $res = CommonClass::saloonBranchList($this->request);
        return $res;
    }

    function getServiceList() {
        $res = CommonClass::branchServiceList($this->request);
        return $res;
    }

}

try {
    $req = CommonClass::getRequestVariables();
    $action = filter_input(INPUT_GET, 'action');
    $conroller = new Controller($req);
    $response = call_user_func(array($conroller, $action));
    echo CommonClass::setApiResponse($response);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
