<?php
header('Content-Type: application/json');
require __DIR__ . '/Credentials.php';

// Connect to the database:
$Conn = new mysqli($Servername, $Username, $Password, $Dbname);
if($Conn->connect_error) {
	die("Connection failed: " . $Conn->connect_error);
}

$Input = json_decode(file_get_contents('php://input'), true);
if (!$Input) {
	$Input = $_POST; // Only used when testing via MATLAB's webwrite function.
} 

// Get the input variables:
$FunctionCall = $Input['FunctionCall'];
$FunctionCall = mysqli_real_escape_string($Conn,$FunctionCall);
$SubjectId = $Input['SubjectId'];
$SubjectId = mysqli_real_escape_string($Conn,$SubjectId);
$ClientTimeZone = $Input['ClientTimeZone'];
$ClientTimeZone = mysqli_real_escape_string($Conn,$ClientTimeZone);
$Now = new DateTimeImmutable("now", new DateTimeZone('Europe/London'));
$DateTime_Write = $Now->format('Y-m-d\TH:i:s');

switch($FunctionCall) {
	case 'WriteTItrainIO':
	    
	    // Parse inputs
		$TItrainIO = $Input["TItrainIO"];
		$TItrainIO = mysqli_real_escape_string($Conn,$TItrainIO);
		
		// SQL to save TItrainIO
        $Sql01 = "CALL RecordTItrainIO('$SubjectId','$DateTime_Write','$ClientTimeZone','$TItrainIO')";
			
		// Run and set the result:
		if(($Conn->query($Sql01)===true)) {
			$Result['TargetUrl'] = "./TIprobe.html?SubjectId=$SubjectId#";
		} else {
			$Conn->close();
			die('Query Sql01 and/or Sql02 failed to execute successfully;');
		}
		break;
		
	case 'WriteTIprobeIO':
	    
	    // Parse inputs
		$TIprobeIO = $Input["TIprobeIO"];
		$TIprobeIO = mysqli_real_escape_string($Conn,$TIprobeIO);
		
		// SQL to save TIprobeIO
        $Sql01 = "CALL RecordTIprobeIO('$SubjectId','$DateTime_Write','$ClientTimeZone','$TIprobeIO')";
			
		// Run and set the result:
		if(($Conn->query($Sql01)===true)) {
			$Result['TargetUrl'] = "./End.html?SubjectId=$SubjectId#";
		} else {
			$Conn->close();
			die('Query Sql01 and/or Sql02 failed to execute successfully;');
		}
		break;
		
	default:
		// Kill it if the function call is bad:
		$Conn->close();
		die('Bad function call.');
		break;
}

$Conn->close();
echo json_encode($Result);

?>