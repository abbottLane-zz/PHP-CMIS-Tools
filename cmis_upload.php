<?php
ini_set('log_errors', 'On');

error_reporting(E_ALL);
require_once ('../../../testingCenter/lib/Uploads/CMIS-lib/CMIS-php/atom/cmis/cmis_repository_wrapper.php');
require_once ('../../../testingCenter/lib/Uploads/CMIS-lib/CMIS-php/atom/cmis/cmis_service.php');
require_once ('./alfCreds.php');
define("UPLOAD_DIR", "./uploadsTemp/");

//------------------------------Handle Upload--------------------------


    //Repository Connection Information
    $alfAuthentication = new AlfAuth();
    $repo_url = $alfAuthentication->getRepoUrl();
    $repo_username = $alfAuthentication-> getRepoUsername();
    $repo_password = $alfAuthentication->getRepoPassword();
    $repo_folder = $alfAuthentication->getRepoUserFolder();

    $client = new CMISService($repo_url, $repo_username, $repo_password);

    $data = array();
    $uploadOk = 1;

    //retrieve the formId ---------------------------
    $formId = null;
    if(isset($_GET['id']))
    {
        $formId = $_GET['id'];
        try{ //Check to see if there is already a folder named for this form ID
            $newFolder = $client->getObjectByPath($repo_folder."/".$formId);
            $repo_folder = $repo_folder."/".$formId;

        }
        catch(Exception $e){ // If queried folder doesn't exist, create it
            echo 'Message: '.$repo_folder. ' doesnt exist.' .$e->getMessage();
            try
            {
                $newFolder = $client->createFolder($client->getObjectByPath($repo_folder)->id, $formId); //correct code for creating folder named $formId
            }
            catch(Exception $e)
            {
                echo "WARNING: Ajax call overlap caused multiple folder-creation events. Not a serious error. This catch block handles the error
                which only occurs when the folder we are looking for already exists, and allows us to proceed with our upload anyways.";
            }
            $repo_folder = $repo_folder."/".$formId;
        }
    }

    //Upload files to the above-determined $newFolder folder (based on formID)
    foreach($_FILES as $file) {
        //Command Line Arguments: "php -f path/to/file fileName.txt mimetype/text
        $repo_file_name = basename($file['name']);//$_SERVER["argv"][2];

        if ($uploadOk) {
            //Extract File Data Type
            $mimeType = $file['type'];

            //extract file data
            $data = file_get_contents($file['tmp_name']);

            /// CREATE TEST DOC FILE ON REPO
            $myfolder = $client->getObjectByPath($repo_folder);
            try {
                $obs = $client->createDocument($myfolder->id, $repo_file_name, array(), $data, $mimeType);
            } catch (Exception $e) {
                echo 'Upload failed for some reason. This shouldnt happen, so just try the upload again.  ' . "\n\n" . $e->getMessage();
            }

            //If you get here, upload was successful
            echo " Upload successful!";
        }
    }



