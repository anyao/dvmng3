<?php
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$msgService = new msgService($sqlHelper);
$msgService->schedule();
?>