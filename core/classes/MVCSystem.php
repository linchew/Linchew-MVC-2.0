<?php
/**
 * 
 * Sytem, the begin of the mvc structure
 * @author Linchew
 *
 */
class MVCSystem{
	
	private $Router;
	private $Request;
	private $ErrorHandler;
	
	public function __construct(){
		//init_object
		try{
			$this->Request=new Request();
			$this->Router=new Router();
			
			//Fetch the Reqeust
			$this->Request->add_from_router_request($this->Router->getRequest());			
			
			//Check for Control & Action
			$this->performControl();
			
		}catch(Exception $e){
			
			$this->ErrorHandler=new ErrorHandler();
			$this->ErrorHandler->showError($e->getCode(),$e->getMessage());			
		}
	}
	
	public function performControl(){
		if(class_exists($this->Router->getControl()."Controller")){
			//Dynamic load Controller
			$ClassName=$this->Router->getControl()."Controller";
			$Controller=new $ClassName($this->Request);
			
			//if Action!=null, then match with the Controller::Action
			$Controller->run($this->Router->getAction());			
		}else{
			throw new Exception("Routing Table setting Error", 100);
		}
	}
	
	/**
	 * If debug mode is on, then show the variable 
	 * @param array or string $var
	 */
	public static function debug($var){
		if(isset($_REQUEST["is_debug"])){			
			if(is_array($var)){
				print_r($var);
			}else{
				echo $var;
			}
			echo nl2br("\n");
		}
	}
	
	public static function getCurrentURL(){
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
		
		for($i= 0;$i < sizeof($scriptName);$i++){
			if ($requestURI[$i]     == $scriptName[$i]){
				unset($requestURI[$i]);
			}
		}
		$currentURL=array_values($requestURI);		
	}
	
	public static function redirectTo404($code, $type, $message){
		header("Location: ".WEBSITE_ROOT."/404?code=$code&type=$type&msg=".urlencode($message));
		exit();
	}
	
	public static function redirectTo($targetPath){
		header("Location: ".WEBSITE_ROOT."/".$targetPath);
	}
}



/*

*/