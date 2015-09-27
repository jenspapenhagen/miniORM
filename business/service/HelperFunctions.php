<?php

class HelperFunctions {
    
    public function ArrayToCommaSeperatedString($array){
        $valuesAsString = "";
        
        foreach ($array as $item) {
            if (empty($valuesAsString)) {
                $valuesAsString .= $item;
            } else {
                $valuesAsString .= ",".$item;
            }
        }
        return $valuesAsString;
    }

    function makeLinkClickable($input) {
        $output = preg_replace('*(f|ht)tps?://[A-Za-z0-9\./?=\+&%]+*', '<a href="$1" target="_blank">$1</a>', $input);
        //force HTTPS
        $output = str_replace( 'http://', 'https://', $output);
        	
        return $output;
    }
    
    function HumanReadableFileSize($size,$unit="") {
        if ( (!$unit and $size >= 1<<30) or $unit == "GB"){
            $gb = number_format($size/(1<<30),2)."GB";
            return $gb;
        }
        if ( (!$unit and $size >= 1<<20) or $unit == "MB"){
            $mb = number_format($size/(1<<20),2)."MB";
            return $mb;
        }
        	
        if ( (!$unit and $size >= 1<<10) or $unit == "KB"){
            $kb = number_format($size/(1<<10),2)."KB";
            return $kb;
        }
        	
        return number_format($size)." Bytes";
    }
    
    function ConvertBBcodesToHTML($text) {
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
    
    function CountUpperWordsInString($str){
        $words = explode(" ", $str);
        $i = 0;
    
        foreach ($words as $word){
            if (strtoupper($word) === $word){
                $i++;
            }
        }
        return $i;
    }
    
    function CleanXMLString($value){
        if (empty($value)) {
            return $ret = "";
        }
    
        $bodytext = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $value);
        $bodytext = iconv("UTF-8","UTF-8//IGNORE",$bodytext); 
        // This will Convert the string to the requested character encoding and strip all of the none-utf-8 characters 
        // (http://uk3.php.net/manual/en/function.iconv.php
        $bodytext = htmlspecialchars($bodytext, ENT_QUOTES, 'UTF-8');
        $ret = htmlentities($bodytext,ENT_COMPAT); // Standard Convert HTML entities
    
        return $ret;
    }
    
}