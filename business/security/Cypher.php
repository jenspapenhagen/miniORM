<?php
class Cypher {
    private $securekey; 
    private $iv;

    function __construct($textkey) {
        $this->securekey = hash('sha256',$textkey,TRUE);
        $this->size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $this->iv = mcrypt_create_iv($this->size, MCRYPT_RAND);
    }
    
    function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_CBC, $this->iv));
    }
    function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_CBC, $this->iv));
    }
}

?>
