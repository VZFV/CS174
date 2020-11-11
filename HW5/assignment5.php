<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(print_error());

// add data to student table
if (isset($_POST['advisor']) && isset($_POST['student']) &&
    isset($_POST['id']) && isset($_POST['class'])){
    $advisor = get_post($conn, 'advisor');
	$student = get_post($conn, 'student');
	$id = get_post($conn, 'id');
	$class = get_post($conn, 'class');
    prepare_add($conn, $advisor, $student, $id, $class);
}

echo <<<_END
    <html>
    <head>
    <title>Feng Zhang CS 174 Assignment 5</title>
    </head>
    <body>
        <form method='post' action='assignment5.php' enctype = 'multipart/form-data'>
        Type your name and select one file to upload. The file format should be .txt.
        <p>Advisor name: <input type = 'text' name = 'advisor'></p>
        <p>Student name: <input type = 'text' name = 'student'></p>
        <p>Student ID: <input type = 'text' name = 'id'></p>
        <p>Class code: <input type = 'text' name = 'class'></p>
        <p><input type = 'submit' value="Add Data"></p>
        </form>
        <form method='post' action='assignment5.php' enctype = 'multipart/form-data'>
        <p>Enter an advisor name to search: <input type = 'text' name = 'search'></p>
        <p><input type = 'submit' value="search results"></p>
</form>
_END;


if (isset($_POST['search'])){
    $search = get_post($conn, 'search');
    $result = prepare_search($conn, $search);

    $rows = $result -> num_rows;
    for ($j = 0; $j < $rows; ++$j){
        $result -> data_seek($j);
        $row = $result -> fetch_array(MYSQLI_NUM);
        echo <<<_END
<pre>
Advisor name: $row[0]
Student name: $row[1]
Student ID: $row[2]
Class code: $row[3]
</pre>
_END;
    }
    $result -> close();
}

$conn -> close();

// prepare for adding data
function prepare_add($conn, $advisor, $student, $id, $class){
    $stmt = $conn -> prepare('INSERT INTO student VALUES (?,?,?,?)');
    $stmt -> bind_param('ssii',$advisor,$student,$id,$class);
    $stmt->execute();
    if($stmt -> affected_rows == 0) die (print_error());
    $stmt -> close();
}

//prepare for searching data
function prepare_search($conn, $advisor){
    $stmt = $conn->prepare("SELECT * FROM student WHERE advisor=?");
    $stmt -> bind_param('s', $advisor);
    $stmt->execute();
    $result = $stmt -> get_result();
    if(!$result) die(print_error());
    $stmt -> close();
    return $result;
}

function print_error(){
    return "There might be an error";
}

function get_post($conn, $var)
{
    $var = $conn->real_escape_string($_POST[$var]);
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

echo "</body></html>";

?>
