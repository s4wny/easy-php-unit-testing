<?php

require_once '../easy-php-tester.class.php';

function square($x)
{
    return $x * $x;
}


test::assertEqual('square', 4, 16);
test::assertEqual('square', 4, "16");
test::assertEqual('square', 4, 8);

?>