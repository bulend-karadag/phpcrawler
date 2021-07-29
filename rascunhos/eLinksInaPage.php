<?php
//extract links in a single page and save in .xml format...
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

$target_website='https://www.tekfentarim.com/news';
$company='tekfentarim';
$department='news';
$filenameXML='tekfentarim.xml';
$rootForLinks='https://www.tekfentarim.com';
$cssTagForLinks='//a[@class="ustCizik"]';
$cssTagForTitle='//div[@class="title-tekfen pt-md-1"]';
$cssTagForContent='//div[@class="col-md-12"]';
$cssTagForData='//time[@class="date-container minor-meta updated"]';

$k=1;
	$xml = new XMLWriter;
	$xml->openURI($filenameXML);
	$xml->setIndent(true);
	$xml->startDocument('1.0', 'UTF-8');
	$xml->startElement('root');
	
	
	$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);

		$news = new DOMDocument;
		$url= file_get_contents ($target_website, false, $context);
		@$news->loadHTML($url);
		$xpath = new DOMXPath($news);
		$newsList = array();
		$newsList = $xpath->query($cssTagForLinks); 

?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

$tmp_news = new DOMDocument(); 

		foreach ($newsList as $news) {	
			$href = $rootForLinks.$news->getAttribute('href');	
				if(strpos($href, '#') == false){ 
					echo $href.'</br>';
					$html = file_get_contents ($href, false, $context);
					@$tmp_news->loadHTML($html);
					$xpath2 = new DOMXPath($tmp_news);
					
					$title0		= $xpath2->query($cssTagForTitle); 
					$mainText0	= $xpath2->query($cssTagForContent);  
					$data0		= $xpath2->query($cssTagForData);

					$title		= $title0[0]->nodeValue; 
					$mainText	= $mainText0[0]->nodeValue;
					//$data		= $data0[0]->nodeValue;
					$data= date("d/m/Y"); 
					
					$xml->startElement('documento');
					$xml->writeElement('empresa', $company);
					$xml->writeElement('departamento', $department);
					$xml->writeElement('titulo',trim($title));
					$xml->writeElement('data', $data);
					$xml->writeElement('texto', preg_replace('/\s+/', ' ',$mainText));
					$xml->endElement();
					
					echo $k." Mission Complete!  <br>";
					$k=$k+1;
				}

		}

			$xml->endElement();
		    $xml->flush();
?>

</body>

</html>
