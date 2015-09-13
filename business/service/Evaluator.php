<?php

class Evaluator{


	public function Evaluator(){

	}



	
	function binaercheck($input) {
		
		$maxchar = 1;
		if(!$this->checkForNumbers($input) || $this->MaxInputLimitCheck($input, $maxchar) == false || !is_null($input) || $input > 1 || $input < 0) {
			return false;
		}
	
		return true;
	}
	
	function emptycheck($input) {
		if (empty($input)) {
			return false;
		}
		return true;
	}
	
	
	

	function stripper($input) {
		foreach (array(' ', '&nbsp;', '\n', '\t', '\r') as $strip){
			$output = str_replace($strip, '', (string) $input);
		}
		if($input === ''){
			return false;
		}else{
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
		if( preg_match("/[^a-z]+/", $input) ) {
			return true;
		}return false;
	}

	function checkForUppercaseLetters($input) {
		if( preg_match("/[^A-Z]+/", $input) ) {
			return true;
		}return false;
	}

	function checkForNumbers($input) {
		if( is_numeric($input)) {
			return true;
		}return false;
	}

	function checkForLetter($input) {
		if( preg_match('/^[a-zA-ZÃ¤Ã¶Ã¼Ã„Ã–Ãœ ]+$/i', $input)) {
			return true;
		}
		return false;
	}

	function checkForLowercaseLettersAndNumbers($input) {
		if( preg_match("/[^a-z0-9]+/", $input) ) {
			return true;
		}
		return false;
	}

	function checkForUppercaseLettersAndNumbers($input) {
		if( preg_match("/[^A-Z0-9]+/", $input) ) {
			return true;
		}
		return false;
	}

	function checkForHexDec($input) {
		if( preg_match("/[^A-F0-9]+/", $input) ) {
			return true;
		}
		return false;
	}

	function checkForBinary($input) {
		if( preg_match("/[^0-1]+/", $input) ) {
			return true;
		}
		return false;
	}

	function checkForLettersNumbersUnderscore($input) {
		if( preg_match("/[^A-Za-z0-9_]+/", $input) ) {
			return true;
		}
		return false;
	}

	function checkForLettersNumbers($input) {
		if( preg_match("/[^A-Za-z0-9]+/", $input) ) {
			return true;
		}
		return false;
	}

	//lengths
	function MinInputLimit($input,$minchar) {
		if(strlen($input) < $minchar ){
			return false;
		}

		return true;
	}

	function MaxInputLimit($input, $maxchar) {
		if(strlen($input) > $maxchar ){
			return $this->cutToMaxInputLimit($input, $maxchar);//force to the right length
		}

		return $input;
	}
	
	function MaxInputLimitCheck($input, $maxchar) {
		if(strlen($input) > $maxchar ){
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
		if(checkdate( $tmp[1], $tmp[0], $tmp[2]) == false){
			return false;
		}

		return true;
	}

	function dateUS($input) {
		if ($this->stripper($input) == false){
			return false;
		}
		$tmp = explode("-", $input); //parse date inpute 02-28-2015 in to $tmp[1]=02 $tmp[0]=28 $tmp[2]=2015
		if(checkdate( $tmp[0], $tmp[1], $tmp[2]) == false){
			return false;
		}

		return true;
		 
	}


	//time
	function time($input) {
		$minchar = "4"; //0:01
		$maxchar = "5"; //21:01

		if($this->MinInputLimit($input, $minchar) == false and $this->MaxInputLimit($input, $maxchar) == false) {
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

		if($this->MinInputLimit($input, $minchar) == false and $this->MaxInputLimit($input, $maxchar) == false ) {
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

	 
	function CleanXMLString($value){
		if (empty($value)) {
			return $ret = "";
		}

		$bodytext = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $value);
		$bodytext = iconv("UTF-8","UTF-8//IGNORE",$bodytext); // This will Convert the string to the requested character encoding and strip all of the none-utf-8 characters (http://uk3.php.net/manual/en/function.iconv.php
		$bodytext = htmlspecialchars($bodytext, ENT_QUOTES, 'UTF-8');
		$ret = htmlentities($bodytext,ENT_COMPAT); // Standard Convert HTML entities

		return $ret;
	}

	//make links clickable, open new tap
	function makeItClick($input) {
		$output = preg_replace('!(((f|ht)tp(s)?://)[-a-zA-ZÃ�Â°-Ã‘ï¿½Ã�ï¿½-Ã�Â¯()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $input);
		//force HTTPS
		$output = str_replace( 'http://', 'https://', $output);
		 
		return $output;
	}

	//human readable file size
	function humanFileSize($size,$unit="") {
		if( (!$unit and $size >= 1<<30) or $unit == "GB"){
			$gb = number_format($size/(1<<30),2)."GB";
			return $gb;
		}
		if( (!$unit and $size >= 1<<20) or $unit == "MB"){
			$mb = number_format($size/(1<<20),2)."MB";
			return $mb;
		}
		 
		if( (!$unit and $size >= 1<<10) or $unit == "KB"){
			$kb = number_format($size/(1<<10),2)."KB";
			return $kb;
		}
		 
		return number_format($size)." Bytes";
	}

	function showBBcodes($text) {
		// BBcode array
		$find = array(
				'~\[b\](.*?)\[/b\]~s',
				'~\[i\](.*?)\[/i\]~s',
				'~\[u\](.*?)\[/u\]~s',
				'~\[s\](.*?)\[/s\]~s',
				//font size
				'~\[size=1\](.*?)\[/size\]~s',
				'~\[size=2\](.*?)\[/size\]~s',
				'~\[size=3\](.*?)\[/size\]~s',
				'~\[size=4\](.*?)\[/size\]~s',
				'~\[size=5\](.*?)\[/size\]~s',
				'~\[size=6\](.*?)\[/size\]~s',
				'~\[size=7\](.*?)\[/size\]~s',
				//colours
				'~\[green\](.*?)\[/green\]~s',
				'~\[red\](.*?)\[/red\]~s',
				'~\[blue\](.*?)\[/blue\]~s',
				'~\[yellow\](.*?)\[/yellow\]~s',
				//special BB-Code
				'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
				'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
				'~\[id\](.*?)\[/id\]~s',
				'~\[youtube\](.*?)\[/youtube\]~s'
		);

		// HTML tags to replace BBcode
		$replace = array(
				'<b>$1</b>',
				'<i>$1</i>',
				'<span style="text-decoration:underline;">$1</span>',
				'<s>$1</s>',
				'<span style="font-size:7.5pt;">$1</span>',
				'<span style="font-size:10pt;">$1</span>',
				'<span style="font-size:12pt;">$1</span>',
				'<span style="font-size:14pt;">$1</span>',
				'<span style="font-size:16pt;">$1</span>',
				'<span style="font-size:18pt;">$1</span>',
				'<span style="font-size:20pt;">$1</span>',
				'<span style="color:green;">$1</span>',
				'<span style="color:red;">$1</span>',
				'<span style="color:blue;">$1</span>',
				'<span style="color:yellow;">$1</span>',
				'<a href="$1" target="_blank">$1</a>',
				'<img src="$1" alt="" />',
				'<a href="#$1">#$1</a>',
				'<iframe width="420" height="315" src="http://www.youtube.com/embed/$1" frameborder="0"><a href="http://www.youtube.com/v/$1" target="_blank">the Iframe do not load therefor try this link</a></iframe>'
						);

		// Replacing the BBcodes with corresponding HTML tags
		return preg_replace($find,$replace,$text);
	}

	//count uppercase Word in String
	function upper_count($str){
		$words = explode(" ", $str);
		$i = 0;

		foreach ($words as $word){
			if (strtoupper($word) === $word){
				$i++;
			}
		}
		return $i;
	}


}

?>