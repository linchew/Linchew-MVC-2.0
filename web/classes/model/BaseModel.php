<?php
class BaseModel extends WebModel
{
    public function getUserList()
    {	
    	$sql="SELECT
    			*
    		  FROM
    			`manage_user`
    		  WHERE
    			`Status`=\"isPublished\"";
    	$result=$this->query($sql);
    	return $this->getData_list($result);
    }

    public function getUserById($userId){
    	$sql="SELECT
    			*
    		  FROM
    			`manage_user`
    		  WHERE 
    			`Id` = ? AND
    			`Status`=\"isPublished\"";
    	$result=$this->adv_query($sql, "s", $userId);
    	return $this->getData_object($result);
    }
}