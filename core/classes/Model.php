<?php
/**
 * 
 * Model is the base Class for refering. It contains the Database connection.
 * @filesource web/config/db.php
 * @author Linchew
 *
 */
abstract class Model{
	
	//General Variable
		var $db_host;
		var $db_name;
		var $db_user;
		var $db_pwd;
		
		var $db_conn;
		var $db_selected;
	
	//abstract
		protected abstract function init();
		
	//construct and destruct
		public function __construct(){
			//variable setting
			$this->db_conn=false;
			
			//init
			$this->connect_database();
			
		}
		
		public function __destruct(){
			$this->close_database();
		}
	
	//public functions
		public final function connect_database(){
			if(!$this->isConnected()){
		    	$this->db_conn=mysql_connect($this->db_host,$this->db_user,$this->db_pwd);
		        $this->db_selected=mysql_select_db($this->db_name,$this->db_conn);
		        mysql_query("SET NAMES 'utf8'",$this->db_conn);
	    	}
		}
		
		public final function close_database(){
			if($this->isConnected())
				@mysql_close($this->db_conn);
		}
		
		public final function isConnected(){
			if($this->db_conn) return true;
			return false;
		}
		
		public final function showError($code,$msg=""){
			throw new Exception($msg, $code);
		}
		
		protected function getData_object(){
			if(mysql_num_rows($result)!=1) return NULL;
			
			return mysql_fetch_object($result);
		}
		
		protected function getData_list(){
			$resultArray=array();
				
			while($row=mysql_fetch_object($result))
	    	{
	    		array_push($resultArray, $row);
	    	}
		
			return $resultArray;
		}
	
	//private functions 
		private function load_db_setting(){
			$dbArray=require_once WEBPATH_CONFIG.'db.php';
		}
}