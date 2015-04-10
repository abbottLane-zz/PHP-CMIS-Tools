<?php
/**
 * Created by PhpStorm.
 * User: wlane
 * Date: 2/5/15
 * Time: 2:43 PM
 */
ini_set('log_errors', 'On');
error_reporting(E_ALL);

require_once ('../../../testingCenter/lib/Uploads/CMIS-lib/CMIS-php/atom/cmis/cmis_repository_wrapper.php');
require_once ('../../../testingCenter/lib/Uploads/CMIS-lib/CMIS-php/atom/cmis/cmis_service.php');
require_once ('./alfCreds.php');

define("UPLOAD_DIR", "./uploadsTemp/");

//Repository Connection Information
$alfAuthentication = new AlfAuth();
$repo_url = $alfAuthentication->getRepoUrl();
$repo_username = $alfAuthentication-> getRepoUsername();
$repo_password = $alfAuthentication->getRepoPassword();
$repo_folder = $alfAuthentication->getRepoUserFolder();

$client = new CMISService($repo_url, $repo_username, $repo_password);

$formId = 1;
if(isset($_GET['id'])){
    $formId = $_GET['id'];
}

try { //Check to see if there is already a folder named for this form ID
    $newFolder = $client->getObjectByPath($repo_folder . "/" . $formId);
    $repo_folder = $repo_folder . "/" . $formId;

    $objs = $client->getChildren($newFolder->id);

    foreach ($objs->objectList as $obj)
    {
        if ($obj->properties['cmis:baseTypeId'] == "cmis:document")
        {
            echo "<option>" . $obj->properties['cmis:name'] . "</option>";
        }
    }
}
catch
    (Exception $e){ // If queried folder doesn't exist, create it
        echo 'Message: ' . $repo_folder . "/" . $formId . ' doesnt exist.' . $e->getMessage();
    }
