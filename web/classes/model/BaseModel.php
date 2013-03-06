<?php
class BaseModel extends Model
{
    public function getUserById($UserId)
    {	
    	$sql="SELECT 
    			*
    		  FROM 
    		  	`man_user` 
    		  WHERE 
    		  	`UserId`=$UserId and 
    		  	`isDeleted`=false";
    	$result=mysql_query($sql) or $this->showError(101);    	
    	return $this->getData($result,"object");
    }
}