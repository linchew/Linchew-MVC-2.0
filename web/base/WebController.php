<?php
class WebController extends Controller
{
	//General Variable
		protected $cookie;
		protected $session;
		protected $me;
		protected $view;
	
	//protected function
		protected function action_index(){}
		
		protected function authCheck(){			
			if($this->me==null) $this->redirectTo("");
			
			//set view
			//---
			$this->view=new MenuHtmlView();
						
			//basic parameter
			//---
			$this->view->setVar("me",$this->me);
			$this->view->setVar("currentURL",$this->full_url());
			$this->view->setVar("linkURL",$this->full_url().($this->action!="index"?"?act=".$this->action:""));
		}
		
		protected function init()
		{
			//Create Element
	    	//---			
	    	//$this->cookie=new WebCookie();
	    	//$this->session=new WebSession();
	    	
			//Check Logout
	    	//$this->checkLogout();
	    	//$this->checkFacebook();
	    	
	    	//Check Cookie
	        //$this->session->checkSession($this->me);
	        
	        //$this->authCheck();
	        
			$this->view=new IndexView();
		}
	
	//public function
		public function render($template_path){
			$this->view->render($template_path);
		}		
		
		public function checkLogout()
		{
			/*
			if($this->router->checkLogout())
			{
				$this->session->clearSession();
				$redirectUrl=$this->full_url();
				$redirectUrl.=$this->remove_request("logout");
				
				$this->redirectTo($redirectUrl);
			}
			*/
		}
		
		public function checkFacebook(){
			
		}
		
		public function checkRequest_page($name){
			//checkRequet
			if(!$this->checkRequest($name, "GET", true)) return 1;
			//get value
			$value=$this->getGET($name);
			//type check
			if(!preg_match('/^\d+$/', $value))return 1;
			//transfer and return
			$value=(int)$value;
			if($value<1) return 1;
			else return $value;
		}
		
		public function checkRequest_row($name){
			//checkRequet
			if(!$this->checkRequest($name, "GET", true)) return 50;
			//get value
			$value=$this->getGET($name);
			//type check
			if(!preg_match('/^\d+$/', $value))return 50;
			//transfer and return
			$value=(int)$value;
			if($value<1) return 50;
			else return $value;
		}
		
		public function checkRequest_date($name, $type){
			if(!$this->checkRequest($name, $type, $allowNull)) return null;
			
			//get value
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
			
			//Date Format: 1985-12-05
			if(preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $value,$parts))
		  	{
			    //check weather the date is valid of not
			        if(checkdate($parts[2],$parts[3],$parts[1]))
			        	return $value;			        
			}			
			
			$this->showError(300);
		}
		
		public function checkRequest_double($name, $type="GET", $allowNull=false){			
			if(!$this->checkRequest($name, $type, $allowNull)) return null;
			
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
			if(!is_numeric($value)) $this->showError(300);
			return $value;
		}
		
		public function checkRequest_int($name, $type="GET", $allowNull=false){
			if(!$this->checkRequest($name, $type, $allowNull)) return null;
			
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
			
			if(!is_numeric($value)) $this->showError(300);
			if(!preg_match('/^\d+$/', $value)) $this->showError(300);
			return $value;
		}
		
		public function checkRequest_str($name, $type="GET", $allowNull=false){
			if(!$this->checkRequest($name, $type, $allowNull)) return null;
			
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
						
			return $value;
		}
		
		public function getMethod($type="GET"){
			if($type=="GET"){
				return "getGET";
			}else if($type=="POST"){
				return "getPOST";
			}else if($type=="FILE"){
				return "getFILE";
			}else{
				throw new Exception("System getMethod() Error", 100);
			}
		}
		
		public function getGET($name){
			return isset($this->Request->get[$name])?
					trim($this->Request->get[$name]):
					null;
		}
		
		public function getPOST($name){
			return isset($this->Request->post[$name])?
					trim($this->Request->post[$name]):
					null;
		}
		
		public function getFILE($name){
			return isset($this->Request->file[$name])?
					$this->Request->file[$name]:
					null;
		}
		
		public function issetRequest($name,$type="GET"){
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
			
			if(isset($value)) return true;
			
			return false;
		}
		
		public function isNullRequest($name,$type="GET"){
			$method=$this->getMethod($type);
			$value=$this->{$method}($name);
			
			if(!isset($value)) return true;
			if(!empty($value)) return true;
			
			return false;
		}
		
		//self define
		//must according to the routing path
		public function redirectToLogin(){
			MVCSystem::redirectTo("auth/login");
		}
		
		public function redirectToIndex(){
			MVCSystem::redirectTo("");
		}
		
		public function redirectToMain(){
			MVCSystem::redirectTo("main");
		}
		
		//private function
		private function checkRequest($name, $type="GET", $allowNull=false){
			if($allowNull){
				if($this->isNullRequest($name,$type)) return false;				
			}else{
				if($this->isNullRequest($name,$type))
					$this->showError(110);				
			}
			
			return true;
		}
		
}