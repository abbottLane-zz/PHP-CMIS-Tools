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
$index= 'undefined';
if(isset($_GET['id'])){
    $formId = $_GET['id'];
}
if(isset($_GET['item'])){
    $index = $_GET['item'];
}

try { //Check to see if there is already a folder named for this form ID
    $newFolder = $client->getObjectByPath($repo_folder . "/" . $formId);
    $repo_folder = $repo_folder . "/" . $formId;

    $objs = $client->getChildren($newFolder->id);

    $iteration=0;
    foreach ($objs->objectList as $obj)
    {
  	//the index is the currently selected test doc item in the option box:
	//so if the index matches the object's position in the repo, delete that object.
        if($iteration == $index && $index != 'undefined'){
            //delete this object
            $client->deleteObject($obj->id);
        }
	// if the current object is not the one we want to delete, echo to repopulate it on the option list. 
        else if ($obj->properties['cmis:baseTypeId'] == "cmis:document")
        {
            echo "<option>" . $obj->properties['cmis:name'] . "</option>";
        }
        $iteration++;
    }
}
catch
(Exception $e){
    echo 'Message: ' . $repo_folder . "/" . $formId . ' doesnt exist.' . $e->getMessage();
}
