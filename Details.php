<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GeneSeq-- Details</title>
<link href="CSS/oneColLiqCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body>

 <?php 
	function curPageURL() {
 		$pageURL = 'http';
 		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {$pageURL .= "s";}
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];//.$_SERVER["REQUEST_URI"];
 		} else {
  			$pageURL .= $_SERVER["SERVER_NAME"];//.$_SERVER["REQUEST_URI"];
 		}
 		return $pageURL;
	}
	$serverName = curPageURL();
	$sampleID = htmlspecialchars($_GET['sampleID']);
	$fusionID =  htmlspecialchars($_GET['fusionID']);
	$genome   = htmlspecialchars($_GET['genome']);
	$gene1=htmlspecialchars($_GET['Gene1']);
	$gene2=htmlspecialchars($_GET['Gene2']);
	$numInter=htmlspecialchars($_GET['numInter']);
	$fusionType= htmlspecialchars($_GET['fusionType']);
	$coordinates1 = htmlspecialchars($_GET['coordinates1']);
	$coordinates2 = htmlspecialchars($_GET['coordinates2']);
	$description1 = htmlspecialchars($_GET['description1']);
	$description2 = htmlspecialchars($_GET['description2']);
	$strand1      = htmlspecialchars($_GET['strand1'])=='F' ? '+' : '-';
	$strand2      = htmlspecialchars($_GET['strand2'])=='F' ? '+' : '-';
	$pairCount    = htmlspecialchars($_GET['pairCount']);
	$numIntra1    = htmlspecialchars($_GET['numIntra1']);
	$numIntra2    = htmlspecialchars($_GET['numIntra2']);
	$numExons1    = htmlspecialchars($_GET['numExons1']);
	$numExons2    = htmlspecialchars($_GET['numExons2']);
	 

$connect = mysql_connect("localhost", "root", "");
mysql_select_db("gene_db");
$query = mysql_query("SELECT * FROM  `gfr` WHERE numInter > 0"); 
WHILE ($rows = mysql_fetch_array($query)):
$readTrans1 = $rows['readsTranscript1'];
$readTrans2 = $rows['readsTranscript2'];

endwhile;

	
	function getCoordinates( $fileName ) {
		if (!file_exists($fileName)) {
		 	echo "Can't find: ".$fileName;
			return (NULL);
		} else {	
    		$fileH=fopen( $fileName, "r");
			$content = fread( $fileH, filesize($fileName) );
		}
		$minCoordinate=-1;
		$maxCoordinate=-1;
		foreach( explode("\n", $content) as $line) {
			if( strpos( $line, "chr" )===FALSE  ) continue;
			$locusCoordinates = explode( "\t", $line );
			$chrLocus = $locusCoordinates[0];
			if( ($minCoordinate==-1) or ($minCoordinate > $locusCoordinates[1]) ) 
				$minCoordinate = $locusCoordinates[1];
			if($maxCoordinate==-1 or $maxCoordinate < $locusCoordinates[2] ) 
				$maxCoordinate = $locusCoordinates[2];
		}
		$minCoordinate = $minCoordinate - 200;
 		$maxCoordinate = $maxCoordinate + 200;
		fclose( $fileH );	
		return( "$chrLocus:$minCoordinate-$maxCoordinate" );
		
	}
  	$locus1 = getCoordinates( "GFR/BED/".$fusionID."_1.bed" );
 	$locus2 = getCoordinates( "GFR/BED/".$fusionID."_2.bed" );
			  
?>
<script language="javascript">
function ShowHide(divId)
{
	if(document.getElementById(divId).style.display == 'none')
	{
	  document.getElementById(divId).style.display='block';
	  ( divId == 'HiddenDiv1' ) ? document.getElementById('HiddenA1').style.display='block' : document.getElementById('HiddenA2').style.display='block';
	}
	else
	{
	  document.getElementById(divId).style.display = 'none';
	  (divId == 'HiddenDiv1' ) ? document.getElementById('HiddenA1').style.display='none' : document.getElementById('HiddenA2').style.display='none';
	}
}

