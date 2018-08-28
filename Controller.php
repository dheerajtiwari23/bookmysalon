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

}

try {
    $req = CommonClass::getRequestVariables();
    $action = $req["action"];
    $conroller = new Controller($req);
    $response = call_user_func(array($conroller, $action));
    echo CommonClass::setApiResponse($response);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
