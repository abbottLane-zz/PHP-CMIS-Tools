<?php
/**
 * Created by PhpStorm.
 * User: wlane
 * Date: 2/12/15
 * Time: 11:54 AM
 *
 * The AlfAuth object is the default way to configure all of your Alfresco connections in one place. CMIS PHP services like upload, download, delete and get
 * make use of these credentials to talk to Alfresco.
 *
 */
class AlfAuth
{
    private $repo_url= "";
    private $repo_password= "";
    private $repo_username="";
    private $repo_user_folder="";

    function __construct()
    {
        switch(ENVIRONMENT)
        {
            case "production":
               $this->repo_url = $this->repo_url = "http://box.domain:8080/alfresco/service/api/cmis";
                break;
            case "stage":
                $this->repo_url = $this->repo_url = "http://box.domain:8080/alfresco/service/api/cmis";
                break;
            default:
                $this->repo_url = $this->repo_url = "http://box.domain:8080/alfresco/service/api/cmis";
                break;
        }
        //credentials
        $this->repo_password = "removed";
        $this->repo_username = "removed";
        $this->repo_user_folder = "/User+Homes/userFolder";
    }

    public function getRepoUrl()
    {
        return $this->repo_url;
    }
    public function getRepoPassword()
    {
        return $this->repo_password;
    }
    public function getRepoUsername()
    {
        return $this->repo_username;
    }
    public function getRepoUserFolder()
    {
        return $this->repo_user_folder;
    }
}