function SelectAll(textID) {
	document.getElementById(textID).select();
}


	function loadBed() {
		window.open("http://localhost:60151/load?file=<?=$serverName?>/FusionSeq/GFR/BED/<?=$fusionID?>_1.bed&genome=<?=$genome?>&merge=TRUE");
		window.open("http://localhost:60151/load?file=<?=$serverName?>/FusionSeq/GFR/BED/<?= $fusionID?>_2.bed&genome=<?=$genome?>&merge=TRUE&locus=<?=$locus1?>%20<?=$locus2?>&genome=<?=$genome?>");
		window.focus();
	}
	
</script>
	<div class="container">
   <div class="header"><h3>Sample: <?= $sampleID ?></h3>
   	
   	<span class="link" onclick="window.alert('This makes use of the listener port, which must be enabled. This option can be controlled on the Advanced preferences tab, and is enabled by default listening on port 60151');window.location='http://www.broadinstitute.org/igv/projects/current/igv.php?sessionURL=<?=$serverName?>/FusionSeq/fusionseq_<?=$genome?>_session.xml&genome=<?=$genome?>'" >
    <input type="submit" id="IGVstart" value="Start IGV" />This makes use of the listener port, which must be enabled. This option can be controlled on the Advanced preferences tab, and is enabled by default listening on port 60151.
   </span><br/>
  </div>
      
  <!-- end .header -->   
  <div class="content">
  
	<table name="title" width="100%">
