 
     ____ ____ ____ ____ ____ ____ ____ 
    ||B |||l |||o |||o |||m |||e |||r ||
    ||__|||__|||__|||__|||__|||__|||__||
    |/__\|/__\|/__\|/__\|/__\|/__\|/__\|


## What is Bloomer?

Bloomer is a utility library to help website authors guide their
users into picking better passwords. It generates a [Bloom Filter](http://en.wikipedia.org/wiki/Bloom_filter) of comprimised passwords,
which are obtained from public security lists on the Internet.

The password lists used by Bloomer do not contain identifiable user information, such as
an e-mail address - just the passwords. The final resulting bloom filter data
does not contain any plaintext passwords and is the result of SHA1, MD5, and
SHA256 crytpographic hashes.


## How do I use Bloomer?

* Download the source code from this GitHub - You will need PHP to
  generate a bloom filter, otherwise skip the next step and just use
  the "bloom.hex"

* Generate the bloom filter data by running:

        php generate.php

* Grab "bloom.hex" and "bloomer.js"

* After including "bloomer.js" create an instance:

        var bloomer = new Bloomer();

* Feed the data into the Javascript object

        bloomer.setHex($('#bloomhex').val()); // Data from a DOM node

* Check if a string is in the bloom filter

        if (bloomer.check("test")) {

