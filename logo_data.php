<?php
require_once './app/Mage.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
Mage::app();

if($_GET)
{
$page=$_GET['page'];
}
$per_page = 9; 
$start = ($page-1)*$per_page;
$keyword='';
$where='';
if(isset($_GET['keyword']) && $_GET['keyword']!=''){
 $keyword=trim($_GET['keyword']);
 $where="and title like '%".$keyword."%' or companyname like '%".$keyword."%'";
}

$write = Mage::getSingleton('core/resource')->getConnection('core_write');
$readresult=$write->query("select * from uploadlogo where status='1' $where order by title ASC limit $start,$per_page");


?>

<table width="800px" border="0" cellpadding="0" cellspacing="2">
<tr> <td align="center"   valign="top"> 
<?php
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
?>

<div style="width:33%; float:left; height:220px">
<div class="item" > 
<img src="<?php echo $mediaUrl.$row['filename']; ?>" border="0" >
</div>
<?php echo $row['title']; ?> 
</div>





<?php } ?>								
                                              
        </td>                      </tr>
              <tr>
                <td colspan="3" >&nbsp;</td>
              </tr>
              

              
              <!-- <tr>
			  
			  			  </tr>-->
            </table>
            <style>
			
			.item {
     width: 150px;
    height: 150px;    
   
   
    margin: 10px;
    text-align: center;
    line-height: 135px;
}
			.item img {
    max-width: 100%;
    max-height: 100%;
    vertical-align: middle;
}


.newp{
width:200px;; 

}
			</style>