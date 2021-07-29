<?php
//Extract links in paginations and save in .txt format...
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$k=1;
$p_lastnumber=2;

for ($i = 1; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('https://www.lantmannen.com/newsroom/press-releases/?page='.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[@class="callout-link"]'); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

		foreach ($newsList as $news) {
			$href = "https://www.lantmannen.com".$news->getAttribute('href');	
			
			$html = file_get_contents ($href);
			@$tmp_news->loadHTML($html);
			$xpath2 = new DOMXPath($tmp_news);
			
			$title0		= $xpath2->query('//h1[@itemprop="title"]'); 
			$mainText0	= $xpath2->query('//div[@class="newspage-wrap"]'); 
			$data0		= $xpath2->query('//span[@class="newspage-meta-publishdate"]');
			
			$title		= $title0[0]->nodeValue; 
			$mainText	= $mainText0[0]->nodeValue; 
			$data		= $data0[0]->nodeValue;
			
			$content= $data.' '.$title.' '.$mainText;
			
			$date = DateTime::createFromFormat('m/d/Y', trim($data));
			
		
			$filename = "lantmannen/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
			file_put_contents($filename, $content);
			echo $filename." islem tamam! <br>";
			$k=$k+1;	

		}
}


?>

</body>

</html>
