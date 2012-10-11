
Easy PHP unit testing
=====================

This script is right now designed to run from the command line but I will maybe add webbrowser support in the future.

Installing
==========

 1. Download the file (easy-php-tester.class.php)
 2. Include it in your test file (`require_once 'easy-php-tester.class.php';`)
 3. Write some testes!
    In example:

    ```php
    <?php

    //Include the file
    require_once '../easy-php-tester.class.php';
    
    //Include your functions
    function square($x)
    {
        return $x * $x;
    }
    
    
    //Test them!
    test::assertEqual('square', 4, 16);     //True
    test::assertEqual('square', 4, "16");   //True
    test::assertEqual('square', 4, 8);      //False
    
    ?>

    The result in the command line should be:

    > 1 testes of 3 testes failed!
    > 
    > ---- Log ----
    > 
    > square(4) !== 8. On line: 13
    > 
    > ------------------------------    
    ```


Examples
========
See `examples/`


Functions
=========
`assertEqual`


Todo
====
 - `assertEqualStrict`
 - `assertNotEqual`
 - `assertNotEqualStrict`
 - Settings, like how much output should be printed.
