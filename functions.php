<?php
function xmlRemoveNode($myXML, $id) {
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($myXML);
    $xpath = new DOMXpath($xmlDoc);
    $nodeList = $xpath->query('//variable[@filename="'.$id.'"]');
    if ($nodeList->length) {
        $node = $nodeList->item(0);
        $node->parentNode->removeChild($node);  
    }
    $xmlDoc->save($myXML);
    echo $id." is removed from setting.xml file";
}

function saveSetting($filename) {
	global $basics; 
		$xmlAppend = simplexml_load_file($filename);
		$newDocument = $xmlAppend->addChild('variable');
		$newDocument->addAttribute('filename', $basics['filename']);
		foreach ($basics as $key=>$value) {		
				$newDocument->addChild($key, htmlspecialchars($value));
				} 
		$xmlAppend->asXML($filename);		
		echo 'Settings are saved succesfully </br>';
}

function GetFileContent($url){
$ch = curl_init();

$header = array();
$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
$header[] = "Cache-Control: max-age=0";
$header[] = "Connection: keep-alive";
$header[] = "Keep-Alive: 300";
$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
$header[] = "Accept-Language: en-us,en;q=0.5";
$header[] = "Pragma: "; 

curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT    5.0'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
/*
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '/home/dnelub/Documents/scraping/soufflet.crt');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '/home/dnelub/Documents/scraping/soufflet.crt');
*/
$data = curl_exec($ch);
$getInfo = curl_getinfo($ch);
	if($data === false){
		echo 'Curl error: ' . curl_error($ch);
		return false;
	}
	else if($getInfo['http_code'] != 200){
		echo "No data returned. Http Code: " . $getInfo['http_code'].'</br>';
		return false;
	} else {
		//echo $data;
		curl_close($ch);
		return $data;
	}
}

function cleanFields($field){
	return htmlspecialchars(preg_replace('/\s\s+/', ' ',$field));
}

function getFields($newXpath){
	global $basics; 
	$fieldList=array();
	
	$textList	=$newXpath->query($basics['text']); 
	if ($textList->length>0) {
					$content='';
					foreach ($textList as $text) {
						$content= $content.' '.$text->nodeValue; 		
					}	
					$content=cleanFields($content);
					$fieldList['text']=$content;
	}
			
	$title 		=$newXpath->query($basics['title']);					
	if ($title->length>0){
					$fieldList['title']=  cleanFields($title[0]->nodeValue);}
					else {$fieldList['title']=' ';}
	
	
	$department	=$newXpath->query($basics['department']);
	if ($department->length>0){
					$local='';
					foreach ($department as $loc) {
						if (trim($loc->nodeValue)<>''){
							if ($local==''){
							$local= $loc->nodeValue;}
							else {
							$local= $local.'/'.$loc->nodeValue;}
						}
					}	
					$local=cleanFields($local);
					$fieldList['department']=$local;		
	}
					if (empty($fieldList['department'])){$fieldList['department']=$fieldList['title'];}	
			
	$data		=$newXpath->query($basics['date']);	
	if ($data->length>0){
					$fieldList['date']=  cleanFields($data[0]->nodeValue);}
					else {$fieldList['date']='';}
					//echo $fieldList['date'];
					//$fieldList['date']= preg_replace("([^0-9\.,])", "", $fieldList['date']);

	
	$metaTagx		= $newXpath->query($basics['metatag']); 
	$metaTag	=array();
	foreach ($metaTagx as $tag) {
					if (!empty($tag->getAttribute('property'))) {
						$metaTag[$tag->getAttribute('property')]=$tag->getAttribute('content');}
					else {
						$metaTag[$tag->getAttribute('name')]=$tag->getAttribute('content');}
			}
	
	$metaTag = array_map('htmlspecialchars', $metaTag);
	$metaTag=array_unique($metaTag, SORT_REGULAR);
	$fieldList['metatag'] = $metaTag;
	
	//print_r($metaTag);
	//echo '</br>';
	return $fieldList;
}

function extractPage($newXpath){
	if($newXpath) {
		global $basics; 
		global $visitedLinkList;
		$xbasics= array_map("htmlspecialchars", $basics);
		$xFields=getFields($newXpath); 
		//echo $basics['count'].$url.'</br>';
			
		if ($xFields['text']<>''){
			$xmlAppend = simplexml_load_file($basics['filename'], null, LIBXML_NOBLANKS);
			$newDocument = $xmlAppend->addChild('documento');
			$newDocument->addAttribute('id', $xbasics['count']);
			$newDocument->addChild('empresa', $xbasics['company']);
			$newDocument->addChild('departamento',$xFields['department'] );
			$newDocument->addChild('titulo',$xFields['title'] );
			$newDocument->addChild('url', $xbasics['url']);
			$newDocument->addChild('urlParent', $xbasics['url_p']);
			$newDocument->addChild('texto',$xFields['text'] );
			$newDocument->addChild('data',$xFields['date']);
			$newMetaTag=$newDocument->addChild('meta');
					foreach ($xFields['metatag'] as $key=>$value) {
						if(!contains($key, 'image')){
						$newMetaTag->addChild($key,$value);}
						//echo $key.' - '.$value.'</br>'; 
					} 
			
			$xmlAppend->asXML($basics['filename']);
			array_push($visitedLinkList, $xbasics['url']);
			$basics['count']++;
		}
	}
}

