<?php
require 'vendor/autoload.php';	
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

$puppeteer = new Puppeteer;

$browser = $puppeteer->launch(['headless' => true, 'args'=> ['--no-sandbox', '--disable-setuid-sandbox', ]]);
$page = $browser->newPage();
$page->goto('http://www.ssp.sp.gov.br/LeNoticia.aspx?ID=44105');

// Get the "viewport" of the page, as reported by the page.
$artigo = $page->evaluate(JsFunction::createWithBody("
    return {
        text: document.getElementsByTagName('section')[1].innerHTML,        
        published: document.getElementsByTagName('h1')[0].innerHTML    
    };
"));

echo $artigo['published'];

$browser->close();

?>
