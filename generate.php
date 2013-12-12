<?php

ini_set('memory_limit', '2G');

require_once __DIR__."/Bloomer.class.php";

$passwordFiles = glob(__DIR__."/lists/*.txt");
$bloomer = new Bloomer(array(
	'unique' => 0
));

array_shift($argv);

foreach ($argv as $arg) {
	$bit = $bloomer->addHash(md5($arg));
	echo "$arg = $bit\n";
	
	$bit = $bloomer->addHash(sha1($arg));
	echo "$arg = $bit\n";
	
	$bit = $bloomer->addHash(hash('sha256', $arg));
	echo "$arg = $bit\n";
}

foreach ($passwordFiles as $file) {
	echo "Adding passwords from $file\n";
	$bloomer->addPasswordFile($file);
}

$elementCount = number_format($bloomer->getElementCount());
$errorRate = $bloomer->getErrorRate();

$hex = $bloomer->getHex();
file_put_contents(__DIR__."/bloom.hex", $hex);

echo "-------------------------\n";
echo "Unique Passwords: $elementCount\n";
echo "Estimated Error:  $errorRate\n";
echo "Written to file bloom.hex\n";
echo "\n";

