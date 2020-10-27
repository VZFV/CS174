<?php
const roman_numeral = array(
    'I'=>1,
    'V'=>5,
    'X'=>10,
    'L'=>50,
    'C'=>100,
    'D'=>500,
    'M'=>1000
);

function inputCheck($str){
    //check if the input is empty
    if(empty($str)){
        echo "[ERROR] Cannot be empty input"."<br>";
        return false;
    }

    // check if it contains invalid character
    if( preg_match("/[^IVXLCDM]/",$str)){
        echo "[ERROR] invalid characters"."<br>";
        return false;
    }

    // check if V L D appear more than one time
    if(preg_match("/V[^V]*V|L[^L]*L|D[^D]*D/",$str)){
        echo "[ERROR] V, L, or D appear more than one time"."<br>";
        return false;
    }

    // check if I X C M appear more than three times
    if( preg_match("/I([^I]*I){3,}|X([^X]*X){3,}|C([^C]*C){3,}|M([^M]*M){3,}/",$str)){
        echo "[ERROR] I, X, C, or M appear more than three times."."<br>";
        return false;
    }

    // check if V L D are subtracted
    for($i = 0; $i < strlen($str)-1; $i++){
        if(roman_numeral[$str[$i]] < roman_numeral[$str[$i+1]]
            and ($str[$i] == "V" or $str[$i] == "L" or $str[$i] == "D")){
            echo "[ERROR] V, L and D are never subtracted."."<br>";
            return false;
        }
    }

    // check if there are 2 consecutive subtraction
    for($i = 0; $i < strlen($str)-2; $i++){
        if(roman_numeral[$str[$i]] < roman_numeral[$str[$i+2]]
            and roman_numeral[$str[$i+1]] < roman_numeral[$str[$i+2]]){
            echo "[ERROR] 2 consecutive subtractions occur."."<br>";
            return false;
        }
    }
    return true;
}



function romanConvert($str){
    if(strlen($str) == 1)
        return roman_numeral[$str];

    $result = 0;
    for($i = 0; $i < strlen($str)-1; $i++){
        $result += roman_numeral[$str[$i]] < roman_numeral[$str[$i+1]]?
            -roman_numeral[$str[$i]]:roman_numeral[$str[$i]] ;
    }
    $result += roman_numeral[substr($str,-1)];
    return $result;
}

function test($str){
    if(inputCheck($str))
        echo "Convert $str to numeral: ".romanConvert($str)."<br>";
}

test("");
test("WIDJF");
test("LLDD");
test("LIVX");
test("IXXXX");
test("VI");
test("IV");
test("MCMXC");
test("IX");