function getLinks($newXpath,$cssTag){
			global $basics; 
			//echo "buradan geciyor";
			$linkList		= $newXpath->query($cssTag); 	
				/*foreach ($linkList as $urlChild) {
					echo $urlChild->getAttribute('href').'</br>'.$k;
					$k++;
					echo "buradan gecmiyor";
				}*/
			return cleanLinks($linkList);
}

function getXpath($url){
			if ($newHtml=GetFileContent($url)) {
					//echo $newHtml;
					$newDOM = new DOMDocument(); 
					@$newDOM->loadHTML($newHtml, LIBXML_HTML_NODEFDTD);
					//echo $newDOM->getElementsByTagName('title')[0]->nodeValue;
					$tags_to_remove = array('script','style','iframe','link');
					foreach($tags_to_remove as $tag){
						foreach(iterator_to_array($newDOM->getElementsByTagName($tag)) as $node) {
							//echo $node->nodeValue.'</br>';
							$node->parentNode->removeChild($node);
							
						}  
					}			
					$newXpath = new DOMXPath($newDOM);	
					/*
					$body = $newDOM->getElementsByTagName('body')->item(0);
					echo $newDOM->saveXML($body);
						*/	
					return $newXpath; }
			else {
					return false;
			}
						
}

function cleanLinks($linkList) {
			global $basics; 
			$urlList=array();
				foreach ($linkList as $urlChild) {
					array_push($urlList,$urlChild->getAttribute('href'));
					//echo $urlChild->getAttribute('href').'</br>';
				}
			$urlList = array_map('trim', $urlList);
			$urlList = array_filter($urlList);
			$urlList = array_unique($urlList, SORT_REGULAR);
			$urlListNovo=array();
				foreach ($urlList as $key=>$value) {
					if((contains($value, '.pdf'))or(contains($value, 'javascript'))or(contains($value, 'youtube'))or(contains($value, 'linkedin')))
					{unset($urlList[$key]);}
					else if(!contains($value, 'http'))
							{array_push($urlListNovo,$basics['url_root'].$value);  }
							else
							{array_push($urlListNovo,$value);}
							//echo $value.'</br>'; 
					
				}
			$urlListx=array_diff($urlListNovo,$basics);
			$urlList=array();
			foreach ($urlListx as $value) {
					if(!contains($value, $basics['company'])) {
							unset($urlListNovo[$key]); }
						else {
							array_push($urlList,$value);}
			}
			/*$j=1;
			foreach ($urlList as $url) {
				echo $j.'-'.$url.'</br>';
				$j++;
			}*/
			
			return $urlList;
}

function createXML($filename){
	
			$xml = new XMLWriter;
			$xml->openURI($filename);
			$xml->setIndent(true);
			$xml->startDocument('1.0', 'UTF-8');
			$xml->startElement('root');
			$xml->endElement();
			$xml->flush();
	
}

function mergeFile($newfilename, $fileName1, $fileName2) {
	$target = new DOMDocument();
	$target->load($fileName1);
	$newXpath = new DOMXPath($target);
	$last_item = $newXpath->query('//documento[last()]');
	$last_id = (int) $last_item[0]->getAttribute('id');
	$newId = $last_id + 1;
   
	$source = new DOMDocument();
    $source->load($fileName2);
  
    foreach ($source->getElementsByTagName("documento") as $documento)   {
        $import = $target->importNode($documento, true);
        $import->setAttribute('id', $newId);
        echo $import->getAttribute('id').'</br>';
        $target->documentElement->appendChild($import);
        $newId++;
    }
    $target->save($newfilename);
}

function contains($haystack, $needle){
    return strpos($haystack, $needle) !== false;
}

function getFieldsFromSetting($filename){
	$dom = new DOMDocument;
	$dom->load("setting.xml");
	$xpath = new DOMXPath($dom);
	$query = "//variable[@filename='".$filename."']";
	$elements = $xpath->query($query);
	$fillForm=array();
	foreach ($elements as $fields) {
			foreach ($fields->childNodes as $field) {
				$fillForm[$field->nodeName] = $field->nodeValue;
				//echo $field->nodeName.' '.$field->nodeValue.'</br>';
			}
	}
	return $fillForm;
	
}

function remove_dom_namespace($doc, $ns) {
  $finder = new DOMXPath($doc);
  $nodes = $finder->query("//*[namespace::{$ns} and not(../namespace::{$ns})]");
  foreach ($nodes as $n) {
    $ns_uri = $n->lookupNamespaceURI($ns);
    $n->removeAttributeNS($ns_uri, $ns);
  }
}
?>
