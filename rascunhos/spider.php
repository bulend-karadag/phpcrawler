<?php
//extract links in a single page and save in .xml format...
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
$urlRoot='https://www.lantmannen.com';
$company='lantmannen';
$department='//ul[@class="breadcrumbs-list"]';
$filenameXML='lantmannen.xml';
$cssTagForAllText='//section//*[self::h1 or self::h2 or self::h3 or self::p]/text()';
$cssTagForMenu='//a[@class="navigation__item-title "]';
$cssTagForLinks='//span[@class="callout-inlinelink"]';
$cssTagForTitle='//title/text()';
$cssTagForDocumento='//ul[@class="breadcrumbs-list"]';
$cssTagForMetadata='//meta[@property]';

require_once("functions.php");

if(!empty($_POST["extract"])) {		
$basics = [
    "url_root" => $_POST['url'],
    "url" => $_POST['url'],
    "url_p" => $_POST['url'],
    "company" => $_POST['company'],
    "filename" => $_POST['filenameXML'],
    "department" => $_POST['department'],
    "menu" => $_POST['cssTagForMenu'],
    "text" => $_POST['cssTagForContent'],
    "links" => $_POST['cssTagForLinks'],
    "title" => $_POST['cssTagForTitle'],
    "count" => 1,
    "metatag"=>$_POST['cssTagForMeta']
];
		
		createXML($basics['filename']);
		$basics['links']=$_POST['cssTagForMenu'];
		$linkList=extractPage($basics['url'],'true');
		$basics['links']=$_POST['cssTagForLinks'];
		foreach ($linkList as $link) {
				$primeiraRoda=extractPage($link,'true');
				foreach ($primeiraRoda as $roda1){
						$basics['url_p']=$link;
						$segundaRoda=extractPage($roda1,'true');
						
				}
			
		}
		echo $basics['count']." paginas foram extraidas ...";
}
					

?>
<html>
<head>
<title>PHP Article Extractor</title>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>

<div class="item">
	<h3>Extract the site through Menu links</h3>
	<form name="extractSite" method="post" action="">
		<?php if(!empty($success_message)) { ?>	
		<div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
		<?php } ?>
		<?php if(!empty($error_message)) { ?>	
		<div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
		<?php } ?>
		<table border="0" align="center" class="table">
			<tr>
				<td>Target Website</td>
				<td><input type="text" class="InputBox" name="url" value="<?php if(isset($_POST['url'])) echo $_POST['url']; ?>"></td>
			</tr>
			<tr>
				<td>Company Name</td>
				<td><input type="text" class="InputBox" name="company" value="<?php if(isset($_POST['company'])) echo $_POST['company']; ?>">
				</td>
			</tr>
			<tr>
				<td>Department</td>
				<td><input type="text" class="InputBox" name="department" value="<?php if(isset($_POST['department'])) echo $_POST['department']; ?>">
				</td>
			</tr>
			<tr>
				<td>File Name</td>
				<td><input type="text" class="InputBox" name="filenameXML" value="<?php if(isset($_POST['filenameXML'])) echo $_POST['filenameXML']; ?>">
				</td>
			</tr>
			<tr>
				<td>Meta Data</td>
				<td><input type="text" class="InputBox" name="cssTagForMeta" value="<?php if(isset($_POST['cssTagForMeta'])) echo $_POST['cssTagForMeta']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Nav. Bar</td>
				<td><input type="text" class="InputBox" name="cssTagForMenu" value="<?php if(isset($_POST['cssTagForMenu'])) echo $_POST['cssTagForMenu']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Links</td>
				<td><input type="text" class="InputBox" name="cssTagForLinks" value="<?php if(isset($_POST['cssTagForLinks'])) echo $_POST['cssTagForLinks']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Title</td>
				<td><input type="text" class="InputBox" name="cssTagForTitle" value="<?php if(isset($_POST['cssTagForTitle'])) echo $_POST['cssTagForTitle']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Content</td>
				<td><input type="text" class="InputBox" name="cssTagForContent" value="<?php if(isset($_POST['cssTagForContent'])) echo $_POST['cssTagForContent']; ?>">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
				<input type="submit" name="extract" value="Extract" class="button">
				</td>
			</tr>

		</table>
	</form>
</div>

</body>

</html>
