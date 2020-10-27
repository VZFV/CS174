<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(print_error());

if (isset($_POST['name']) && $_FILES){
    if(file_tester($_FILES)){
        $name = get_post($conn, 'name');
        $filename = htmlentities($_FILES['filename']['tmp_name']);
        $file_content = htmlentities(file_get_contents($filename));
        $query = "INSERT INTO contentdb VALUES ('$name','$file_content')";
        $result = $conn->query($query);
        if (!$result) die (print_error());
    }
}

echo <<<_END
    <html>
    <head>
    <title>Feng Zhang CS 174 Assignment 4</title>
    </head>
    <body>
        <form method='post' action='assignment4.php' enctype = 'multipart/form-data'>
        Type your name and select one file to upload. The file format should be .txt.
        <p><input type = 'text' name = 'name'></p>
        <p><input type = 'file' name = 'filename'></p>
        <p><input type='submit' value="Submit your name and file"></p>
_END;


$query = "SELECT * FROM contentdb";
$result = $conn->query($query);
if (!$result) die (print_error());

$rows = $result -> num_rows;
for ($j = 0; $j < $rows; ++$j){
    $result -> data_seek($j);
    $row = $result -> fetch_array(MYSQLI_NUM);
    echo <<<_END
<pre>
Name: $row[0]
Content: $row[1]
</pre>
_END;
}

$result -> close();
$conn -> close();

function file_tester($file)
{
    if (htmlentities($file['filename']['type']) == 'text/plain')
        return true;
    else {
        echo "Please submit correct file type (.txt)";
        return false;
    }
}

function print_error(){
    return "There might be an error";
}

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

echo "</body></html>";

?>