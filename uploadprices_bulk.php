<?php
//load constantes
if (is_file('config.php')) {
        require_once('config.php');
}
//set charset
ini_set("default_charset",'utf-8');//utf-8 windows-1251
ini_set('display_errors', 1);
set_time_limit(600);
//get variables from input
$csv1 = $_FILES["fileToUpload"]["name"];
$man_id = $_POST["manufacturer_id"];
//echo $man_id;
//echo $csv1;
//echo " isset ";
//echo file($csv1);
$csv2 = $_POST["name"];
//$csv3 = array_map('str_getcsv', file('data.csv'));
$csv3 = array_map('str_getcsv', file($_FILES["fileToUpload"]["tmp_name"]));
$csv4 = str_getcsv(file($csv1), ';');
if (isset($csv4)) {
	//echo "csv4 str_getcsv map is set"; 
	//echo "<br>";
	//echo $csv4;
} else {
	//echo "csv4 str_getcsv map is not set";
}
//echo " size of ";
//echo sizeof($csv4);
foreach ($csv4 as $value) {
	//echo "we are people";    
	//echo $value;
}
//echo "<br>";
$csvFile  = file_get_contents($_FILES["fileToUpload"][tmp_name]);
$csvFile2 = explode("\n", file_get_contents($_FILES["fileToUpload"][tmp_name]));
$csvFile2Len = sizeof($csvFile2);
//echo $csvFile2Len;
/*
for ($i = 1; $i < $csvFile2Len; $i++) {
	$acctArr[i-1] = str_getcsv($csvFile2, ";");
	echo $acctArr[i-1]." "; 
}
*/
//myvar = "a";
$it = 0;
$idArr    = Array();
$priceArr = Array();
foreach ($csvFile2 as $entry) {
	
    $acctArr       = str_getcsv($entry, ";");
	$idArr[$it]    = $acctArr[0];
	$priceArr[$it] = $acctArr[1];
	//echo $acctArr[1]." "; 
	$it = $it+1;
};
echo " array size is ".$it;
for ($i = 0; $i < $it; $i++) {
	echo $idArr[$i]." ".$priceArr[$i]."<br>"; 
};

//echo " php. ";
//echo $csvFile;
//echo $csv3;
//connect to database
$conn = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
/* change character set to utf8 */
if (!mysqli_set_charset($conn, "utf8")) {
  //  printf("Error loading character set utf8: %s\n", mysqli_error($conn));
    exit();
} else {
  //  printf("Current character set: %s\n", mysqli_character_set_name($conn));
}
for ($i = 0; $i < $it; $i++) {
	$query_line = "UPDATE oc_product, oc_product_discount 
 	SET oc_product_discount.price = ".$priceArr[$i].
 	" WHERE (oc_product.product_id = oc_product_discount.product_id 
      && oc_product.manufacturer_id = ".$man_id.
 	" && oc_product.model = ".$idArr[$i].
 	")";
	//echo "<br>";
	//echo "hello";
	echo $query_line;
	mysqli_query($conn, $query_line);
	echo "<br>";
	echo "query result is ".$query;
	//$result = $conn->query($query_line);
	//echo $idArr[$i]." ".$priceArr[$i]."<br>"; 
};

echo "<html><body>";
$query = mysqli_query($conn, $query_line);
echo "<a href=\"uploadprices_bulk.html\">back to input</a>";
echo "</body></html>";
?>
