<?php

$csv1 = $_FILES["fileToUpload"]["name"];
echo $csv1;
echo " isset ";
echo file($csv1);
$csv2 = $_POST["name"];
//$csv3 = array_map('str_getcsv', file('data.csv'));
$csv3 = array_map('str_getcsv', file($_FILES["fileToUpload"]["tmp_name"]));
$csv4 = str_getcsv(file($csv1), ';');
if (isset($csv4)) {
	echo "csv4 str_getcsv map is set"; 
	echo "<br>";
	//echo $csv4;
} else {
	echo "csv4 str_getcsv map is not set";
}
echo " size of ";
echo sizeof($csv4);
foreach ($csv4 as $value) {
	echo "we are people";    
	echo $value;
}
echo "<br>";
$csvFile  = file_get_contents($_FILES["fileToUpload"][tmp_name]);
$csvFile2 = explode("\n", file_get_contents($_FILES["fileToUpload"][tmp_name]));
$csvFile2Len = sizeof($csvFile2);
echo $csvFile2Len;
/*
for ($i = 1; $i < $csvFile2Len; $i++) {
	$acctArr[i-1] = str_getcsv($csvFile2, ";");
	echo $acctArr[i-1]." "; 
}
*/
//myvar = "a";
$it = 0;
//$acctArr = Array();
foreach ($csvFile2 as $entry) {
	
    $acctArr = str_getcsv($entry, ";");
		
	echo $acctArr[1]." "; 
	$it = $it+1;
}
echo " php. ";
echo $csvFile;
echo $csv3;

?>
