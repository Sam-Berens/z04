<?php
header('Content-Type: application/json');

// A function that makes stimuli assignments
function MakeAssignment($SubjectId) {
	$RandBit = rand(0,1);
    $ImgNums = range(0,5);
	$ImgIds = array();
	if ($RandBit == 0) {
		for ($ii=0; $ii<6; $ii++) {
			$ImgIds[$ii] = sprintf("./Imgs/l%02d.png",$ImgNums[$ii]);
		}
	} else {
		for ($ii=0; $ii<6; $ii++) {
			$ImgIds[$ii] = sprintf("./Imgs/h%02d.png",$ImgNums[$ii]);
		}
	}
    shuffle($ImgIds);
    $Assignment['tA'] = $ImgIds[ 0];
    $Assignment['tB'] = $ImgIds[ 1];
    $Assignment['tC'] = $ImgIds[ 2];
    $Assignment['tD'] = $ImgIds[ 3];
    $Assignment['tE'] = $ImgIds[ 4];
    $Assignment['tF'] = $ImgIds[ 5];
    return $Assignment;
}

// Unpack inputs:
$Input = json_decode(file_get_contents('php://input'), true);
if (!$Input) {
	$Input = $_POST; // Only used when testing via MATLAB's webwrite function.
}
$SubjectId = $Input['SubjectId'];

// Create the output array:
$Result = array();

// Get the SubjectInt
$SubjectId = strtolower($SubjectId);
$SubjectInt = intval($SubjectId,36);
$SubjectIntW = $SubjectInt % 4294967295;
			
// Set the Seed:
srand($SubjectIntW);

// Set the output:
$Assignment = MakeAssignment($SubjectId);
$Result['Assignment'] = $Assignment;

// Echo the output:
echo json_encode($Result);
?>