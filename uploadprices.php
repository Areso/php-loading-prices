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
$csv1    = $_FILES["fileToUpload"]["name"];
$man_id  = $_POST["manufacturer_id"];
$scenary = $_POST["radio"];
if ($scenary == "SPECIAL") {
	$date_start = $_POST["date_start"];
	$date_end   = $_POST["date_end"]; 
};
$csv2 = $_POST["name"];
//$csv3 = array_map('str_getcsv', file('data.csv'));
$csv3 = array_map('str_getcsv', file($_FILES["fileToUpload"]["tmp_name"]));
$csv4 = str_getcsv(file($csv1), ';');
$csvFile  = file_get_contents($_FILES["fileToUpload"][tmp_name]);
$csvFile2 = explode("\n", file_get_contents($_FILES["fileToUpload"][tmp_name]));
$csvFile2Len = sizeof($csvFile2);
$it = 0;
$idArr    = Array();
$priceArr = Array();
foreach ($csvFile2 as $entry) {
    $acctArr       = str_getcsv($entry, ";");
	$idArr[$it]    = trim($acctArr[0], " ");
	$priceArr[$it] = trim($acctArr[1], " ");
	//echo $acctArr[1]." "; 
	$it = $it+1;
};
echo " array size is ".$it;


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
if ($scenary == "PRODUCT") { 
	for ($i = 0; $i < $it; $i++) {
		$query_line = "UPDATE oc_product 
		SET oc_product.price = ".$priceArr[$i].
		" WHERE (oc_product.manufacturer_id = ".$man_id.
		" && oc_product.model = ".$idArr[$i].
		")";
		echo $query_line;
		$result = mysqli_query($conn, $query_line);
		echo " Query result is ".$result;
		echo "<br>";		
	};
};
if ($scenary == "DISCOUNT") {
	for ($i = 0; $i < $it; $i++) {
		$query_line = "UPDATE oc_product, oc_product_discount 
		SET oc_product_discount.price = ".$priceArr[$i].
		" WHERE (oc_product.product_id = oc_product_discount.product_id 
		  && oc_product.manufacturer_id = ".$man_id.
		" && oc_product.model = ".$idArr[$i].
		")";
		echo $query_line;
		$result = mysqli_query($conn, $query_line);
		echo " Query result is ".$result;
		echo "<br>";		 
	};
};
//INSERT INTO Customers (CustomerName, City, Country)
//VALUES ((SELECT product_id FROM MyTable), 'Stavanger', 'Norway');

if ($scenary == "SPECIAL") {
	for ($i = 0; $i < $it; $i++) {
		$query_line = "INSERT INTO oc_product_special 
		(product_id, customer_group_id, priority, price, date_start, date_end) 
		VALUES 
		((SELECT oc_product.product_id FROM oc_product WHERE oc_product.model = ".$idArr[$i]." && oc_product.manufacturer_id = ".$man_id."), 
		'2', '0', ".$priceArr[$i].", '".$date_start."', '".$date_end."')";
		echo $query_line;
		$result = mysqli_query($conn, $query_line);
		echo " Query result is ".$result;
		echo "<br>";
		//echo "date start is ".$date_start." and date end is ".$date_end;
		echo "<br>";
	};	
};
echo "<html><body>";
$query = mysqli_query($conn, $query_line);
echo "<a href=\"uploadprices.html\">back to input</a>";
echo "</body></html>";
?>
