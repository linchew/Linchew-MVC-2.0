<?php
Class respJASON
{	
 	public static function showResult($result)
 	{	 
 		$this->show($result);
 	}
 	
 	public static function showError($errorCode, $errorType, $errorMsg)
 	{	 		
 		$result=array();
 		$error=array();
 			 		
 		$result["result"]=false;
 		$result["error"]=array(
 			"type"		=> $errorType,
 			"code"		=> $errorCode,
 			"message"	=> $errorMsg
 		);
 		
 		$this->show($result);
 	}	 	
 	
 	private function show($object)
 	{
 		header('Content-Type: application/json; charset=utf-8');
 		echo $this->indent(json_encode($object));
 		exit();
 	}
 	
	private function indent($json) {

	    $result      = '';
	    $pos         = 0;
	    $strLen      = strlen($json);
	    $indentStr   = '  ';
	    $newLine     = "\n";
	    $prevChar    = '';
	    $outOfQuotes = true;
	
	    for ($i=0; $i<=$strLen; $i++) {
	
	        // Grab the next character in the string.
	        $char = substr($json, $i, 1);
	
	        // Are we inside a quoted string?
	        if ($char == '"' && $prevChar != '\\') {
	            $outOfQuotes = !$outOfQuotes;
	        
	        // If this character is the end of an element, 
	        // output a new line and indent the next line.
	        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
	            $result .= $newLine;
	            $pos --;
	            for ($j=0; $j<$pos; $j++) {
	                $result .= $indentStr;
	            }
	        }
	        
	        // Add the character to the result string.
	        $result .= $char;
	
	        // If the last character was the beginning of an element, 
	        // output a new line and indent the next line.
	        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
	            $result .= $newLine;
	            if ($char == '{' || $char == '[') {
	                $pos ++;
	            }
	            
	            for ($j = 0; $j < $pos; $j++) {
	                $result .= $indentStr;
	            }
	        }
	        
	        $prevChar = $char;
	    }
	
	    return $result;
	}
}
