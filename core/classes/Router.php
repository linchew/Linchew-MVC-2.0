<?php 

/**
 * Main Router of the MVC System,
 * First, generate routing table, 
 * Then, mapping the routing table with the user URL
 * @author Linchew
 *
 */
Class Router{
	
	const VAR_ACTION	= "/^./i";
	const VAR_ANY 		= "/^./i";
	const VAR_DAY 		= "/^\b\d{1,2}\b/i";
	const VAR_MONTH 	= "/^\b\d{1,2}\b/i";
	const VAR_YEAR 		= "/^\b\d{4}\b/i";
	const VAR_DATE 		= "/^\b\d{1,2}\b-\b\d{1,2}\b-\b\d{4}\b/i";
	const VAR_DOUBLE 	= "/^\d+\.\d+$/i";
	const VAR_NUMERIC 	= "/^\d+$/i";
	
	public $Request;
	public $Control;
	public $Action;
	
	public $routing_file;
	public $routing_table;
	
	public $userURL;
	public $matchNode;
		
	public function __construct(){
		//parameter setting
		$this->Request=array();
		$this->routing_table=array();
		$this->matchNode=null;
		
		//load Routing talbe routes.php
		$this->loadFile();
		//gerate Routing table
		$this->generateRoutingTable();
		//start routing
		$this->routing();		
	}
	
	public function getControl(){
		return $this->Control;
	}
	
	public function getAction(){
		return $this->Action;
	}
	
	public function getRequest(){
		return $this->Request;
	}
	
	private function routing(){
		//get and handle the request URL
		$this->getURLRequest();
		$this->handleURLRequest();
		
		//mapping with routing Table rules
		$this->mapping();
		//setting Router Control and Action
		$this->handleControlNAction();
		//mapping with the routing requests
		$this->handleRequest();
		//dealing with no action routing,
		//handle the action from the done request array
		$this->handleActionFromRequest();
		
		//::debug issue
		MVCSystem::debug($this->routing_table);
	}
	
	private function mapping(){
		//if no command found, then perform _root_
		if(count($this->userURL)>0){
			
			//get command form the head index
			$command=$this->userURL[0];
			
			//check if the command match the routing talbe index
			if(isset($this->routing_table[$command])){
				
				//get into the rule with the routingTable index				
				foreach($this->routing_table[$command] as $node){
					//first, compare with the length of the rule
					if(count($this->userURL) == count($node->array_routingRules)+1){
						
						//this part is the matching with the route
						$isMatch=true;
						foreach($node->array_routingRules as $i=>$rule){
							if(!preg_match($rule, $this->userURL[$i])){
								$isMatch=false;
							}
						}
						
						//if isMatch, then match!
						if($isMatch){
							$this->matchNode=$node;							
							break;
						}
					}
				}
				
				//if matches with no node rule
				if($this->matchNode==null){
					throw new Exception("", 101);
				}
			}else{
				throw new Exception("", 101);
			}
		}
		//if this->userURL ==0, means to index
		else{
			$this->matchNode=$this->routing_table["root"][0];		
		}		
	}
	
	private function getURLRequest(){
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
		
		for($i= 0;$i < sizeof($scriptName);$i++){
			if(!isset($requestURI[$i])) break;
			if ($requestURI[$i]     == $scriptName[$i]){
				unset($requestURI[$i]);
			}
		}
		$this->userURL=array_values($requestURI);
	}
	
	//handle with the empty array element
	//handle with the latest part ?
	private function handleURLRequest(){
		//handle with the empty array element
		for($i=0;$i<count($this->userURL);$i++){
			if(empty($this->userURL[$i])) unset($this->userURL[$i]);
		}
		
		//handle with the latest part "?"
		if(count($this->userURL)>0){
			$last_index=count($this->userURL)-1;
			$lastURL=$this->userURL[$last_index];
			if(strpos($lastURL,"?")!==FALSE){
				$this->userURL[$last_index]=substr($lastURL, 0, strpos($lastURL,"?"));
				if($this->userURL[$last_index]=="") unset($this->userURL[$last_index]);			
			}
		}
	}
	
	//handle with the match node and the matching request
	//if find out node with () and return with the certern [variable]
	private function handleRequest(){
		if($this->matchNode!=null){
			if(count($this->matchNode->array_mappingRequests)>0){
				foreach($this->matchNode->array_mappingRequests as $key=>$request){
					//if userURL[$key]=="", then Exception
					if($this->userURL[$key]==""){
						throw new Exception("", 101);
					}
					
					//mapping with the request
					$this->Request[$request]=$this->userURL[$key];
				}
			}
		}
	}
	
	private function handleControlNAction(){
		if($this->matchNode==null){
			throw new Exception("", 101);
		}
		
		$this->Control=$this->matchNode->control;
		$this->Action=$this->matchNode->action;
	}
	
	//if $this->action is null, then start to get the action from the $this->requests
	private function handleActionFromRequest(){
		if($this->Action=="" && $this->Action ==null && empty($this->Action)){
			if(isset($this->Request["action"])){
				if($this->Request["action"]!=""){
					//set the action from the request					
					$this->Action=$this->Request["action"];
				}
			}
		}
	}
	
	private function loadFile(){
		$this->routing_file=require_once WEBPATH_CONFIG.'routes.php';
	}
	
	private function generateRoutingTable(){
		foreach($this->routing_file as $route => $rule){
			
			//getting rules obj
			$array_routeObj=explode("/", $route);
			//getting mapping obj
			$array_ruleObj=explode("/", $rule);
			
			//error pre-check
			if($array_routeObj[0]=="") throw new Exception("Routes setting error", 100);
			if($array_ruleObj[0]=="") throw new Exception("Routes setting error", 100);
			
			//get Command & Control
			$command=$array_routeObj[0];
			$control=$array_ruleObj[0];
			$action=isset($array_ruleObj[1])?$array_ruleObj[1]:"";

			//new Route Node
			$node=new RouteNote();
			$node->command=$command;
			$node->control=$control;
			$node->action=$action;
			
			//generate routing Table
			foreach($array_routeObj as $_route){
				if(count($array_routeObj)>1){
					for($i=1;$i<count($array_routeObj);$i++){
						$single_route=$array_routeObj[$i];
						if($this->isSpecialVariable($single_route)){
							$node->array_routingRules[$i]=$this->generateSpecialVariable($single_route);
							$node->array_mappingRequests[$i]=$this->getRuleVariable($single_route);
							if(!$this->isSpecialACTION($single_route)){
								if($this->getRuleVariable($single_route)=="action")
									throw new Exception("Routes setting error", 100);
							}
						}else{
							$node->array_routingRules[$i]=$this->generateNormalVariable($single_route);
						}
					}	
				}
			}
			
			//check if the command is existed in the routing Table
			if(!isset($this->routing_table[$command])){
				$this->routing_table[$command]=array();
			}
			
			//push into node
			array_push($this->routing_table[$command], $node);
		}
	}
	
	private function isSpecialACTION($str){
		if(strpos($str,":ACTION")) return true;
		return false;
	}
	
	private function isSpecialVariable($str){
		if(strpos($str,":ACTION")) 		return true;
		if(strpos($str,":ANY")) 		return true;
		if(strpos($str,":DAY")) 		return true;
		if(strpos($str,":MONTH")) 		return true;
		if(strpos($str,":YEAR")) 		return true;
		if(strpos($str,":DATE")) 		return true;
		if(strpos($str,":NUMERIC")) 	return true;
		if(strpos($str,":DOUBLE")) 		return true;
		
		return false;
	}
	
	private function generateSpecialVariable($str){
		if(strpos($str,":ACTION")) 		return self::VAR_ACTION;
		if(strpos($str,":ANY")) 		return self::VAR_ANY;
		if(strpos($str,":DAY")) 		return self::VAR_DAY;
		if(strpos($str,":MONTH")) 		return self::VAR_MONTH;
		if(strpos($str,":YEAR")) 		return self::VAR_YEAR;
		if(strpos($str,":DATE")) 		return self::VAR_DATE;
		if(strpos($str,":NUMERIC")) 	return self::VAR_NUMERIC;
		if(strpos($str,":DOUBLE")) 		return self::VAR_DOUBLE;
		
		return null;
	}
	
	private function generateNormalVariable($str){
		return "/^\b$str\b/i";
	}
	
	private function getRuleVariable($str){
		$_start=strpos($str, "|");
		$_end=strpos($str, ")");
		
		if(!$_start || !$_end) throw new Exception("Routes setting error", 100);
		if($_end<$_start) throw new Exception("Routes setting error", 100);
		
		return substr($str, $_start+1, $_end-$_start-1);
	}
	
	public static function redirectTo404($code,$type,$message){
		header("Location: ".WEBSITE_ROOT."404?code=$code&type=$type&msg=".urlencode($message));
		exit();
	}
}

/**
 * 
 * RouteNoe in the Routing Table
 * @author Linchew
 *
 */
Class RouteNote{
	
	var $command;
	var $control;
	var $action;
	
	var $array_routingRules;
	var $array_mappingRequests;
	
	public function __construct(){
		$this->array_routingRules=array();
		$this->array_mappingRequests=array();
	}
}