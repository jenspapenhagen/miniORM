<?php
class CSRF_Protect {

    private $acceptGet = false;
	private $timeout = 300; // Default timeout is 300 seconds (5 minutes)
	
	public function __construct(int $timeout=300){
        $this->timeout = $timeout;
            if (session_id() === ''){
                session_start();
            }
	}
	
	public function getRandomString() :string {
	    $len = 32;
	    $rString = '';
	    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
	    $charsTotal  = strlen($chars);
	    for ($i = 0; $i < $len; $i++) {
	        $rInt = (integer) mt_rand(0, $charsTotal);
	        $rString .= substr($chars, $rInt, 1);
	    }
	    
	    return $rString;
	}
	
	protected function calculateHash():string {
	    $options = ['cost' => 12];
	    //old sha1(implode('', $_SESSION['csrf']));
	    $output =  password_hash(implode('', $_SESSION['csrf']), PASSWORD_BCRYPT, $options);
	    
	    return $output;
	}
	
	public function setToken():string {
	    // Create or overwrite the csrf entry in the seesion
	    $_SESSION['csrf'] = array();
	    $_SESSION['csrf']['time'] = time();
	    $_SESSION['csrf']['salt'] = $this->getRandomString();
	    $_SESSION['csrf']['sessid'] = session_id();
	    $_SESSION['csrf']['ip'] = $_SERVER['REMOTE_ADDR'];
	    
	    $hash = $this->calculateHash();
	    
	    return base64_encode($hash);
	}
	
	public function generateHiddenField():string {
	    $token = $this->setToken();
	    $output = "<input type=\"hidden\" name=\"csrf\" value=\"$token\" />";
	    
	    return $output;
	}
	
	protected function checkTimeout(null $timeout=NULL):string {
	    if (!$timeout) {
	        $timeout = $this->timeout;
	    }
	    
	return ($_SERVER['REQUEST_TIME'] - $_SESSION['csrf']['time']) < $timeout;
    }
	
	public function getToken(null $timeout=NULL):string {
	    if (isset($_SESSION['csrf'])) {
	        if (!$this->checkTimeout($timeout)) {
	            return false;
	        }
	
	        if (session_id()) {
	            $isCsrfPost = isset($_POST['csrf']);
	            if ($isCsrfPost) {
	                $tokenHash = base64_decode($_REQUEST['csrf']);
	                $generatedHash = $this->calculateHash();
	                if ($tokenHash and $generatedHash) {
	                    return $tokenHash == $generatedHash;
	                }
	            }
	        }
	    }
	    return false;
	}
	
}
?>