<?php
echo <<<_END
    <html>
    <head>
    <title>Feng Zhang CS 174 Midterm 1</title>
    </head>
    <body>
        <form method='post' action='Midterm1.php' enctype = 'multipart/form-data'>
        Select a input file (.txt) to upload. The file must contain 400 integers which allow new line and spaces.<br>
        User could upload any type files or txt files with wrong format. But the program will automatically run test function 
        to print appropriate error message.
        <p><input type = 'file' name = 'filename' size = '10'></p>
        <p><input type='submit' value="Compute"></p>
_END;

$abc = file_tester($_FILES);
if($abc){
    $arr = set_array($abc);
    find_max_product($arr);
}

function file_tester($file){
    try{
        if ($file) {
            if ($file['filename']['error'] == UPLOAD_ERR_NO_FILE)
                throw new Exception("No file was uploaded.");

            if ($file['filename']['type'] != 'text/plain')
                throw new Exception("Please submit correct file type (.txt)");

            $name = $file['filename']['tmp_name'];
            $numbers = file_get_contents("$name");
            $numbers = preg_replace('/\s*/','',$numbers);

            if (strlen($numbers) != 400 or preg_match('/[^0-9]/',$numbers))
                throw new Exception("The file doesn't contain 400 numbers or contains 
                some characters in between, please submit integers-only file.");
            return $numbers;
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }
}

//convert the input string to two dimentional array
function set_array($numbers){
    $arr = array();
    $k = 0;
    for ($i = 0; $i < 20; $i++) {
        for ($j = 0; $j < 20; $j++) {
            $arr[$i][$j] = $numbers[$k++];
        }
    }
    return $arr;
}

//find the greatest product of four adjacent numbers in four directions
function find_max_product($arr){
    $max = 0;
    $result = 0;
    $num_1 = $num_2 =$num_3 = $num_4 = 0;
    for ( $i = 0; $i < 20; $i++) {
        for ( $j = 0; $j < 20; $j++) {
            // find the greatest product of four adjacent numbers in horizontal
            if (($j + 3) < 20) {
                $result = $arr[$i][$j] * $arr[$i][$j + 1] *
                    $arr[$i][$j + 2] * $arr[$i][$j + 3];
                if ($max < $result){
                    $max = $result;
                    $num_1 = $arr[$i][$j];
                    $num_2 = $arr[$i][$j + 1];
                    $num_3 = $arr[$i][$j + 2];
                    $num_4 = $arr[$i][$j + 3];
                }
            }
            // find the greatest product of four adjacent numbers in vertical
            if (($i + 3) < 20) {
                $result = $arr[$i][$j] * $arr[$i + 1][$j] *
                    $arr[$i + 2][$j] * $arr[$i + 3][$j];
                if ($max < $result){
                    $max = $result;
                    $num_1 = $arr[$i][$j];
                    $num_2 = $arr[$i + 1][$j];
                    $num_3 = $arr[$i + 2][$j];
                    $num_4 = $arr[$i + 3][$j];
                }
            }
            // find the greatest product of four adjacent numbers in diagonal
            if (($i + 3) < 20 and ($j + 3) < 20) {
                $result = $arr[$i][$j] * $arr[$i + 1][$j + 1] *
                    $arr[$i + 2][$j + 2] * $arr[$i + 3][$j + 3];
                if ($max < $result){
                    $max = $result;
                    $num_1 = $arr[$i][$j];
                    $num_2 = $arr[$i + 1][$j + 1];
                    $num_3 = $arr[$i + 2][$j + 2];
                    $num_4 = $arr[$i + 3][$j + 3];
                }
            }
            // find the greatest product of four adjacent numbers in anti-diagonal
            if (($i + 3) < 20 and ($j - 3) >= 0) {
                $result = $arr[$i][$j] * $arr[$i + 1][$j - 1] *
                    $arr[$i + 2][$j - 2] * $arr[$i + 3][$j - 3];
                if ($max < $result){
                    $max = $result;
                    $num_1 = $arr[$i][$j];
                    $num_2 = $arr[$i + 1][$j - 1];
                    $num_3 = $arr[$i + 2][$j - 2];
                    $num_4 = $arr[$i + 3][$j - 3];
                }
            }
        }
    }
    echo "The four numbers are :$num_1 $num_2 $num_3 $num_4"."<br>"."The maximum product is $max";
   // return $max;
}


echo "</body></html>";

?>



