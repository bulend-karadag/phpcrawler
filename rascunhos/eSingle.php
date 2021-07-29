<?php
//extract links in a single page and save in .xml format...
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
$k=1;
$target_website='https://www.lantmannen.com/research-and-innovation/innovation-from-field-to-fork/food-innovation-and-health/broccoli-sprouts/';
$company='lantmannen';
$department='Sustainable Agriculture';
$filenameXML='lantmannen.xml';
$cssTagForTitle='//title';
$cssTagForContent='//section//*[self::h1 or self::h2 or self::h3 or self::p]/text()';
$cssTagForData='//span[@class="newspage-meta-publishdate"]';


?>
<html>
<head>
<title>PHP Article Extractor</title>
</head>
<body>
<?php

					$html = file_get_contents ($target_website);
					$tmp_news = new DOMDocument(); 
					@$tmp_news->loadHTML($html);
					$xpath2 = new DOMXPath($tmp_news);
					
					$title0		= $xpath2->query($cssTagForTitle); 
					$mainText0	= $xpath2->query($cssTagForContent);  
					$metaTag	= $xpath2->query('//meta[@property]');
					
					foreach ($metaTag as $tag) {
						echo $tag->getAttribute('content').'</br>';		
					} 

					$title		= $title0[0]->nodeValue; 
					$mainText	= $mainText0[0]->nodeValue;
					//$data		= $data0[0]->nodeValue;
					$data= date("d/m/Y"); 
					
					$xml = simplexml_load_file($filenameXML);

					$newDocument = $xml->addChild('documento');
					$newDocument->addChild('empresa', $company);
					$newDocument->addChild('departamento', $department);
					$newDocument->addChild('titulo', htmlspecialchars($title));
					$newDocument->addChild('data', $data);
					$newDocument->addChild('texto', htmlspecialchars(preg_replace('/\s+/', ' ',$mainText)));
					$newMetaTag=$newDocument->addChild('meta');
					foreach ($metaTag as $tag) {
						$newMetaTag->addChild(htmlspecialchars($tag->getAttribute('property')),htmlspecialchars($tag->getAttribute('content')));	
					} 
					$xml->asXML($filenameXML);
					
					echo $k." Mission Complete!  <br>";
					$k=$k+1;



?>

</body>

</html>
