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



// The result in the command line should be:

# 1 testes of 3 testes failed!
# 
# ---- Log ----
# 
# square(4) !== 8. On line: 13
# 
# ------------------------------    
?>

