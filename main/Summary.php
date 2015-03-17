<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GeneSeq -- Result Summary</title>
<link href="CSS/oneColLiqCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container">  
  </p></div>
    <p>
<table width="95%" align="center" class="tableBorder" name="results" cellpadding="5px">
   	<thead align="center" >
        <th width="5%" class='content'>Samp_ID</th>
     	<th width="5%" class='content'>SPER</th>
        <th width="5%" class='content'>DASPER</th>
        <th width="5%" class='content'>RESPER</th>
        <th width="5%" class='content'>Number of inter-paired reads</th>
    	<th width="5%" class='content'>Fusion type</th>
    	<th width="5%" class='content'>Genomic coordinates</th>
        <th width="10%" class='content'>Gene symbol </th>
        <th width="20%" class='content'>Description </th>
    	<th width="5%" class='content'>Genomic coordinates</th>
        <th width="10%" class='content'>Gene symbol </th>
        <th width="20%" class='content'>Description</th>
		<th width="5%" class="content">Details</th>
     </thead> 
<?php
	
$Gene1   = htmlspecialchars($_POST['Gene1']);
$Gene2   = htmlspecialchars($_POST['Gene2']);
$connect = mysql_connect("localhost", "root", "");


mysql_select_db("gene_db");
If (strlen($Gene2) == 0) {
$query = mysql_query("SELECT * FROM  `gfr` WHERE numInter > 0 AND geneSymbolTranscript1 = '$Gene1' or geneSymbolTranscript2 = '$Gene1' "); }
else 
{$query = mysql_query("SELECT * FROM  `gfr` WHERE numInter > 0 AND (geneSymbolTranscript1 = '$Gene1' AND geneSymbolTranscript2 = '$Gene2') or (geneSymbolTranscript2 = '$Gene1' AND geneSymbolTranscript1 = '$Gene2') ");}

WHILE ($rows = mysql_fetch_array($query)):
   $Id = $rows['id'];
   $Sper = $rows['SPER'];	
   $Dasp = $rows['DASPER'];	
   $Resp = $rows['RESPER'];	
   $Int_pairs = $rows['numInter'];	
   $Fus = $rows['fusionType'];	
   $Gene_cor1 = $rows['chromosomeTranscript1'];	
   $Gene_sym1 = $rows['geneSymbolTranscript1'];	
   $Desc1 = $rows['descriptionTranscript1'];	
   $Gene_cor2 = $rows['chromosomeTranscript2'];	
   $Gene_sym2 = $rows['geneSymbolTranscript2'];	
   $Desc2 = $rows['descriptionTranscript2'];
   $Start1 =   $rows['startTranscript1'];
   $Start2 =  $rows['startTranscript2'];
   $End1 =  $rows['endTranscript1'];
   $End2 =  $rows['endTranscript2'];
   $Pair_ct = $rows['pairCount'];
   $Exon1 =$rows ['numExonsTranscript1'];
   $Exon2 =$rows ['numExonsTranscript2'];
   $Num_int1 = $rows ['numIntra1'];
   $Num_int2 = $rows ['numIntra2'];
   $strand1 = $rows ['strandTranscript1'];
   $strand2 = $rows ['strandTranscript2'];
   $readTrans1 = $rows['readsTranscript1'];
   $readTrans2 = $rows['readsTranscript2'];

 
if (strlen($Gene1) == 0) 
echo "No fusion Transcript avaible";       



   
$sampleID = preg_replace("/_[0-9]{0,5}$/", "",$Id);   
echo "<tr width=60% class='content'>";
echo "<td class='content'>$sampleID<br></td>";
echo "<td class='content'>$Sper<br></td>";
echo "<td class='content'>$Dasp<br></td>";
echo "<td class='content'>$Resp<br></td>";
echo "<td class='content'>$Int_pairs<br></td>";
echo "<td class='content'>$Fus<br></td>";
echo "<td class='content'>$Gene_cor1: $Start1-$End1<br></td>";
echo "<td class='content'>$Gene_sym1<br></td>";
echo "<td class='content'>$Desc1<br></td>";
echo "<td class='content'>$Gene_cor2:$$Start1-$End1<br></td>";
echo "<td class='content'>$Gene_sym2<br></td>";
echo "<td class='content'>$Desc2<br></td>";
echo "<td class='content'><a href='Details.php?sampleID=".$sampleID."&genome=hg18"."&fusionID=".$Id."&numInter=".$Int_pairs."&Gene1=".$Gene_sym1."&Gene2=".$Gene_sym2."&fusionType=".$Fus."&coordinates1=".$Gene_cor1."&coordinates2=".$Gene_cor2."&pairCount=".$Pair_ct."&strand1=".$strand1."&strand2=".$strand2."&numExons1=".$Exon1."&numExons2=".$Exon2."&numIntra1=".$Num_int1."&numIntra2=".$Num_int2."&description1=".$Desc1."&description2=".$Desc2."';>Details</a></td></tr>";


endwhile; 
 
?>
</table>
</p>

    <!-- end .content --></div>
  <div class="footer">
    <p>GeneSeq &copy;2013</p>
    <!-- end .footer --></div>
  <!-- end .container --></div>

</body>
</html>