<br />
<tr>
	<td width="40%" valign="middle">   
		<h3 style="text-align:left">Detail summary of <br /><?php echo $gene1."-".$gene2?></h3>
     </td>
     <td valign="middle"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" height=200 width="100%">
	 	<?php 
			$maxNumExons = max( $numExons1, $numExons2);
			$exonSize = 8; $spacer = 12; $sideMargin = 10;
			$imageWidth = $sideMargin + $maxNumExons * $spacer + ($maxNumExons - 1) * $exonSize  ;
			$imageHeigth = 200; 
			$yTranscript1 = 25; $yTranscript2 = 150; $fontSize = 12;
			$xEndCoord1 = $sideMargin + $numExons1 * $exonSize + (($numExons2 -1 ) * $spacer);
			$xEndCoord2 = $sideMargin + $numExons1 * $exonSize + (($numExons2 -1 ) * $spacer);
			
						
			foreach ( explode( "|", $pairCount ) as $pairCounts ) {
				$items = explode(",", $pairCounts );
				$strokeWidth = ($items[1]/$numInter);
					switch( $items[0] ) {
						case 1: // exon-exon
							$xCoord1 = $sideMargin + ($items[2] * $spacer) + $items[2] * $exonSize - $exonSize / 2;
							$xCoord2 = $sideMargin + $items[3] * $spacer + $items[3] * $exonSize - $exonSize / 2;
							$color = 'red';
							break;
						case 2: // exon-intron
							$xCoord1 = $sideMargin + $items[2] * $spacer + $items[2] * $exonSize - $exonSize / 2;
							$xCoord2 = $sideMargin + $items[3] * $spacer + $items[3] * $exonSize + $spacer / 2;
							$color = 'red';
							break;
						case 3: // exon-junction
							$xCoord1 = $sideMargin + $items[2] * $spacer + $items[2] * $exonSize - $exonSize / 2;
							$xCoord2 = $sideMargin + ($items[3]/2) * $spacer + ($items[3])/2 * $exonSize + ($items[3] % 2) * $spacer;
							$color = 'red';
							break;    
						case 4: // intron-exon
							$xCoord1 = $sideMargin + ($items[2] * $spacer) + ($items[2] * $exonSize) + ($spacer / 2);
							$xCoord2 = $sideMargin + ($items[3] * $spacer) + ($items[3] * $exonSize) - ($exonSize / 2);
							$color = 'green';
							break;
						case 5: // intron-intron
							$xCoord1 = $sideMargin + $items[2] * $spacer + $items[2] * $exonSize + $spacer / 2;
							$xCoord2 = $sideMargin + $items[3] * $spacer + $items[3] * $exonSize + $spacer / 2;
							$color = 'green';
							break;
						case 6: // intron-junction
							$xCoord1 = $sideMargin + $items[2] * $spacer + $items[2] * $exonSize + $spacer / 2;
							$xCoord2 = $sideMargin + ($items[3]/2) * $spacer + ($items[3])/2 * $exonSize + ($items[3] % 2) * $spacer;
							$color = 'green';
							break;
						case 7: // junction-junction
							$xCoord1 = $sideMargin + ($items[2]/2) * $spacer + ($items[2]) / 2 * $exonSize + ($items[2] % 2) * $spacer;
							$xCoord2 = $sideMargin + ($items[3]/2) * $spacer + ($items[3]) / 2 * $exonSize + ($items[3] % 2) * $spacer;
							$color = 'gray';
							break;
						case 8: // junction-exon
							$xCoord1 = $sideMargin + ($items[2]/2) * $spacer + ($items[2]) / 2 * $exonSize + ($items[2] % 2) * $spacer;
							$xCoord2 = $sideMargin + $items[3] * $spacer + $items[3] * $exonSize - $exonSize / 2;
							$color = 'gray';
							break;     
						case 9: // junction-intron
							$xCoord1 = $sideMargin + ($items[2]/2) * $spacer + ($items[2]) / 2 * $exonSize + ($items[2] % 2) * $spacer;
							$xCoord2 = $sideMargin + $items[3] * $spacer + $items[3] * $exonSize + $spacer / 2;
							$color = 'gray';
							break;     
						default:
							$xCoord1 = $sideMargin;
							$xCoord2 = $sideMargin;
							$color = 'white';
							break;
						}
						echo "<line x1=$xCoord1 x2=$xCoord2 y1=".($yTranscript1+($exonSize/2))." y2=".($yTranscript2+($exonSize/2))." stroke=$color style='stroke-width:".( $strokeWidth < 0.1 ? 0.1 : $strokeWidth)."' fill='url(grad1)'/>";
			}
			
			echo "<text x='0' y=".($yTranscript1 - $fontSize)." fill='blue' style='font-size:$fontSize'>$gene1</text>";
			echo "<line x1=$sideMargin y1='".($exonSize/2 + $yTranscript1)."' x2=".($xEndCoord1 + $exonSize + $spacer + $sideMargin)." y2='".($exonSize/2 + $yTranscript1)."' style='stroke:rgb(0,0,0);stroke-width:0.2'/>";
			
			echo "<text x='0' y=".($yTranscript2 + $exonSize + $fontSize*2)." fill='orange' style='font-size:$fontSize'>$gene2</text>";
			echo "<line x1=$sideMargin y1='".($exonSize/2 + $yTranscript2)."' x2=".($xEndCoord2 + $exonSize + $spacer + $sideMargin)." y2='".($exonSize/2 + $yTranscript2)."' style='stroke:rgb(0,0,0);stroke-width:0.2'/>";
			
			for ( $i=1; $i<=$numExons1; $i++) 
				echo "<rect x=".($sideMargin + $i * $spacer + ($i - 1) * $exonSize )." y=$yTranscript1 width=$exonSize height=$exonSize fill='black'/>";
			for ( $i=1; $i<=$numExons2; $i++) 
				echo "<rect x=".($sideMargin + $i * $spacer + ($i - 1) * $exonSize )." y=$yTranscript2 width=$exonSize height=$exonSize fill='black'/>";
			
			for ( $i=1; $i<=$maxNumExons; $i++)  
				echo "<text x=".($sideMargin + $i * $spacer + ($i - 1) * $exonSize )." y=".(($yTranscript2 - $yTranscript1)/2 + $yTranscript1 + $exonSize). " fill='black' style='font-size:9px'>$i</text>";
			
		?>
	</svg></td>
</tr>
</table>


