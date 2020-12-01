<?php
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(print_error());

if (!isset($_SESSION['username'])){
    // home page
    if (!isset($_POST['username']) || !isset($_POST['password'])){
        echo <<<_END
    <html>
    <head>
    <title>Feng Zhang CS 174 Midterm 2</title>
    </head>
    <body>
        <form method='post' action='midterm2.php' enctype = 'multipart/form-data'>
        <p>User name: <input type = 'text' name = 'username'></p>
        <p>Password: <input type = 'text' name = 'password'></p>
        <p>
        <input type = 'submit' name = 'button1' value="sign in">
        <input type = 'submit' name = 'button2' value="sign up">
        </p>
        </form>
_END;
    }
    // user sign in and prompt to input a string and submit a txt file
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['button1'])){
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');

        if(!check_username($conn,$username)[0]){
            die ("Invalid user name or password");
        }else{
            $stmt = $conn->prepare("SELECT * FROM USER WHERE username=?");
            $result = query($conn,$username,$stmt);
            if (!$result) die (print_error());
            $row = mysqli_fetch_array($result, MYSQLI_NUM);
            $hash = hash('ripemd128',"$row[1]$password");
            if($hash == $row[2]){
                $_SESSION['username'] = $username;
            }else
                die ("Invalid user name or password");

            $result -> close();
            $stmt -> close();
        }
    }
    // user sign up
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['button2'])){
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');

        if(!check_username($conn,$username)[0]){
            $salt = random();
            $hash = hash('ripemd128',"$salt$password");

            $stmt = $conn -> prepare('INSERT INTO USER VALUES (?,?,?)');
            $stmt -> bind_param('sss', $username, $salt,$hash);
            $stmt -> execute();
            if($stmt -> affected_rows == 0) die (print_error());
            $stmt -> close();
            echo "Successfully create username and password";
        }else{
            echo "The username already exists";
        }
    }
}

if (isset($_SESSION['username'])){
    echo <<<_END
<form method='post' action='midterm2.php' enctype = 'multipart/form-data'>
        Successfully sign in.
        Type file name and select one file to upload. The file format should be .txt.
        <p>Name: <input type = 'text' name = 'name'></p>
        <p><input type = 'file' name = 'filename'></p>
        <p><input type='submit' name = 'button3' value="Submit your name and file"></p>
</form>
_END;
    if (isset($_POST['name']) && $_FILES){
        if(file_tester($_FILES)){
            $name = get_post($conn, 'name');
            $filename = htmlentities($_FILES['filename']['tmp_name']);
            $file_content = htmlentities(file_get_contents($filename));

            $stmt = $conn -> prepare('INSERT INTO filecontent VALUES (?,?,?)');
            $stmt -> bind_param('sss', $_SESSION['username'], $name,$file_content);
            $stmt -> execute();
            if($stmt -> affected_rows == 0) die (print_error());
            $stmt -> close();
        }
    }

    print_content($_SESSION['username'],$conn);
    if(isset($_POST['destroy'])){
        session_unset();
        session_destroy();
    }

}

$conn ->close();

// query user files from database
function query($conn, $username,$stmt){
    $stmt -> bind_param('s', $username);
    $stmt -> execute();
    $result = $stmt -> get_result();
    if(!$result) die(print_error());
    return $result;
}

// print user file name and content
function print_content($username,$conn){
    $stmt = $conn->prepare("SELECT * FROM filecontent WHERE username=?");
    $result = query($conn, $username,$stmt);
    $rows = $result -> num_rows;
    for ($j = 0; $j < $rows; ++$j){
        $result -> data_seek($j);
        $row = $result -> fetch_array(MYSQLI_NUM);
        echo <<<_END
<pre>
Name: $row[1]
Content: $row[2]
</pre>
_END;
    }
}

/*
 * check if username in database
 * return true if username is in database
 */
function check_username($conn,$username){
    $stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM USER WHERE username=?)");
    $stmt -> bind_param('s', $username);
    $stmt -> execute();
    $result = $stmt -> get_result();
    if(!$result) die(print_error());
    $stmt -> close();
    return mysqli_fetch_array($result, MYSQLI_NUM);
}

/*
 * test if file extension is .txt
 * return true if it is .txt
 */
function file_tester($file){
    if (htmlentities($file['filename']['type']) == 'text/plain')
        return true;
    else {
        echo "Please submit correct file type (.txt)";
        return false;
    }
}

// create random string as salt
function random() {
    $charset = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789!@#$%^&*().,/?[]{}-=';
    $random = "";
    for ($i = 0; $i < 10; $i++) {
        $random .= $charset[mt_rand(0, strlen($charset) - 1)];
    }
    return $random;
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
