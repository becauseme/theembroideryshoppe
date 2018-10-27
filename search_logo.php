<?php
require_once './app/Mage.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
Mage::app();
$search_engine['options']['input'] = htmlentities($_GET['input']) ;
	$search_engine['options']['search_limit'] = isset($_GET['limit']) ? (int) $_GET['limit'] : 8;
	$search_engine['options']['input_len'] = strlen($search_engine['options']['input']) ;
	$searchCat_name = 'Countries' ;
$keyword='';
$where='';
if(isset($_GET['input']) && $_GET['input']!=''){
 $keyword=trim($_GET['input']);
 $where="and title like '%".$keyword."%' or companyname like '%".$keyword."%'";
}

$write = Mage::getSingleton('core/resource')->getConnection('core_write');
$readresult=$write->query("select * from uploadlogo where status='1' $where order by title ASC");
//echo "<pre>";
//print_r($readresult);


$i=1;
 $mediaUrl=Mage::getBaseUrl('media');
while($row = $readresult->fetch()){
//$width='100';
//$height='100';
if(!empty($row['filename']))
{
	 if(file_exists($mediaUrl.$row['filename']))
	 {
		list($width, $height, $type, $attr) = getimagesize("media/".$row['filename']);
		if($width > '200')
			{
				$width='200';
			}
		if($height > '110')
			{
				$height='110';
			}	
	  }
}
/*
$common_name='<div style="width:33%; float:left; height:220px">
<div class="item" >
<img src="'.$mediaUrl.$row['filename'].'" border="0" >'.$row['title'].' 
</div>
</div> ';
*/

$common_name=$row['title'];
$imageinfo='<img src="'.$mediaUrl.$row['filename'].'" width="80px">';

$id 			= $row['uploadlogo_id'] ;

// To display
			$results['value'] 		= utf8_encode($common_name);
			$results['info'] 		= utf8_encode($imageinfo) ;	
			$results['currency'] 	= utf8_encode('') ;	
		
		
			// Store result
			$search_engine['results'][$searchCat_name][$id] = $results ;
 }

//echo "<pre>";
//print_r($search_engine);

	#@ Format result 
	$aResults = array();
	$count = 0;
	$len = $search_engine['options']['input_len'] ;
	
	//_dumpLog("Search : START => '".$search_engine['options']['input']."'", 0) ;	
	if ( $len > 1)
	{
		#@ Count num results in all categories
		$num_total_results = 0 ;
		$num_plugins = count($search_engine['results']) ;
		$search_results = $search_engine['results'] ;
		@reset($search_results) ;
		while(list($cat, $val) = @each($search_results) ) {
			$num_total_results += count($search_results[$cat]) ;
		}	
		
		#@ If more than requested => take firsts of all categories
		if ( $num_total_results > $search_engine['options']['search_limit'] ) {
			$limit = floor($search_engine['options']['search_limit'] / $num_plugins) ;
		} else {
			$limit = $search_engine['options']['search_limit'] ;
		}			
		
		#@ Build results array
		@reset($search_results) ;
		$count = 0 ;
		while(list($cat, $val) = @each($search_results) )
		{			
			// Parse plugin results
			
			// Plugins headers
			if ( count($search_results) > 1 ) {
				$aResults[$count++] = array( "cat"=>($key) ,"value"=>$cat, "info"=>"plugin_header" );
			}				
			while (list($key, $field) = @each($val) ) {
				// had to use utf_decode, here
				// not necessary if the results are coming from mysql
				//
				
				$val[$key]['info'] = html_entity_decode($val[$key]['info']) ;
				$val[$key]['info'] = html_entity_decode($val[$key]['info']) ;
				
				$aResults[$count]['id'] = $key ;
				foreach($val[$key] as $key_field => $val_field) {
					$aResults[$count][$key_field] = addslashes(html_entity_decode($val_field)) ;
				}
				
				$count++;
				
				if ( $limit && $count==$limit)
					break;
				
			}
		}
	}	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	
	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\": \"".$aResults[$i]['info']."\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");
	
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		foreach($aResults as $entry) {
			echo "<rs" ;
			foreach($entry as $key => $val) {
				echo " $key=\"".$val."\"" ;
			}
			echo ">".$entry['value']."</rs>" ;
		}
		echo "</results>";
	}
	?>