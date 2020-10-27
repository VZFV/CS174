<?php
// Assignment 1 question 1
// find prime number up to input number
function primeNumber($inputNumber)
{
    if ($inputNumber <= 1) {
        return null;
    }
    $primeNumbers = [];
    for ($i = 2; $i < $inputNumber; $i++) {
        if (isPrimeNumber($i) == 1)
            array_push($primeNumbers, $i);
    }
    return $primeNumbers;
}

// print array if input number is larger than 1 or print null
function output($num_array, $inputNumber)
{
    if ($num_array == null) {
        echo "[ERROR] Cannot input any number is equal or less than 1" . "<br>";
    } else {
        echo "Prime numbers up to $inputNumber is: ";
        foreach ($num_array as $value) {
            echo $value . " ";
        }
        echo "<br>";
    }
}

// test prime number
function isPrimeNumber($number)
{
    for ($i = 2; $i <= $number / 2; $i++) {
        if ($number % $i == 0)
            return 0;
    }
    return 1;
}

$input1 = 0;
$input2 = 10;
$input3 = 100;

output(primeNumber($input1), $input1);
output(primeNumber($input2), $input2);
output(primeNumber($input3), $input3);


// Assignment 1 question 2
$tester_1 = array(2, 3, 5, 7);
$tester_2 = array(2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41,
    43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97);

function tester($input_arr, $tester_arr, $input)
{
    echo "output from prime_function($input) equal to given array?" . "<br>";
    echo $input_arr == $tester_arr ? "Yes, test pass" . "<br>" : "No, test fail" . "<br>";

}

tester(primeNumber($input2), $tester_1, $input2);
tester(primeNumber($input3), $tester_2, $input3);
