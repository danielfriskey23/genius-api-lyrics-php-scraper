<style>
body {
background-color: grey;	
}

</style>
<!-- LYRIC API START -->

<center>
<br><br>
<form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="getLyrics();" >
<input type='text' id="lyrics_search" name='lyrics_search' value="" size="16" autocomplete="off" required>
<input type="submit" value="Submit Form" name="submitlyrapi" /><br>
</form>
<!-- YOU JUST SEARCHED FOR:<input type="text" id="searchhistory" name="searchhistory"  size="20" readonly /> -->
</center>


<?php 
if(isset($_POST['submitlyrapi'])) 
{ 
$searchterm = $_POST['lyrics_search'];

error_reporting(0);
ini_set('display_errors', 0);

$new = str_replace(' ', '%20', $searchterm);
//echo $new;

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => "https://genius.p.rapidapi.com/search?q='.$new.'" ,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"x-rapidapi-host: genius.p.rapidapi.com",
		"x-rapidapi-key: ea76df7a46mshe0fcb6f748a0438p129582jsn3ea571766d39"
	),
));
$response = curl_exec($curl);
curl_close($curl);
$thelyrics = json_decode($response);
$lyricresponse = $thelyrics->response->hits[0]->result->path;
//echo $lyricresponse;
$lyricsTitle = $thelyrics->response->hits[0]->result->full_title;
//echo $lyricsTitle
$lyricsImage = $thelyrics->response->hits[0]->result->header_image_thumbnail_url;
//echo $lyricsImage
$urlfront = 'https://genius.com';
$completeurl = $urlfront.$lyricresponse;
//echo $completeurl;
$doc = new DOMDocument;
$doc->preserveWhiteSpace = false;
$doc->strictErrorChecking = true;
$doc->recover = true;
$doc->loadHTMLFile($completeurl);
$xpath = new DOMXPath($doc);
$query = "//div[@class='lyrics']";
$entries = $xpath->query($query);
$var = $entries->item(0)->textContent;
//echo $var;
function innerHTML(DOMNode $node)
{
  $doc2 = new DOMDocument();
  foreach ($node->childNodes as $child) {
    $doc2->appendChild($doc2->importNode($child, true));
  }
  return $doc2->saveHTML();
}
foreach ($entries as $node) {
    $full_content = innerHTML($node);
   //echo $full_content;
   echo '<center><font color="black">';
   echo '<a href="'.$completeurl.'" target="_blank" class="lyrictitle">'.$lyricsTitle.'</a>';
   echo '<br>';
   echo '<img src="'.$lyricsImage.'" id="album_thumbnail" width="225px" height="auto"><br>';
   echo strip_tags($full_content, '<br><div>');
   echo '</font></center>';
   echo '<br>';
} 
}
?>
<!-- LYRIC API END -->
