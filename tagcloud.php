<?php
/**
 * Template Name: tagcloud
 * */
?>

<?php

header('Content-type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
print_r($_POST);
print_r($data);

//echo sizeof($data);
if(sizeof($_POST)==0)
{ echo json_encode("Status: No arguments passed")."\n";
}

else if(sizeof($_POST)<3)
{ echo json_encode("Status: Enter all the arguments")."\n";
}

else if (sizeof($_POST)>3)
{ echo json_encode("Status: Only 3 arguments required")."\n";
}

else 
{

error_reporting(0);

$titlereview = '';
$title_review = '';
$array_exp = '';
$stopwords = '';
$numOfWords = '';
$show_result = '';


$titlereview = $_POST['contents'];
$numOfWords = $_POST['numofwords'];
$stop_words = explode(",", $_POST['stop_words']);

foreach($stop_words as $make_stp_words){
	$filter_words[] = $make_stp_words;
}

$sentence = strtolower($titlereview);
$array_exp = explode(" ", $sentence);
$stopwords = $filter_words;
$array_imp = array_diff($array_exp, $stopwords);
$title_review = implode($array_imp, " ");

// Store frequency of words in an array
$freqData = array(); 
 
// Get individual words and build a frequency table
foreach( str_word_count( $title_review, 1 ) as $word )
{
	// For each word found in the frequency table, increment its value by one
	array_key_exists( $word, $freqData ) ? $freqData[ $word ]++ : $freqData[ $word ] = 0;	
}
 
// ---------------------------------------------------------------
// Function to actually generate the cloud from provided data
// ---------------------------------------------------------------
function getCloud( $data = array(), $numOfWords, $minFontSize = 12, $maxFontSize = 30 )
{
	$i = 0;
	$minimumCount = min( array_values( $data ) );
	$maximumCount = max( array_values( $data ) );
	$spread       = $maximumCount - $minimumCount;
	$cloudHTML    = '';
	$cloudTags    = array();
//echo "<pre>"; print_r($maximumCount);exit;
	//$spread == 0 && $spread = 1;
	arsort($data);
	foreach( $data as $tag => $count )
	{  if($i<$numOfWords){
			$size = $minFontSize + ( $count - $minimumCount ) 
				* ( $maxFontSize - $minFontSize ) / $spread;
			$cloudTags[] = htmlspecialchars( stripslashes( $tag ));
		}else{
			break;
		}
	$i++;	
	}
	return json_encode($cloudTags);
}	

$show_result = getCloud($freqData, $numOfWords);

if (sizeof($show_result)==0)
   echo json_encode("Status: Error in generating Tag Cloud")."\n";
else
   echo $show_result;
   echo "\n"; 


}
?>
