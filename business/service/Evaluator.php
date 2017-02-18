<?php

class Evaluator{

    //construct
    public function __construct(){
        //$this->fileHandler = new fileHandler();

    }
	
	public function Evaluator(){
        self::__construct();
    }
	
	
	public function isString(string $input):bool{
		if(is_string($input) AND (strlen($input)>= 1) ){
			return true;
		}else{
			return false;
		}
	}
	
	public function isInt(int $input) :bool{
		if(is_numeric($input) AND (strlen((string)$input)>= 1) ){
			return true;
		}
		
		return false;
		
	}
	
	public function isFloat(float $input) :bool{
		if (is_float($input) ) {
			return true;
		}
		
		return false;
	}

    public function cleanString(string $input):string{
        $string = filter_var($input, FILTER_SANITIZE_STRING);

    return $string;
    }

    function stripper(string $input):string {
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
    function GetSuchAlgorithmen(string $input):string {
        switch ($input) {
            case "LowercaseLettersAndNumbers":
                $output = "a-z0-9";
                break;
            case "UppercaseLettersAndNumbers":
                $output = "A-Z0-9";
                break;
            case "ForHexDec":
                $output = "A-F0-9";
                break;
            case "Binary":
                $output = "0-1";
                break;
            case "LettersNumbersUnderscore":
                $output = "A-Za-z0-9_";
                break;
            case "Numbers":
                $output = "0-9";
                break;
            case "ForLettersNumber":
                $output = "A-Za-z0-9";
                break;
            case "Letter":
                $output = "A-Za-z";
                break;
            default:
                $output = "0-1";
        }
        return $output;
    }

    //check for input
    function checkFor(string $input,string $SuchAlgorithmen="GermanLetter",int $minlength=0,int $maxlength=60):bool {
        if(empty($input)){
            echo "no input";
            die();
        }

        $buildTheRegex = "";

        if($SuchAlgorithmen == "GermanLetter"){
            $buildTheRegex = '/^((?i)[0-9a-zäöüÄÖÜ]){'.$minlength.','.$maxlength.'}$/i';
        }else{
            $Regex = $this->GetSuchAlgorithmen($SuchAlgorithmen);
            $buildTheRegex = '/^['.$Regex.']{'.$minlength.','.$maxlength.'}$/';
        }

		if(preg_match($buildTheRegex, $input) ){
            return true;
        }

		return false;
	}

    //return only strict input
    function returnOnly(string $input,string $SuchAlgorithmen="GermanLetter",int $minlength=0,int $maxlength=60):string{
        if(empty($input)){
            echo "no input";
            die();
        }

        $buildTheRegex = "";

        if($SuchAlgorithmen == "GermanLetter") {
            $buildTheRegex = '/^((?i)[0-9a-zäöüÄÖÜ]){'.$minlength.','.$maxlength.'}$/i';
        }else{
            $Regex = $this->GetSuchAlgorithmen($SuchAlgorithmen);
            $buildTheRegex = '/^['.$Regex.']{'.$minlength.','.$maxlength.'}$/';
        }

		$output = preg_replace($buildTheRegex, "",$input);

		return $output;
	}

    //lengths
    function MinInputLimit(string $input,int $minchar):bool {
        if (strlen($input) < $minchar ){
            return false;
        }

        return true;
    }

    function MaxInputLimit(string $input,int $maxchar):string {
        if (strlen($input) > $maxchar ){
            return $this->cutToMaxInputLimit($input, $maxchar);//force to the right length
        }

        return $input;
    }

    function MaxInputLimitCheck(string $input,int $maxchar):bool {
        if (strlen($input) > $maxchar ){
            return false;
        }

        return true;
    }

    function cutToMaxInputLimit(string $input,int $maxchar):string {
        $output = substr($input,0,$maxchar);

        return $output;
    }

    //date
    function date(string $input):bool {
        if ($this->stripper($input) == false){
            return false;
        }
        $tmp = explode(".", $input);//parse date inpute 28.02.2015 in to $tmp[1]=28 $tmp[0]=02 $tmp[2]=2015
        if (checkdate( $tmp[1], $tmp[0], $tmp[2]) == false){
            return false;
        }

        return true;
    }

    function dateUS(string $input):bool {
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
    function time(string $input):bool {
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

    function timeMil(string $input):bool {
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

    function URL(string $input):bool {
        if ($this->stripper($input) !== false) {
            if (filter_var($input, FILTER_VALIDATE_URL == false)){
                return false;
            }

            return true;
        }

    }

    function Email(string $input):bool {
        if ($this->stripper($input) !== false) {
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)){
                return false;
            }
            return true;
        }

    }

    function EmailConfirm(string $input1 ,string $input2):bool{
        if (strcmp($input1, $input2) !== 0) {
            return false;
        }
        return true;
    }
	//money
	function money(string $input):bool {
		$money = preg_match("/^[0-9]+(?:\\,[0-9]{0,2})?$/", $input);

		if ( $money == false ) {
			return false;
		}

		return true;
	}

	function moneyUS(string $input):bool {
		$money = preg_match("/^[0-9]+(?:\\.[0-9]{0,2})?$/", $input);

		if ( $money == false ) {
			return false;
		}

		return true;
	}
	
	//japan only have Yen
	function moneyJap(string $input):bool {
		$money = $this->returnOnly("Numbers",$input);
		if ( $money == false ) {
			return false;
		}

		return true;
	}
	 

}

?>