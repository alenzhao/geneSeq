index.php

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="generator" content="Adobe GoLive" />
		<title>GeneSeq</title>
		<link href="CSS/oneColLiqCtrHdr.css" rel="stylesheet" type="text/css" />
					
	<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.--><script>var __adobewebfontsappname__="dreamweaver"</script><script src="http://use.edgefonts.net/aguafina-script:n4:default.js" type="text/javascript"></script>
</head>

	 <body>
			<img src="background.jpg" alt="" height="210" width="100%" border="0" />
			<div id="title">
			  <h3 align="center">GeneSeq</h3>
			</div>
  <form action="Summary.php" method="post" name="sampleDetail" target="_self" class="inp" AUTOCOMPLETE="off">       
 <h1>
 
<!--Input Gene 1-->

	<label for="sampleID">Gene 1: </label>
              <input type="text" name='Gene1' id='sampleID' list="samp"> </input><br>
             <datalist id="samp">
 
<?php
$connect = mysql_connect("localhost", "root", "");


mysql_select_db("gene_db");
$query = mysql_query("SELECT * FROM  `gene_name` ORDER BY  `gene_name`.`SYMBOL` ASC LIMIT 30 , 29501");
WHILE ($rows = mysql_fetch_array($query)):
   $Symb = $rows['SYMBOL'];  
  
echo "<option value=$Symb>$Symb</option> <br>";
endwhile;


 
 ?>
</datalist>
</h1>

<!--Input Gene 2-->
<h1>

 <label for="sampleID">Gene 2: </label>
              <input type="text" name='Gene2' id='Gene2' list="samp2"></input><br>
             <datalist id="samp2">
<?php

$connect = mysql_connect("localhost", "root", "");



mysql_select_db("gene_db");
$query = mysql_query("SELECT * FROM  `gene_name` ORDER BY  `gene_name`.`SYMBOL` ASC LIMIT 30 , 29501");
WHILE ($rows = mysql_fetch_array($query)):
   $Symb = $rows['SYMBOL'];  
  
echo "<option value=$Symb>$Symb</option> <br>";
 
endwhile; 
 
?>
</datalist>

<input type="submit" class="button" >
</h1>
</form><img src="back.jpg" alt="" height="500" width="100%" border="0" />		
</body>
