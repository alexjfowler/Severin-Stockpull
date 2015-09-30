<?php 
echo "start executing stockpull script\n";
$pathToDir = "C:\\Users\\alex\\Desktop\\Severin\\";
$csvFile = $pathToDir . "stockInfo.csv";

$stocks = array();
# Step #1 read in a csv that contains a list of stock tickers
$file = fopen( $pathToDir . "tickers.csv", "r") or die("Unable to open ticker file");

# The firt row contains a column name so throw it away
$data = fgetcsv($file);
while(($data = fgetcsv($file)) !== FALSE) {
	$stocks[] = $data[0];
}
echo "Finished reading in list of tickers from ticker file\n";

# clear output file
// file_put_contents($csvFile, $csv);

$urlPrepend = "http://download.finance.yahoo.com/d/quotes.csv?s=%40%5EDJI,";
$urlAppend = "&f=nsl1op&e=.csv";
$urlMiddle = "";
# use the yahoo finance api to get details for each stock
for ($i = 0; $i * 200 < count($stocks); $i++) {
	$j = 0;
	for ($j = 0; $j < 200 && $j + $i < count($stocks); $j++) {
		if ($urlMiddle === "") {
			$urlMiddle = $urlMiddle . $stocks[$i + $j];
		}
		else {
			$urlMiddle = $urlMiddle . "," . $stocks[$i + $j];
		}
	}
	$url = $urlPrepend . $urlMiddle . $urlAppend;

	
	# wait 10 seconds between calls to avoid throttling by yahoo finance
	$csv = file_get_contents($url);
	file_put_contents($csvFile, $csv, FILE_APPEND);
	echo "Wrote " . $j . " records to stockInfo.txt - " . (count($stocks) - 200 * $i) . " left to go. \n";

	sleep(10);
}

$dbConn = new mysqli($servername, $username, $password, $dbname) or die("could not connect to mysql database");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// $serverName = "localhost";
// $username = "root";
// $password = "SOMEPASSWORD" # you need to put the actual password here;
// $dbName = "yahoo_finance";
// 
// $query = <<<eof
//     LOAD DATA INFILE '$csvFile'
//      INTO TABLE stocks
//      FIELDS TERMINATED BY ','
//      LINES TERMINATED BY '\n'
//     (symbol, latestValue, open, latestClose)
// eof;
// 
// $dbConn->query($query);
// $dbConn->close();

echo 'Finished running stockpull script!';

# Step #3 read in the information about each ticker into the MySQL database

?> 