<table  name="generalSummary"  width="100%">
  <tr>            
		<td width="35%" valign="top">
        <h1>Summary Information</h1>
        <table name="summaryInfo" class="tableBorder">
            <tr>
            	<td><h2>Identifier:</h2></td>
                <td align="right"><?= $fusionID; ?></td>
            </tr>
 			<tr>
            	<td><h2>Number of inter-transcript reads</h2></td>
                <td align="right"><?= $numInter; ?></td>
    		</tr>
            <tr>
  	          	<td><h2>Fusion type:</h2></td>
                <td align="right"><?= $fusionType; ?></td>
             </tr>
             <tr>
              <td><h2>Paired-end reads</h2></td>
              <td align="right"><a id="bed" href="" onclick="loadBed();">
               [IGV]</a>
               </td>
               
           </tr>
             <?php 
			 $minCoordinate=NULL;
			 $maxCoordinate=NULL;
			 $chrLocus=NULL;
			 if ( $fusionType == "read-through" or $fusionType == "intra" ): ?>
             <tr>
             <?php
			 	$gffFileName = "./GFR/GFF/".$fusionID.".gff";
				if (!file_exists($gffFileName)) {
					echo "Can't find: <a href=".$gffFileName;
				} else {	
					$gffH = fopen( $gffFileName, "r");
					$gffContent = fread( $gffH , filesize($gffFileName));
				
					$minCoordinate=-1;
					$maxCoordinate=-1;
					foreach ( explode("\n", $gffContent) as $line ) {
						if( strpos( $line, "chr" )===FALSE  ) continue;
						$locusCoordinates = explode( "\t", $line );
						$chrLocus = $locusCoordinates[0];
						if( ($minCoordinate==-1) or ($minCoordinate > $locusCoordinates[3]) ) 
							$minCoordinate = $locusCoordinates[3];
						if($maxCoordinate==-1 or $maxCoordinate < $locusCoordinates[4] ) 
							$maxCoordinate = $locusCoordinates[4];
					}
					$minCoordinate -= 150;
 					$maxCoordinate += 150;
					fclose( $gffH );
				}
			?>
  	          	<td><h2>Connected reads:</h2></td>
                <td align="right"><a href="http://localhost:60151/load?file=<?=$serverName?>/FusionSeq/GFR/GFF/<?= $fusionID?>.gff&locus=<?=$chrLocus?>:<?=$minCoordinate?>-<?=$maxCoordinate?>&genome=<?=$genome?>&merge=TRUE">
               Connected reads</a></td>
             </tr>
             <?php endif; ?>
			
        <tr>
   		<td colspan="2">
        <h1>Transcript Connectivity Table</h1>
       	  <table class="tableBorder" name="transcript connectivity table" cellpadding="0" width="100%" >
            <thead>
           		<th width="30%"><h2>Pair-type</h2></th>
                <th width="25%"><h2>Entry transcript 1</h2></th>
                <th width="25%"><h2>Entry transcript 2</h2></th>
                <th width="20%"><h2>Counts</h2></th>
            </thead>
            <tbody>

