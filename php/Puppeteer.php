<?php
require '../vendor/autoload.php';
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

function getcontent($url){
$puppeteer = new Puppeteer (['read_timeout' => 95,]);
$browser = $puppeteer->launch(['headless' => true, 'args'=> ['--no-sandbox', '--disable-setuid-sandbox', ]]);
$page = $browser->newPage();
$page->goto($url,['read_timeout' => 120000,]);
$page->waitForSelector('.chapeu2');
$artigo = $page->evaluate(JsFunction::createWithBody("
    return {
        text: document.getElementsByTagName('section')[1].textContent,        
        published: document.getElementsByTagName('h1')[0].textContent    
    };
"));
$browser->close();
if (isset($artigo['published'])) {return array($artigo['text'], $artigo['published']); return true;}else{return false;}
}


$k=10309;
$p_length=10;
$p_lastnumber=2400;


for ($i = 878; $i <=$p_lastnumber; $i++) {
$news = new DOMDocument;
$url= file_get_contents ('http://www.ssp.sp.gov.br/noticia/UltimasNoticias.aspx?pag='.strval($i));
//echo $url;
@$news->loadHTML($url);
//echo $news->saveHTML();

$xpath = new DOMXPath($news);
$newsList = array();
$newsList = $xpath->query('//a[contains(@id, "conteudo_repData_repNoticias")]'); 
 

foreach ($newsList as $news) {
	$href = "http://www.ssp.sp.gov.br".$news->getAttribute('href');	
	//echo $response." <br>";
	echo $href." <br>";
	if (getcontent($href)) { 
	//echo var_dump($parList->nodeValue);
	$data=getcontent($href)[1];
	//echo $data." <br>";
	$data = substr($data, (strpos($data,',')+1),11);
	//echo $data." <br>";
	$content= trim(preg_replace('/\s+/', ' ', getcontent($href)[0]));
	//$content=utf8_decode ($content);
	if (DateTime::createFromFormat('d/m/Y', trim($data))) {
	$date = DateTime::createFromFormat('d/m/Y', trim($data));
	$filename = "governodoestado/ssp-ultimas-noticias/".$date->format('Y-m-d')."-".strval($i)."-".$k.".txt";
	file_put_contents($filename, $content);
	echo $filename." <br>";
	$k=$k+1;
	} else {echo "The date cannot be retrieved ! <br>";}
	}
	else {echo "The page cannot be retrieved ! <br>";}

}
}


?>

</body>

</html>
