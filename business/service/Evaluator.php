<?php

class Evaluator{


	public function Evaluator(){

	}


	function binaercheck($input) {
		
		$maxchar = 1;
		if (!$this->checkForNumbers($input) || $this->MaxInputLimitCheck($input, $maxchar) == false || !is_null($input) || $input > 1 || $input < 0) {
			return false;
		}
	
		return true;
	}
	
	function emptycheck($input) {
		if (empty($input)) {
			return true;
		}
		return false;
	}
	
	
	

	function stripper($input) {
	    $output = '';
		foreach (array(' ', '&nbsp;', '\n', '\t', '\r') as $strip){
			$output = str_replace($strip, '', (string) $input);
		}
		if ($input === ''){
			return false;
		} else { 
			return $output;
		}

	}
	 

	function returnOnlyLowercaseLetters($input) {
		$output = preg_replace("/[^a-z]+/", "", $input);
		 
		return $output;
	}

	function returnOnlyUppercaseLetters($input) {
		$output = preg_replace("/[^A-Z]+/", "", $input);

		return $output;
	}

	function returnOnlyNumbers($input) {
		$output = preg_replace("/[^0-9]+/", "", $input);

		return $output;
	}

	function returnOnlyLetter($input) {
		$output = preg_replace("/[^A-Za-z]+/", "", $input);

		return $output;
	}

	function returnOnlyLowercaseLettersAndNumbers($input) {
		$output = preg_replace("/[^a-z0-9]+/", "", $input);

		return $output;
	}

	function returnOnlyUppercaseLettersAndNumbers($input) {
		$output = preg_replace("/[^A-Z0-9]+/", "", $input);

		return $output;
	}

	function returnOnlyHexDec($input) {
		$output = preg_replace("/[^A-F0-9]+/", "", $input);

		return $output;
	}

	function returnOnlyBinary($input) {
		$output = preg_replace("/[^0-1]+/", "", $input);

		return $output;
	}

	function returnOnlyLettersNumbersUnderscore($input) {
		$output = preg_replace("/[^A-Za-z0-9_]+/", "_", $input);

		return $output;
	}

	function returnOnlyLettersNumbers($input) {
		$output = preg_replace("/[^A-Za-z0-9]+/", "", $input);

		return $output;
	}

	//check for input
	function checkForLowercaseLetters($input) {
		if ( preg_match("/[^a-z]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForUppercaseLetters($input) {
		if ( preg_match("/[^A-Z]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForNumbers($input) {
		if ( is_numeric($input)) {
			return true;
		}
		
		return false;
	}

	function checkForLetter($input) {
		if ( preg_match('/^[a-zA-ZÃ¤Ã¶Ã¼Ã„Ã–Ãœ ]+$/i', $input)) {
			return true;
		}
		
		return false;
	}

	function checkForLowercaseLettersAndNumbers($input) {
		if ( preg_match("/[^a-z0-9]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForUppercaseLettersAndNumbers($input) {
		if ( preg_match("/[^A-Z0-9]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForHexDec($input) {
		if ( preg_match("/[^A-F0-9]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForBinary($input) {
		if ( preg_match("/[^0-1]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForLettersNumbersUnderscore($input) {
		if ( preg_match("/[^A-Za-z0-9_]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	function checkForLettersNumbers($input) {
		if ( preg_match("/[^A-Za-z0-9]+/", $input) ) {
			return true;
		}
		
		return false;
	}

	//lengths
	function MinInputLimit($input,$minchar) {
		if (strlen($input) < $minchar ){
			return false;
		}

		return true;
	}

	function MaxInputLimit($input, $maxchar) {
		if (strlen($input) > $maxchar ){
			return $this->cutToMaxInputLimit($input, $maxchar);//force to the right length
		}

		return $input;
	}
	
	function MaxInputLimitCheck($input, $maxchar) {
		if (strlen($input) > $maxchar ){
			return false;
		}
	
		return true;
	}

	function cutToMaxInputLimit($input,$maxchar) {
		$output = substr($input,0,$maxchar);

		return $output;
	}

	//date
	function date($input) {
		if ($this->stripper($input) == false){
			return false;
		}
		$tmp = explode(".", $input);//parse date inpute 28.02.2015 in to $tmp[1]=28 $tmp[0]=02 $tmp[2]=2015
		if (checkdate( $tmp[1], $tmp[0], $tmp[2]) == false){
			return false;
		}

		return true;
	}

	function dateUS($input) {
		if ($this->stripper($input) == false){
			return false;
		}
		$tmp = explode("-", $input); //parse date inpute 02-28-2015 in to $tmp[1]=02 $tmp[0]=28 $tmp[2]=2015
		if (checkdate( $tmp[0], $tmp[1], $tmp[2]) == false){
			return false;
		}

		return true;
		 
	}


	//time
	function time($input) {
		$minchar = "4"; //0:01
		$maxchar = "5"; //21:01

		if ($this->MinInputLimit($input, $minchar) == false and $this->MaxInputLimit($input, $maxchar) == false) {
			return false;
		}

		$time = preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", $input);
		if ( $time == false ) {
			return false;
		}

		return true;

	}

	function timeMil($input) {
		$minchar = "1"; //0
		$maxchar = "4"; //2100

		if ($this->MinInputLimit($input, $minchar) == false and $this->MaxInputLimit($input, $maxchar) == false ) {
			return false;
		}

		$time = preg_match("/^((([01][0-9])|([2][0-3])):([0-5][0-9]))|(24:00)$/", $input);
		if ( $time == false ) {
			return false;
		}

		return true;

	}

	//money
	function money($input) {
		$money = preg_match("/^[0-9]+(?:\\,[0-9]{0,2})?$/", $input);

		if ( $money == false ) {
			return false;
		}

		return true;
	}

	function moneyUS($input) {
		$money = preg_match("/^[0-9]+(?:\\.[0-9]{0,2})?$/", $input);

		if ( $money == false ) {
			return false;
		}

		return true;
	}
	//japan only have Yen
	function moneyJap($input) {
		$money = $this->returnOnlyNumbers($input);
		if ( $money == false ) {
			return false;
		}

		return true;
	}
	 
	function URL($input) {
		if ($this->stripper($input) !== false) {
			if (filter_var($input, FILTER_VALIDATE_URL == false)){
				return false;
			}

			return true;
		}

	}

	function Email($input) {
		if ($this->stripper($input) !== false) {
			if (!filter_var($input, FILTER_VALIDATE_EMAIL)){
				return false;
			}
			return true;
		}

	}

	function EmailConfirm($input1 ,$input2){
		if (strcmp($input1, $input2) !== 0) {
			return false;
		}
		return true;
	}

}

?>