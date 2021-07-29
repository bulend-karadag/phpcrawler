<?php
//extract links in a single page and save in .xml format...
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
$k=1;
$target_website='https://www.tekfentarim.com/news';
$company='tekfentarim';
$department='news';
$filenameXML='tekfentarim.xml';
$rootForLinks='https://www.tekfentarim.com';
$cssTagForLinks='//a[@class="ustCizik"]';
$cssTagForTitle='//div[@class="title-tekfen pt-md-1"]';
$cssTagForContent='//div[@class="col-md-12"]';
$cssTagForData='//time[@class="date-container minor-meta updated"]';


?>
<html>
<head>
<title>PHP Article Extractor</title>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
		<?php if(!empty($success_message)) { ?>	
		<div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
		<?php } ?>
		<?php if(!empty($error_message)) { ?>	
		<div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
		<?php } ?>
<div class="container">
<div class="item">
	<h3>Extract a single Page</h3>
	<form name="frmRegistration" method="post" action="">
		<table border="0"  align="center" class="table">
			<tr>
				<td>Target Website</td>
				<td><input type="text" class="InputBox" name="target_website" value="<?php if(isset($_POST['target_website'])) echo $_POST['target_website']; ?>"></td>
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
				<td>CSS tag for Date</td>
				<td><input type="text" class="InputBox" name="cssTagForData" value="<?php if(isset($_POST['cssTagForData'])) echo $_POST['cssTagForData']; ?>">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
				<input type="submit" name="eLinksInaPage" value="Extract" class="button">
				</td>
			</tr>

		</table>
	</form>
</div>
<div class="item">
	<h3>Extract links in a Page</h3>
	<form name="frmRegistration" method="post" action="">
		<table border="0"  align="center" class="table">
			<tr>
				<td>Target Website</td>
				<td><input type="text" class="InputBox" name="target_website" value="<?php if(isset($_POST['target_website'])) echo $_POST['target_website']; ?>"></td>
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
				<td>Root for Links</td>
				<td><input type="text" class="InputBox" name="rootForLinks" value="<?php if(isset($_POST['rootForLinks'])) echo $_POST['rootForLinks']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Links</td>
				<td><input type="text" class="InputBox" name="cssTagForLinks" value="<?php if(isset($_POST['cssTagForTitle'])) echo $_POST['cssTagForTitle']; ?>">
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
				<td>CSS tag for Date</td>
				<td><input type="text" class="InputBox" name="cssTagForData" value="<?php if(isset($_POST['cssTagForData'])) echo $_POST['cssTagForData']; ?>">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
				<input type="submit" name="eLinksInaPage" value="Extract" class="button">
				</td>
			</tr>

		</table>
	</form>
</div>
<div class="item">
	<h3>Extract links in Pagination</h3>
	<form name="frmRegistration" method="post" action="">
		<table border="0" align="center" class="table">
			<tr>
				<td>Target Website</td>
				<td><input type="text" class="InputBox" name="target_website" value="<?php if(isset($_POST['target_website'])) echo $_POST['target_website']; ?>"></td>
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
				<td>Root for Links</td>
				<td><input type="text" class="InputBox" name="rootForLinks" value="<?php if(isset($_POST['rootForLinks'])) echo $_POST['rootForLinks']; ?>">
				</td>
			</tr>
			<tr>
				<td>Number of Tabs</td>
				<td><input type="text" class="InputBox" name="numberTabs" value="<?php if(isset($_POST['numberTabs'])) echo $_POST['numberTabs']; ?>">
				</td>
			</tr>
			<tr>
				<td>CSS tag for Links</td>
				<td><input type="text" class="InputBox" name="cssTagForLinks" value="<?php if(isset($_POST['cssTagForTitle'])) echo $_POST['cssTagForTitle']; ?>">
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
				<td>CSS tag for Date</td>
				<td><input type="text" class="InputBox" name="cssTagForData" value="<?php if(isset($_POST['cssTagForData'])) echo $_POST['cssTagForData']; ?>">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
				<input type="submit" name="eLinksInaPage" value="Extract" class="button">
				</td>
			</tr>

		</table>
	</form>
</div>
</div>
</body>

</html>
