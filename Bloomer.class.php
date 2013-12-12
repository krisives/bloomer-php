<?php


/** Adapted from http://en.wikipedia.org/wiki/Bloom_filter
***
*** Code attempts to be simple and easy to understand rather than clever
*** or heavily optimized.
*** 
*** @author Kristopher Ives
**/
class Bloomer {
	// On Wikipedia this is "k" hashing functions
	// Since we need this implementation in Javascript we have 3 total
	// hashing functions we can use: MD5, SHA1, SHA256
	private $hashFunctionCount = 3;
	
	// Size of the bit array that stores the result of each hashing function
	// On Wikipedia this is "m" bits
	private $bitCount = 16777216;// (2^21);
	
	// Array of bits (may get compacted later but in PHP it's just an array of bools)
	private $bits;
	
	// Remember how many things are added to the bloom filter so we can calculate the error
	// On Wikipedia this is "n"
	private $elementCount;
	
	// An array mapping known passwords to avoid duplicates (since multiple password
	// lists may contain the same entry) and to avoid $elementCount being inaccurate,
	// which would make the estimated error rate innaccurate
	// NOTE: May want to provide a way to disable this for memory reasons
	private $uniqueIndex;
	
	private $ensureUnique = false;
	
	public function __construct($options=array()) {
		$this->bits = array();
		
		$this->ensureUnique = @intval($options['unique']);
		
		for ($i=0; $i < $this->bitCount; $i++) {
			$this->bits[$i] = 0;
		}
		
		$this->uniqueIndex = array();
	}
	
	public function getElementCount() {
		return $this->elementCount;
	}
	
	public function getErrorRate() {
		$m = $this->bitCount;
		$n = $this->elementCount;
		$k = $this->hashFunctionCount;
		
		if ($n <= 0) {
			// Cannot have any errors if nothing is in the set
			return 0.0;
		}
		
		return pow(1 - pow(1.0 - (1.0 / $m), $n * $k), $k);
	}
	
	public function addPasswordFile($file) {
		$fp = fopen($file, 'r');
		
		if (!$fp) {
			throw new Exception("Unable to open file");
		}
		
		while (false !== ($line = fgets($fp)) ) {
			$line = rtrim($line, "\r\n");
			$this->addPassword($line);
		}
		
		fclose($fp);
	}
	
	public function addPassword($password) {
		if ($this->ensureUnique && array_key_exists($password, $this->uniqueIndex)) {
			return;
		}
		
		$this->addHash(sha1($password));
		$this->addHash(md5($password));
		$this->addHash(hash('sha256', $password));
		
		if ($this->ensureUnique) {
			$this->uniqueIndex[$password] = 1;
		}
		
		$this->elementCount++;
	}
	
	public function addHash($hash) {
		$len = strlen($hash);
		$value = 0;
		
		// Break the hash into pieces of 8 characters (32-bits)
		for ($i=0; $i < $len; $i += 8) {
			$piece = hexdec(substr($hash, $i, 8)) & 0xFFFFFFF;
			
			// XOR preserves distribution and the resulting 32-bit integer
			// should be sufficient for indexing the bitmap up to 4GB
			$value = ($value ^ $piece) & 0xFFFFFFF;
		}
		
		// Limit the range of the value so we can use it as an index
		// (Should preserve unifirm distribution as long as bitCount is a power of two)
		$value = abs($value % $this->bitCount);
		
		$this->bits[$value] = 1;
		return $value;
	}
	
	public function getBits() {
		return $this->bits;
	}
	
	public function getHex() {
		$hex = '';
		
		for ($i=0; $i < $this->bitCount; $i += 32) {
			$value = 0;
			
			for ($j=0; $j < 32; $j++) {
				$bit = $this->bits[$i + $j];
				
				if ($bit) {
					$value = $value | (1 << $j);
				}
			}
			
			$hex .= str_pad(dechex($value), 8, '0', STR_PAD_LEFT);
		}
		
		return $hex;
	}
}

