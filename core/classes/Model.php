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
		
		var $mysqli;
		
		var $isDebugMode;
	
	//abstract
		protected abstract function init();	
		
	//construct, destruct
		public function __construct(){
			//variable setting
			$this->db_conn=false;
			$this->mysqli=new mysqli();
			$this->isDebugMode=isset($_REQUEST["is_debug"]);
			
			//load setting
			$this->load_db_setting();
			
			//init			
			$this->connect_database();
		}
		
		public function __destruct(){
			$this->close_database();
		}
		
	//public functions
		/**
		 * Query the MySQL with only the none SQL injection instruction
		 * @param string $sql
		 * @return mixed
		 */
		public function query($sql){
			$result=$this->mysqli->query($sql) or $this->showError_SQL($sql,$error);			
			return $result;
		}		
		
		
		/**
		 * adv_query is SQL query with SQL injection issue,
		 * @param $sql, $rules, $param1, $param2, $param3, ... list of parameters
		 * @return multitype:
		 */
		public function adv_query(){
			//get parameters
			$args = func_get_args();
			$sql = $args[0];
			
			//remove the first parameters and put the others into a new param_array();
			$param_array=array();
			for($i=1;$i<count($args);$i++){
				array_push($param_array, $args[$i]);
			}
			
			//mysqli->prepare
			//stmt->bind_param
			//stmt->execute
			$stmt=null;
			if($stmt=$this->mysqli->prepare($sql)){
				call_user_func_array(array($stmt, 'bind_param'), $this->refValues($param_array));
				$stmt->execute();
			}else{
				throw new Exception("mysql prepare error", 100);
			}
			
			//get meta data from stmt,
			//meta data would contain the field name
			//(field name are from SELECT * <---) 
			$meta = $stmt->result_metadata();
			
			//get field name
			while ($field = $meta->fetch_field()) {
				$parameters[] = &$row[$field->name];
			}
			
			//bind parameters
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			//stmt->fetch data into results array
			$results=array();			
			while ($stmt->fetch()) {
				foreach($row as $key => $val) {
					$x[$key] = $val;
				}
				array_push($results, $x);				
			}
			
			//fetching the results, return as array			
			return $results;
		}
		
		/**
		 * Get single object result. 
		 * @param $result (array or mysqli_result accetable)
		 * @throws Exception
		 * @return Ambigous <mysqli_fetch_obect($result), Object>| Object
		 */
		public function getData_object($result){
			if($result instanceof mysqli_result) return $this->getData_normal_object($result);
			else if(is_array($result)) return $this->getData_adv_object($result);
			else throw new Exception("Error type of result",100); 
		}
		
		/**
		 * Get a list of results.  
		 * @param $result (array or mysqli_result accetable)
		 * @throws Exception
		 * @return Ambigous <array:, multitype:>|multitype:
		 */
		public function getData_list($result){
			if($result instanceof mysqli_result) return $this->getData_normal_list($result);
			else if(is_array($result)) return $this->getData_adv_list($result);
			else throw new Exception("Error type of result",100);
		}
		
				
		/**
		 * Check if the database is connected
		 * @return boolean
		 */
		public final function isConnected(){
			if($this->db_conn) return true;
			return false;
		}
		
		public final function showError($code,$msg=""){
			throw new Exception($msg, $code);
		}
		
		public function showError_SQL($sql){
			//close database connection
			$this->close_database();
		
			//present the error message
			if($this->isDebugMode){
				$this->showError(103,$sql);
			}else{
				$this->showError(103,"");
			}
		}
		
		/**
		 * Returns a single object of the $result, 
		 * if $result has more then one items, it will return NULL
		 * @param mysqli_result $result
		 * @throws Exception
		 * @return mysqli_fetch_obect($result)
		 */
		private function getData_normal_object(mysqli_result $result){
			//check if the param is mysqli_result type			
			if(!($result instanceof mysqli_result)) throw new Exception("getData with wrong datatype!",100);
			
			//check if the result
			if($result->num_rows!=1) return NULL;			
			return mysqli_fetch_object($result);
		}
		
		/**
		 * Returns a single object of the $result, 
		 * if $result has more then one items, it will return NULL
		 * @param array $result
		 * @throws Exception
		 * @return NULL|$Object
		 */
		private function getData_adv_object(array $result){
			//check if the param is mysqli_result type
			if(!is_array($result)) throw new Exception("getData with wrong datatype!",100);
				
			//check if the result
			if(count($result)!=1) return null;
			return $result[0];
		}
		
		/**
		 * Returns a list of object of the $result
		 * if $result has no item, it will return an empty array
		 * @param mysqli_result $result
		 * @throws Exception
		 * @return array:
		 */
		private function getData_normal_list(mysqli_result $result){
			//check if the param is mysqli_result type
			if(!($result instanceof mysqli_result)) throw new Exception("getData with wrong datatype!",100);
			
			//create an array
			$resultArray=array();				
			while($row=mysqli_fetch_object($result))
	    	{
	    		array_push($resultArray, $row);
	    	}
			return $resultArray;
		}
		
		/**		 
		 * Returns a list of object of the $result
		 * if $result has no item, it will return an empty array
		 * @param array $result
		 */
		private function getData_adv_list(array $result){
			//check if the param is mysqli_result type
			if(!is_array($result)) throw new Exception("getData with wrong datatype!",100);
			
			//return the result
			return $result;
		}
		
		/**
		 * refValues if setted to be used for adv_query
		 * @uses $this->adv_query()
		 * @param $arr
		 * @return multitype:unknown |unknown
		 */
		private function refValues($arr){
			if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
			{
				$refs = array();
				foreach($arr as $key => $value)
					$refs[$key] = &$arr[$key];
				return $refs;				
			}			
			return $arr;
		}
		
	//private functions 
		private function load_db_setting(){
			$dbArray=require_once WEBPATH_CONFIG.'db.php';
			
			if(!isset($dbArray["db_host"]) || empty($dbArray["db_host"])) $this->showError(100,"db file setting error");
			if(!isset($dbArray["db_name"]) || empty($dbArray["db_name"])) $this->showError(100,"db file setting error");
			if(!isset($dbArray["db_user"]) || empty($dbArray["db_user"])) $this->showError(100,"db file setting error");
			if(!isset($dbArray["db_pass"]) || empty($dbArray["db_pass"])) $this->showError(100,"db file setting error");
				
			$this->db_host=$dbArray["db_host"];
			$this->db_name=$dbArray["db_name"];
			$this->db_user=$dbArray["db_user"];
			$this->db_pwd=$dbArray["db_pass"];
		}
		
		private final function connect_database(){
			if(!$this->isConnected()){
				$this->db_conn=$this->mysqli->connect($this->db_host,$this->db_user,$this->db_pwd);
				$this->db_selected=$this->mysqli->select_db($this->db_name);
		
				if (!$this->mysqli->set_charset('utf8')) {
					throw new Exception("", 100);
				}
		
				//old mysql connection
				//---
				//$this->db_conn=mysql_connect($this->db_host,$this->db_user,$this->db_pwd);
				//$this->db_selected=mysql_select_db($this->db_name,$this->db_conn);
				//mysql_query("SET NAMES 'utf8'",$this->db_conn);
			}
		}
		
		private final function close_database(){
			if($this->isConnected())
				@$this->mysqli->close();
				
			//old mysql close
			//---
			//@mysql_close($this->db_conn);
		}
		
}