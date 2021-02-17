<?php

require "classes/Image.php";

$fileName = $_FILES['image']['name'];

$fileTmpLoc = $_FILES['image']['tmp_name'];

$fileType = $_FILES['image']['type'];

$fileSize = $_FILES['image']['size'];

$fileErrorMsg = $_FILES['image']['error'];

$fileExt = explode('.', $fileName)[1];

//Validation
if(!$fileTmpLoc) { 
	echo "ERROR: Please browse for a file before clicking the upload button";
	exit();
} elseif ($fileSize > 5242880) {
	echo "ERROR: Upload files less than 5MB.";
	unlink($fileTmpLoc);
	exit();
} elseif (!preg_match("/\.(jpg|png)$/i", $fileName)) {
	echo "ERROR: The image type is not accepted.";
	unlink($fileTmpLoc);
	exit();
} elseif ($fileErrorMsg) {
	echo "ERROR: An error occured while processing the file. Try again.";
	exit();
}

$moveResult = move_uploaded_file($fileTmpLoc, "uploads/{$fileName}");

if ($moveResult != true) {
	echo "ERROR: File upload failed. Try again";
	unlink($fileTmpLoc);
	exit();
}

$target_file = "uploads/{$fileName}"; //file location

$wMax = 300;

$hMax = 300;

(new Image)->resize($target_file, $fileName, $wMax, $hMax);

$targetFile = "uploads/$fileName";
$thumbNail = "uploads/thumb_{$fileName}";

//dimensions for a square cropped image
$wthumb = 300;
$hthumb = 300;

(new Image)->crop($targetFile, $thumbNail, $wthumb, $hthumb, $fileExt);


echo "The file named <strong>{$fileName}</strong> uploaded and cropped successfully.<br><br>";


