<?php 
//PDO FORMAT
$host = 'localhost';
$db = '';
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

// PROCEDURAL FORMAT current one we are using right now
//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $dbname = " db_receipt";
// //you must be considerate of your double connection files in case of changes
//     $dbconn = mysqli_connect('localhost', 'root', '', 'db_receipt');
//      if (!$dbconn) {
//        mysqli_set_charset($dbconn, 'utf-8');
//    }

// OBJECT ORIENTED
//DEFINE ('DB_HOST', 'localhost');
//DEFINE ('DB_USER', 'root');
//DEFINE ('DB_PASSWORD', '');
//DEFINE ('DB_NAME', '');

// Create connection
//$conn = new mysqli($servername, $username, $password);

/* Check connection
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
*/



?>