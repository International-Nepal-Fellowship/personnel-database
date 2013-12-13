<?php
 include_once (dirname(__FILE__).'/date.php');
 //include_once (dirname(__FILE__).'/DBI.php');
 include_once (dirname(__FILE__).'/connect.php');
 
class DateConvert {  
	
	function DateConvert($str=''){	
	
		$s_Date=$str;			
		
	}	
		
	function _findSeparator($s_strVal){

		$i_sepPos="";
		
		If (strpos($s_strVal,"/")>0)
				$i_sepPos="/";
		Elseif(strpos($s_strVal,".")>0)
				$i_sepPos=".";		
		ElseIf(strpos($s_strVal," ")>0)				
				$i_sepPos=" ";	
		ElseIf(strpos($s_strVal,"-")>0)	
				$i_sepPos="-";	
			
		return $i_sepPos;
		
	}
	
	function getAll(){ 

		$dbi=new DBI();
		
	    //return a list of all the users
		$Query = "SELECT * from nepali_day";
		$Result =$dbi->query( $Query );

		
		$i=1;
		while ( $User = mysql_fetch_object( $Result ) ){					
			$days[$i]=$User->Day;						
			$i++;
					
		}	      
		
		$dbi->disconnect();	
	   	
		return $days;	
	   
	} 
	  
