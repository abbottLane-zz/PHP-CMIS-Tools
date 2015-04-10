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
$newFolder= ' ';

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

    $iteration = 0;
    foreach ($objs->objectList as $obj)
    {
        if($iteration == $index && $index != 'undefined'){

            //extract content bytes
            $file =$client->getContentStream($obj->id);

            //extract file information
            $fileName = $obj->properties['cmis:name'];

            //populate headers
            header('Content-Description: File Transfer');
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: -1');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length:' . strlen($file));// param: path to file

            //send file
            ob_clean();
            flush();
            echo $file;
        }
        $iteration++;
    }
    exit;
}
catch
(Exception $e){
    echo 'Message: ' . $repo_folder . $newFolder->id . ' doesnt exist.' . $e->getMessage();
}