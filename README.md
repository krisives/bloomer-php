 
 *Note: If you're looking for the NodeJS module go to https://github.com/krisives/bloomer*
 
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

The goal of the code written is to be simple and easy to understand. I tried to
provide comments that explain things like key distribution, uniformity, etc. I also
adapt the constants used in the Wikipedia article, making it easy to see how the
parameters *k*, *m*, and *n* affect the data structure.


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

## How do I add more password lists?

Drop a `.txt` file into the `lists/` directory and run `php generate.php` again. More work
is needed to adjust the parameters and how large the bloom filter is, but this should be
fixed soon.


## I run out of memory / Why does Bloomer take up so much memory?

PHP internally is really bad at memory management. Giving it just 153MB of string data can
cause it to allocate gigabytes of memory. Bloomer keeps a unique index of all the passwords
added while generating the filter in an effort to maximize the accuracy of the error rate
estimate.

You can avoid this problem by disabling the unique index of Bloomer:

    $bloomer = new Bloomer(array(
        'unique' => 0
    ));


Now Bloomer will not track duplicates, and the error rate display is inaccurate with respect
to how many duplicate passwords there are (since adding duplicates to the filter doesn't actually
affect it's error)

## How can I help?

Press the fork button!


