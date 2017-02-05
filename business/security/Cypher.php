<?php
class Cypher {
    private $securekey; 
    private $iv;

    public function __construct(string $textkey) {
        $this->securekey = hash('sha256',$textkey,TRUE);
        $this->size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $this->iv = mcrypt_create_iv($this->size, MCRYPT_RAND);
    }
    
    public function encrypt(string $input):string {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_CBC, $this->iv));
    }
    public function decrypt(string $input):string {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_CBC, $this->iv));
    }
}

?>
