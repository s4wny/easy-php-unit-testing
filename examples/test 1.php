<?php

//Include the file
require_once '../easy-php-tester.class.php';

//Include your functions
function square($x)
{
    return $x * $x;
}


//Test them!
test::is('square', 4)->equalTo(16);         // True
test::is('square', 4)->equalTo("16");       // True 
test::is('square', 4)->strictEqualTo("16"); // False
test::is('square', 4)->equalTo(8);          // False
test::is('square', 4)->lessThen(29);        // True
test::is('square', 4)->biggerThen(29);      // False



// The result in the command line should be:

# 3 testes of 6 testes failed!
# 
# ---- Log ----
# 
# square(4) !== "16". On line: 16
# square(4) != 8. On line: 17
# square(4) <= 29. On line: 19
# 
# ------------------------------
?>

