<?PHP 

include_once (dirname(__FILE__).'/connect.php');
//salinate $_POST
$_POST=$dbi->prepareUserData($_POST);

$userID		=	$_POST['userID'];
$userGroup	=	$_POST['userGroup'];

$where  = "WHERE `search_history`.`deleted`=0 AND `search_history`.`id`>0 ";

//for admin group users get all the queries BUT for other get only public queries(search_history.user_id=0) and queries saved by himself
if(($userGroup!='admin') && ($userGroup!='superadmin')){
    $where .= "AND ((`search_history`.`user_id`='$userID') OR (`search_history`.`search_4_all`='1') ) ";
}

$order = "ORDER BY `search_history`.`name`";

$query=sprintf("select `search_history`.`id`, `search_history`.`name`, `users`.`user_name` from search_history LEFT JOIN `users` on `users`.`id`=`search_history`.`user_id` $where $order");

/*
$fh = fopen('querytest.txt', "a");
fwrite($fh, "dash: ".$query."\n");
*/

$retVal='';
//$retVal.='<queryTag><id>'. 0 .'</id></queryTag>'; // zero row already in database
$result=$dbi->query($query);

    $retVal.='<dashTag><query data="">None</query>';
    
	if($result)
	{
		if(mysql_num_rows($result)>0){
       
            while($row=mysql_fetch_assoc($result)){	
				if ($row['user_name']!='')
					$tag=' ['.$row['user_name'].']';
				else
					$tag='';
                $retVal.='<query data="'.$row['id'].'">'.$row['name'].$tag.'</query>';
 			}  
        }
    }
    $retVal.='</dashTag>';
    
/*
fwrite($fh, "dash: ".$retVal); 
fwrite($fh,"\n");
fclose($fh);
*/
 
$query=sprintf("select `search_history`.`id`, `search_history`.`query`, `search_history`.`search_4_all`, `search_history`.`name`, `search_history`.`timestamp`,  `users`.`user_name` from search_history LEFT JOIN `users` on `users`.`id`=`search_history`.`user_id` $where AND `search_history`.`saved` is not null $order");
    
$result=$dbi->query($query);

	if($result)
	{
		if(mysql_num_rows($result)>0){

			while($row=mysql_fetch_assoc($result)){	
				
				$date_split=split(' ',$row['timestamp']);
				if ($row['user_name']!='')
					$tag=' ['.$row['user_name'].']';
				else
					$tag='';
				$tag.=' @'.$date_split[0];
				
				$retVal.='<queryTag>';
				$retVal.="<id>".$row['id']."</id>";
				$retVal.="<query>".$row['query']."</query>";
				$retVal.="<search_4_all>".$row['search_4_all']."</search_4_all>";
				$retVal.="<name>".$row['name'].$tag."</name>";
                $retVal.="<timestamp>".$row['timestamp']."</timestamp>";
				$retVal.='</queryTag>';
			}
		}			
	}

/*
$fh = fopen('querytest.txt', "a");
fwrite($fh, "query: ".$query."\n");
fwrite($fh, "query: ".$retVal); 
fwrite($fh,"\n");
fclose($fh);
*/

$query=sprintf("select `search_history`.`id`, `search_history`.`name`, `users`.`user_name` from search_history LEFT JOIN `users` on `users`.`id`=`search_history`.`user_id` $where AND `search_history`.`saved` is not null $order");
    
$result=$dbi->query($query);
	
	$retVal.="<savedsearches>";
	$retVal.='<search data="0">None</search>';

	if($result)
	{
		if(mysql_num_rows($result)>0){

            while($row=mysql_fetch_assoc($result)){	
				if ($row['user_name']!='')
					$tag=' ['.$row['user_name'].']';
				else
					$tag='';
                $retVal.='<search data="'.$row['id'].'">'.$row['name'].$tag.'</search>';
 			}  
        }
    }		
	$retVal.="</savedsearches>";

/*
$fh = fopen('querytest.txt', "a");
fwrite($fh, "saved: ".$query."\n");
fwrite($fh, "saved: ".$retVal); 
fwrite($fh,"\n");
fclose($fh);
*/

print $retVal;

?>