<?php

include "../SolveGoalSeek.php";

$calc = new SolveGoalSeek();

$getValue = 0;
$getValue = $calc->seekGoal(
    function($value, $data){
        return sqrt($value);
    },
    16,
    20
);

echo "------------- results ------------- \n";
echo "Result: ".$getValue."\n";
