<?php
/**
 * 
 * Web Session is to handle all the Session login problem within 
 * http://manage.tableapp.com
 * @author Linchew
 *
 */
class WebSession
{
	var $SessionKey_1;
	var $SessionKey_2;
	var $SessionBase;
	var $SessionUser;
	var $SessionSecurity;
	
	public function __construct(){
		//start
		$this->start();
		
		//load Data
		$this->loadSessionSecurityData();
	}
	
	//public function
	public function clearSession()
	{
		session_unset();
	}
	
	public function setSession($userId)
	{
		$sysKey=$this->generateSecurityKey($userId);
		
		$_SESSION[$this->SessionUser]=$userId;
		$_SESSION[$this->SessionSecurity]=$sysKey;
	}
	
	public function getSessionDataArray(){
		return $_SESSION;
	}
	
	public function getUserId(){
		if(isset($_SESSION[$this->SessionUser]))
			return $_SESSION[$this->SessionUser];
		return null;
	}
	
	public function getUserSecurity(){
		if(isset($_SESSION[$this->SessionSecurity]))
			return $_SESSION[$this->SessionSecurity];
		return null;
	}
	
	public function checkSession(&$me){
		//get and check
		if($this->getUserId()==null) 		{ $this->clearSession();return NULL;}
		if($this->getUserSecurity()==null) 	{ $this->clearSession();return NULL;}
		
		if(!$this->isKeyMatch($this->getUserId(), $this->getUserSecurity())){
			$this->clearSession();return NULL;
		}
	}
	
	//private function 
	private function start(){
		session_start();
	}
	
	private function isKeyMatch($userId,$key){
		$sysKey=$this->generateSecurityKey($userId);
		if($key!=$sysKey) return false;
		return true;
	}
	
	private function loadSessionSecurityData(){
		$keyArray=require_once WEBPATH_CONFIG."session.php";
		$this->SessionKey_1=$keyArray["sessionKey_1"];
		$this->SessionKey_2=$keyArray["sessionKey_2"];
		$this->SessionBase=$keyArray["sessionBase"];
		$this->SessionUser=$this->SessionBase."UserId";
		$this->SessionSecurity=$this->SessionBase."Security";
	}
	
	private function generateSecurityKey($userId){
		return md5($this->SessionKey_1.$userId.$this->SessionKey_2);
	}
}