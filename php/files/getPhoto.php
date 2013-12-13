<?PHP
include_once (dirname(__FILE__).'/connect.php');

ini_set('display_errors', "1");
ini_set('error_reporting', E_ALL ^ E_NOTICE);
header("Content-type: text/xml"); 


$nameID=$_POST['nameid'];

$query = "SELECT * FROM photo WHERE photo.deleted='0' AND name_id=".$nameID; 
$resultID = mysql_query($query) or die("Data not found."); 

$xml_output = "<?xml version='1.0' encoding='utf-8'?>\n"; 
$xml_output .= "<rss>\n"; 


for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){ 
    $row = mysql_fetch_assoc($resultID); 
    $xml_output .= "\t<channel>\n";
    
    
        // Escaping illegal characters 
        $row['text'] = str_replace("&", "&", $row['text']); 
        $row['text'] = str_replace("<", "<", $row['text']); 
        $row['text'] = str_replace(">", "&gt;", $row['text']); 
        $row['text'] = str_replace("\"", "&quot;", $row['text']); 
    $xml_output .= "\t\t<__id>" . $row['id'] . "</__id>\n";
    $xml_output .= "\t\t<link>" . $row['link'] . "</link>\n";
	$xml_output .= "\t\t<description>" . $row['description'] . "</description>\n";  
	
    $xml_output .= "\t</channel>\n"; 
} 

$xml_output .= "</rss>"; 

echo $xml_output; 

?> 
