<?php
header('Content-Type: application/json');
require __DIR__ . '/Credentials.php';

// Connect to the database:
$Conn = new mysqli($Servername, $Username, $Password, $Dbname);
if($Conn->connect_error) {
	die("Connection failed: " . $Conn->connect_error);
}

// Unpack inputs:
$Input = json_decode(file_get_contents('php://input'), true);
if (!$Input) {
	$Input = $_POST; // Only used when testing via MATLAB's webwrite function.
}
$SubjectId = $Input['SubjectId'];
$SubjectId = mysqli_real_escape_string($Conn,$SubjectId);


$Sql = "SELECT * FROM TItrainIO WHERE SubjectId = '$SubjectId'";
$QueryRes = mysqli_query($Conn, $Sql);
$TItrainIO = null;
if($QueryRes === FALSE) {
    // If there is an SQL error:
    $Conn->close();
    die("Query Sql failed to execute successfully");
} else {
	// If the query ran successfully...
	while($Row = mysqli_fetch_assoc($QueryRes)) {
		$TItrainIO = $Row["TItrainIO"];
	}
}
$Conn->close();
echo($TItrainIO);
?>