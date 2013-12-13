<?PHP
include_once (dirname(__FILE__).'/connect.php');

ini_set('display_errors', "1");
ini_set('error_reporting', E_ALL ^ E_NOTICE);
header("Content-type: text/xml"); 


$nameID=$_POST['nameid'];
//$nameID=16;
$query = "SELECT * FROM passport WHERE passport.deleted='0' AND name_id=".$nameID; 
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
    $xml_output .= "\t\t<photo_link>" . $row['photo_link'] . "</photo_link>\n";
	$xml_output .= "\t\t<scan_link>" . $row['scan_link'] . "</scan_link>\n";	
	$xml_output .= "\t\t<number>" . $row['number'] . "</number>\n";  
	$xml_output .= "\t\t<issue_date>" . $row['issue_date'] . "</issue_date>\n";
	$xml_output .= "\t\t<expiry_date>" . $row['expiry_date'] . "</expiry_date>\n";
	$xml_output .= "\t\t<issue_city>" . $row['issue_city'] . "</issue_city>\n";
	
	$xml_output .= "\t\t<visa>" . $dbi->get_table_item('visa','title','id',$row['visa_id']) . "</visa>\n";
	$xml_output .= "\t\t<country>" . $dbi->get_table_item('country','name','id',$row['issue_country_id']) . "</country>\n";
	$xml_output .= "\t\t<visa_id>" . $row['visa_id'] . "</visa_id>\n";
	$xml_output .= "\t\t<issue_country_id>" . $row['issue_country_id'] . "</issue_country_id>\n";
	
	
    $xml_output .= "\t</channel>\n"; 
} 

$xml_output .= "</rss>"; 

echo $xml_output; 

?> 