	function nepToEnglish($s_Date){

			if($s_Date=="") return "00/00/00";
				
			//object of date class
			$_dateConvert=new Date;
			
			//initialization-variables used
			$i_engNextMonth=0;
			$i_engDate=0;
			$s_date="";	 
			$s_nepDay="";	 
	  		$s_nepMonth="";
	  		$s_nepYear="";
			$s_sysDateFormat="";
			$s_sep="";
			$b_do_rough_calc=false;		  		 	
			$i_daysDiff=0;
			$i_subDays=0;			
			
	  	 
	  		$s_sep=$this->_findSeparator($s_Date);
	  		If ($s_sep==""){
				echo "You must use space( ) or  period(.) or slash(/)  or dash(-) to separate day, month and year";
				return "00/00/00";				
			}
			
			$s_date = explode($s_sep, $s_Date);
			$s_sysDateFormat=$this->_getSystemDateFormat();//Find out the system date format
			
			
			if(sizeof($s_date)>2){//if year is entered
				If($s_sysDateFormat=="d/m/Y"){
					$s_nepDay=$s_date[0];	 
					$s_nepMonth=$s_date[1];
					$s_nepYear=$s_date[2];
				}
				ElseIf($s_sysDateFormat=="m/d/Y"){
					$s_nepDay=$s_date[1];	 
					$s_nepMonth=$s_date[0];
					$s_nepYear=$s_date[2];
				}
				ElseIf($s_sysDateFormat=="Y/m/d"){
					$s_nepDay=$s_date[2];	 
					$s_nepMonth=$s_date[1];
					$s_nepYear=$s_date[0];
				}
				
						
				If ($s_nepYear<100)
					$s_nepYear=$s_nepYear+2000;
				
			}
			Else{//true if year is not entered	
					$s_nepYear="2000";	//=>we can assign current nepali year  also
				
					if($s_sysDateFormat=="d/m/Y"){
						$s_nepDay=(int)$s_date[0];
						$s_nepMonth=(int)$s_date[1];
					}
					ElseIf($s_sysDateFormat=="m/d/Y"){
						$s_nepDay=(int)$s_date[1];
						$s_nepMonth=(int)$s_date[0];
					}
					ElseIf($s_sysDateFormat=="Y/m/d"){
						$s_nepDay=(int)$s_date[3];
						$s_nepMonth=(int)$s_date[2];
					}					
					
			}
			
			If (($s_nepDay>32) | ($s_nepDay<1)|($s_nepMonth<1) | ($s_nepMonth>12)){
				echo "Error: Invalid Date!!";
				return "00/00/00";
			}
			
												
						
	  		$i_nepDays=array();
	  		$i_nepDays=$this->getAll();//loads all the mapping gregorian days into an array from the database
	  	
	  			//test1
	  			//echo "Nepali Date:".$i_nepMonth."/".$i_nepDay."/".$i_nepYear."<br>";
			
		//substract first element of the array(i.e,2038) from nepali date and multiplies it by 12 to find out the gregorian date for the first of nepali month
	  		$i_arrayIndex=($s_nepYear-$i_nepDays[1])*12;			
	  		$i_arrayIndex=$i_arrayIndex+$s_nepMonth+1;
	  	
	  			//test2
	  			//echo "Array Index:".$i_arrayIndex."<br>";
	  	
	  	
	  		
	  		if(($i_arrayIndex+1)>sizeOf($i_nepDays)){		  		 	
	  			$b_do_rough_calc=true;}
	  		elseif($i_arrayIndex<2)
	  			$b_do_rough_calc=true;
	  			
	  			
	  	
	  		if(!$b_do_rough_calc){
	  			//Find English date on first of Nepali month
	  			if($s_nepMonth<10)//if true adds 3 and subtracts 57 from month and year of nepali date
	  				$i_engDate=mktime(0,0,0,$s_nepMonth+3,$i_nepDays[$i_arrayIndex],$s_nepYear-57); 				//$s_engDate=(string)($i_nepDays[$i_arrayIndex])."-".(string)($i_nepMonth+3)."-".(string)($i_nepYear-57);
	  			else//if true subtracts 9 and 56 resp from month and year of nepali date
	  				$i_engDate=mktime(0,0,0,$s_nepMonth-9,$i_nepDays[$i_arrayIndex],$s_nepYear-56); 				//$s_engDate=(string)($i_nepDays[$i_arrayIndex])."-".string($i_nepMonth-9)."-".(string)($i_nepYear-56);	
	  	
	  				//test3	
	  				//echo "Engdate".date("m.d.y",$s_engDate)."<br>";
	  		
	  			//Find Next English Month 
	  			
	  			if($s_nepMonth<9)//if true adds 4 and subtracts 57 from month and year of nepali date
	  				$i_engNextMonth=mktime(0,0,0,$s_nepMonth+4,$i_nepDays[$i_arrayIndex+1],$s_nepYear-57); 				//$s_engNextMonth=(string)($i_nepDays[$i_arrayIndex+1])."-".(string)($i_nepMonth+4)."-".(string)($i_nepYear-57);	  				
	  			elseif($s_nepMonth>=9)//if true subtracts 8 and 56 resp from month and year of nepali date
	  				$i_engNextMonth=mktime(0,0,0,$s_nepMonth-8,$i_nepDays[$i_arrayIndex+1],$s_nepYear-56);	  				//$s_engNextMonth=(string)($i_nepDays[$i_arrayIndex+1])."-".(string)($i_nepMonth-8)."-".(string)($i_nepYear-56);
		
	  				//test4
	  				//echo "nextEngdate".date("d.m.y",$s_engNextMonth)."<br>";
					
				//it divides diff of unix time stamp  by 86400 to get the number of days.
				$i_daysDiff=($i_engNextMonth-$i_engDate)/86400;
				
					//test5
	  				//echo "days diff".$i_daysDiff."<br>";
	  	
	  			if($i_daysDiff<$s_nepDay){
	  				echo "Error: Invalid Date!!";
					return "00/00/00";
	  			}	
	  			else{//add days to english date`		
	  				$s_nepDay--;						
	  				return date($s_sysDateFormat,(strtotime(date("m/d/Y",$i_engDate)." +$s_nepDay days")));
	  			}
	  		}
	  	
	  		if($b_do_rough_calc){	  						
	  			$i_subDays=243+15+17+(365*56);
				//add $i_subDays to the nepali date.
	  			return $_dateConvert->format($s_sysDateFormat,$_dateConvert->_mktime(0,0,0,$s_nepMonth,$s_nepDay-$i_subDays,$s_nepYear));	  		
	  			
	  		}
	  	
	}
	
	
function engToNepali($s_engDate){
		
		
		//object of date class
   			$_dateConvert=new Date;
   			
		//initialization of variables
	  		$b_do_rough_calc=False;
			$s_engYear="";
			$s_engMonth="";
			$s_engDay="";
			$i_arrayIndex=0;
			$i_addDays=0;
			$s_sep="";
			$s_sysDateFormat="";
			
			
		if($s_engDate=="") return "00/00/00";
		
		
	  	// find the gregorian date that is the 1st of the nepali month	  		
	  	
			//test1
	  		//echo '$i_engd:'.$s_engDate."<br>";
			
	  		$s_sep=$this->_findSeparator($s_engDate);//Find the seperator used in the date
	  		$s_sysDateFormat=$this->_getSystemDateFormat();//find the system date format
			
			//Extract  year month and day from the english date
	  		$s_date = explode($s_sep, $s_engDate);
			if($s_sysDateFormat=="d/m/Y"){			
				$s_engYear=$s_date[2];
				$s_engMonth=$s_date[1];
				$s_engDay=$s_date[0];
			}
			elseif($s_sysDateFormat=="m/d/Y"){
				$s_engYear=$s_date[2];
				$s_engMonth=$s_date[0];
				$s_engDay=$s_date[1];
			}
			elseif($s_sysDateFormat=="Y/m/d"){
				$s_engYear=$s_date[0];
				$s_engMonth=$s_date[1];
				$s_engDay=$s_date[2];
			
			}
			//echo $i_engYear;
			//echo $i_engMonth;
			//echo $i_engDay;
			
			if(checkdate($s_engMonth,$s_engDay,$s_engYear)==false){
			echo "Invalid English Date"; 
			return "00/00/00";	
		}
			
			
	  		$i_nepDays=array();
	  		$i_nepDays=$this->getAll();//loads all the mapping gregorian days into an array from the database
			
			// find out the gregorian day for the first of nepali month
			$i_arrayIndex=(($s_engYear-$i_nepDays[1])+57)*12;			
			$i_arrayIndex+=$s_engMonth-2;
			
			//test2
			//echo '$iarray index'.$i_arrayIndex.'<br>';
			
			
				  		 	
	  		if(($i_arrayIndex+1)>sizeOf($i_nepDays))		  		 	
	  			$b_do_rough_calc=true;
	  		elseif($i_arrayIndex<2)
	  			$b_do_rough_calc=true;
	  			
	  			
	  			
			If (!$b_do_rough_calc){		  			
  				$i_gregorianFirstDayNepali=$i_nepDays[$i_arrayIndex];
  			
  				If ($i_gregorianFirstDayNepali<=$s_engDay){
					//compare gregorian date on first of nepali month with $d_english_date
					$s_tempNepDay=$s_engDay-$i_gregorianFirstDayNepali+1;
				
					If ($s_engMonth>=4){ //if english month is greater than or equal to 4												
										
						$s_tempNepMonth=$s_engMonth-3;
						$s_tempNepYear=$s_engYear+57;
						
					}
					Else {	//if < 4
													
						$s_tempNepMonth=$s_engMonth+9;
						$s_tempNepYear=$s_engYear+56;
					}				
					
					
	  			}	// end of "if gregorian day for the first day of Nepali date is less than or equal to day of english date"
	  			Else{//if greater than day of english date

					
					If ($i_arrayIndex>2){
						
						$i_daysDiff=$i_nepDays[$i_arrayIndex-1]-$s_engDay;																	
						$nepDay=((mktime(0,0,0,$s_engMonth,$s_engDay,$s_engYear)-mktime(0,0,0,$s_engMonth-1,$s_engDay+$i_daysDiff,$s_engYear))/86400)+1;
						
							//test5
						//echo '$nepDay:'.$nepDay."<br>";
						
						$s_tempNepDay=round($nepDay);
						If ($s_engMonth>=5){						
							
							$s_tempNepMonth=$s_engMonth-4;
							$s_tempNepYear=$s_engYear+57;
						}
						Else {							
							
							$s_tempNepMonth=$s_engMonth+8;
							$s_tempNepYear=$s_engYear+56;
						}
						
						//echo '$tempNepMonth:'.$s_tempNepMonth."<br>";
						//echo '$tempNepMonth:'.$s_tempNepYear."<br>";
						
					}
					Else{						
						
						$b_do_rough_calc=True;	
							
					}
					
				}// end of "if gregorian day is greater than day of english date"
	  			
	  		}	//end of "don't do rough calculation"
	  					
			if($b_do_rough_calc){			
			echo $s_engDay .$s_engMonth .$s_engYear;
				$i_addDays=243+15+17+(365*56);
				return $_dateConvert->format($s_sysDateFormat,$_dateConvert->_mktime(0,0,0,$s_engMonth,$s_engDay+$i_addDays,$s_engYear));	  				  			  		
	  			
			}	  	
			else{
				
                $s_tempNepMonth = sprintf("%02d",$s_tempNepMonth);
                $s_tempNepDay = sprintf("%02d",$s_tempNepDay);
                $s_tempNepYear = sprintf("%04d",$s_tempNepYear);
                
				if($s_sysDateFormat=="d/m/Y")
					return $s_tempNepDay."/".$s_tempNepMonth."/".$s_tempNepYear;
				elseif($s_sysDateFormat=="m/d/Y")
					return $s_tempNepMonth."/".$s_tempNepDay."/".$s_tempNepYear;
				elseif($s_sysDateFormat=="Y/m/d")
					return $s_tempNepYear."/".$s_tempNepMonth."/".$s_tempNepDay;				
			}
		 	
   	}
/*
function engToNepali($s_engDate){
		//$s_endDate="15/04/2065"
		
		//object of date class
   			$_dateConvert=new Date;
   			
		//initialization of variables
	  		$b_do_rough_calc=False;
			$s_engYear="";
			$s_engMonth="";
			$s_engDay="";
			$i_arrayIndex=0;
			$i_addDays=0;
			$s_sep="";
			$s_sysDateFormat="";
			
			
		if($s_engDate=="") return "00/00/00";
		
		
	  	// find the gregorian date that is the 1st of the nepali month	  		
	  	
			//test1
	  		//echo '$i_engd:'.$s_engDate."<br>";
			
	  		$s_sep=$this->_findSeparator($s_engDate);//Find the seperator used in the date
	  		$s_sysDateFormat=$this->_getSystemDateFormat();//find the system date format
			
			//Extract  year month and day from the english date
	  		$s_date = explode($s_sep, $s_engDate);
			if($s_sysDateFormat	==	"d/m/Y"){			
				$s_engYear=$s_date[2];
				$s_engMonth=$s_date[1];
				$s_engDay=$s_date[0];
			}
			elseif($s_sysDateFormat	==	"m/d/Y"){
				$s_engYear=$s_date[2];
				$s_engMonth=$s_date[0];
				$s_engDay=$s_date[1];
			}
			elseif($s_sysDateFormat	==	"Y/m/d"){
				$s_engYear=$s_date[0];
				$s_engMonth=$s_date[1];
				$s_engDay=$s_date[2];
			
			}
			//echo $i_engYear;
			//echo $i_engMonth;
			//echo $i_engDay;
			
			if(checkdate($s_engMonth,$s_engDay,$s_engYear)==false){
			echo "Invalid English Date"; 
			return "00/00/00";	
		}
			
			
	  		$i_nepDays=array();
	  		$i_nepDays=$this->getAll();//loads all the mapping gregorian days into an array from the database
			
			// find out the gregorian day for the first of nepali month
			$i_arrayIndex=(($s_engYear-$i_nepDays[1])+57)*12;			
			$i_arrayIndex+=$s_engMonth-2;
			
			//test2
			//echo '$iarray index'.$i_arrayIndex.'<br>';
			
			
				  		 	
	  		if(($i_arrayIndex+1)>sizeOf($i_nepDays))		  		 	
	  			$b_do_rough_calc=true;
	  		elseif($i_arrayIndex<2)
	  			$b_do_rough_calc=true;
	  			
	  			
	  			
			If (!$b_do_rough_calc){		  			
  				$i_gregorianFirstDayNepali=$i_nepDays[$i_arrayIndex];
  			
  				If ($i_gregorianFirstDayNepali<=$s_engDay){
					//compare gregorian date on first of nepali month with $d_english_date
					$s_tempNepDay=$s_engDay-$i_gregorianFirstDayNepali+1;
				
					If ($s_engMonth>=4){ //if english month is greater than or equal to 4												
										
						$s_tempNepMonth=$s_engMonth-3;
						$s_tempNepYear=$s_engYear+57;
						
					}
					Else {	//if < 4
													
						$s_tempNepMonth=$s_engMonth+9;
						$s_tempNepYear=$s_engYear+56;
					}				
					
					
	  			}	// end of "if gregorian day for the first day of Nepali date is less than or equal to day of english date"
	  			Else{//if greater than day of english date

					
					If ($i_arrayIndex>2){
						
						$i_daysDiff=$i_nepDays[$i_arrayIndex-1]-$s_engDay;																	
						$nepDay=((mktime(0,0,0,$s_engMonth,$s_engDay,$s_engYear)-mktime(0,0,0,$s_engMonth-1,$s_engDay+$i_daysDiff,$s_engYear))/86400)+1;
						
							//test5
							//echo '$nepDay:'.$nepDay."<br>";
						
						$s_tempNepDay=$nepDay;
						If ($s_engMonth>=5){						
							
							$s_tempNepMonth=$s_engMonth-4;
							$s_tempNepYear=$s_engYear+57;
						}
						Else {							
							
							$s_tempNepMonth=$s_engMonth+8;
							$s_tempNepYear=$s_engYear+56;
						}
						
						//echo '$tempNepMonth:'.$s_tempNepMonth."<br>";
						//echo '$tempNepMonth:'.$s_tempNepYear."<br>";
						
					}
					Else{						
						
						$b_do_rough_calc=True;	
							
					}
					
				}// end of "if gregorian day is greater than day of english date"
	  			
	  		}	//end of "don't do rough calculation"
	  		
			
			
			if($b_do_rough_calc){			
			echo $s_engDay .$s_engMonth .$s_engYear;
				$i_addDays=243+15+17+(365*56);
				return $_dateConvert->format($s_sysDateFormat,$_dateConvert->_mktime(0,0,0,$s_engMonth,$s_engDay+$i_addDays,$s_engYear));	  				  			  		
	  			
			}	  	
			else{
				
				if($s_sysDateFormat=="d/m/Y")
					return $s_tempNepDay."/".$s_tempNepMonth."/".$s_tempNepYear;
				elseif($s_sysDateFormat=="m/d/Y")
					return $s_tempNepMonth."/".$s_tempNepDay."/".$s_tempNepYear;
				elseif($s_sysDateFormat=="Y/m/d")
					return $s_tempNepYear."/".$s_tempNepMonth."/".$s_tempNepDay;
	
				
			}
		 	
   	}
*/





//change the input and display format of the day
	function _getSystemDateFormat(){
	/*
		//Finds out the system date format and returns it .
		//get the system date
		$s_date=date("d/m/Y");//get the system date 

		//break down the date into three parts mm.dd.yy or dd.mm.yy or yy.mm.dd
		$as_sysDate=explode($this->_findSeparator($s_date),$s_date);
	
		//it returns the system date in associative array form
		$as_date=getdate();
		
		//get the day month and year from t he array.
		$s_day=(int)$as_date['mday'];
		$s_month=(int)$as_date['mon'];
		$s_year=(int)$as_date['year'];
		
		//echo $s_day;
		//echo $s_month;
		//echo $s_year;
	
		if ($as_sysDate[0]==$s_day)
			$s_format="d";	
		else if($as_sysDate[0]==$s_month)
			$s_format="m";
		else if($as_sysDate[0]==$s_year)
			$s_format="Y";
		//echo $s_format;
	
		if ($as_sysDate[1]==$s_day)
			$s_format=$s_format."/d";	
		else if($as_sysDate[1]==$s_month)
			$s_format=$s_format."/m";
		else if($as_sysDate[1]==$s_year)
			$s_format=$s_format."/Y";		
		//echo $s_format;
		
		if ($as_sysDate[2]==$s_day)
			$s_format=$s_format."/d";	
		else if($as_sysDate[2]==$s_month)
			$s_format=$s_format."/m";
		else if($as_sysDate[2]==$s_year)
			$s_format=$s_format."/Y";
		
		//echo $s_format;
		return $s_format;
	*/
		
		$s_format="Y/m/d";
		
		return $s_format;
	}
	
}
?>