<?php /*?>  if( flag==1 ) {
    int rem = number % 2;
    if( rem == 0 )
      stringPrintf(str, "%d right", number/2);
    else 
      stringPrintf(str, "left %d", number/2+1);
  } else {
    stringPrintf(str, "%d", number);
  }
<?php */?>  
	
            <?php 
				foreach ( explode( "|", $pairCount ) as $element ) {
					echo "<tr>";
                		$items = explode(",", $element );
						switch( $items[0] ) {
							case 1:
								echo "<td>exon-exon</td>";
								break;
							case 2:
								echo "<td>exon-intron</td>";
								break;
							case 3:
								echo "<td>exon-boundary</td>";
								$num = $items[3]/2;
								break;
							case 4:
								echo "<td>intron-exon</td>";
								break;
							case 5:
								echo "<td>intron-intron</td>";
								break;
							case 6:
								echo "<td>intron-boundary</td>";
								$items[3] = $items[3]/2;
								break;
							case 7:
								echo "<td>boundary-boundary</td>";
								$items[2] = $items[2]/2;
								$items[3] = $items[3]/2;
								break;
							case 8:
								echo "<td>boundary-exon</td>";
								$items[2] = $items[2]/2;
								break;
							case 9:
								echo "<td>boundary-intron</td>";
								$items[2] = $items[2]/2;
								break;
						}
                	echo "<td>$items[2]</td><td>$items[3]</td><td>$items[1]</td></tr>";
			 	} ?>
            </tbody>    
          </table>
          </td>
          </tr>
          </table>
          </td>
        <td width="65%" align="center">
           <h2>Transcripts information</h2>
    	<table cellpadding="3" class="tableBorder" id="transcriptInfo" width="98%" >
    	<thead>
        	<th width="20%">&nbsp;</th>
            <th style="color:#00F" width="40%">Transcript 1</th>
            <th style="color:#F93" width="40%">Transcript 2</th>
        </thead>
        <tbody class="tableBorder" style="valign:middle">
        	<tr><td><h5 style="vertical-align:middle; padding:5px; margin:5px">Gene Symbol(s)</h5></td><td><?= $gene1?></td><td><?= $gene2?></td></tr>
            <tr>
              <td><h5>Coordinates</h5></td><td><a href="http://localhost:60151/goto?locus=<?=$coordinates1?>&genome=<?=$genome?>"><?= $coordinates1?></a>&nbsp;<a href='http://genome.ucsc.edu/cgi-bin/hgTracks?db=<?=$genome?>&clade=vertebrate&org=human&position=<?=$coordinates1?>' target='_blank'>[UCSC]</a></td><td><a href="http://localhost:60151/goto?locus=<?=$coordinates2?>"><?= $coordinates2 ?></a>&nbsp;<a href='http://genome.ucsc.edu/cgi-bin/hgTracks?db=<?=$genome?>&clade=vertebrate&org=human&position=<?=$coordinates2?>' target='_blank'>[UCSC]</a></td></tr>
            <tr>
              <td><h5>Strand</h5></td><td><?= $strand1 ?></td><td><?= $strand2?></td></tr>
            <tr>
              <td><h5>Gene Description(s)</h5></td><td><?php foreach( explode("|", $description1) as $item) echo "$item<br/>";?></td><td><?php foreach( explode("|", $description2) as $item) echo "$item<br/>"; ?></td></tr>
            <tr>
              <td><h5>Number of exons</h5></td><td><?=$numExons1 ?></td><td><?= $numExons2 ?></td></tr>
            <tr>
              <td><h5>Number of intra-PE reads</h5></td><td><?= $numIntra1?></td><td><?= $numIntra2?></td></tr>
            
           <tr>
              <td><h5>Fasta</h5></td>
                <td valign="top">
            <a onclick="javascript:ShowHide('HiddenDiv1')" href="javascript:;" >Show/Hide</a>
            <a onclick="javascript:SelectAll('HiddenDiv1')" href="javascript:;" id="HiddenA1" style="display:none">Select all</a>
			<?php
					$buffer = NULL;
					$readLength=0;
					foreach( explode("|", $readTrans1 ) as $key => $item) { 
						$buffer.=">$fusionID"."_1_$key\n$item\n";
						if( $readLength == 0 ) $readLength = strlen( $item );
					}
				?>
			<textarea name="" cols=<?=$readLength?> rows=<?=$numInter > 25 ? 50 : ($numInter * 2) ?> readonly="readonly" class="content" id="HiddenDiv1" style="Display: none;font-family:Courier New, Courier, monospace; font-size: 11px" width="100%" ><?=$buffer?></textarea>
            
            </td>
            <td valign="top">
            <a onclick="javascript:ShowHide('HiddenDiv2')" href="javascript:;" >Show/Hide</a>
            <a onclick="javascript:SelectAll('HiddenDiv2')" href="javascript:;" id="HiddenA2" style="display:none">Select all</a>
			<?php
					$buffer = NULL;
					$readLength=0;
					foreach( explode("|", $readTrans2 ) as $key => $item) { 
						$buffer.=">$fusionID"."_2_$key\n$item\n";
						if( $readLength == 0 ) $readLength = strlen( $item );
					}
				?>
			<textarea name="" cols=<?=$readLength?> rows=<?=$numInter > 25 ? 50 : ($numInter * 2) ?> readonly="readonly" class="content" id="HiddenDiv2" style="Display: none; font-family:Courier New, Courier, monospace; font-size:11px"" width="100%" ><?=$buffer?></textarea>
            
            </td>
           </tr>
    </table>
        </td>
	</tr>
  </table>
   	<p>

  		
  <!-- end .content --></div>
  
  <div class="footer">
    <p>GeneSeq TR 2013</p>
    <!-- end .footer --></div>
  <!-- end .container --></div>
  </body>
</html>
