<?php

class Image
{
	//target_file - original file to be resized
	//fileName - name and location of the resize file
	//$wMax - maximum with of the resized file
	//$hMax -maximu height of the resized file
	public function resize($target_file, $fileName, $wMax, $hMax) 
	{
		$fileExt = $this->getExtension($target_file);

		$resizedFile = "uploads/resized_{$fileName}"; 

		list($w_org, $h_org) = getimagesize($target_file);
		$scale_ratio = $w_org/$h_org;

		if( ( $wMax/$hMax ) > $scale_ratio ) {
			$wMax = $hMax*$scale_ratio;
		} else {
			$hMax = $w/$scale_ratio;
		}

		$img = "";

		if($fileExt == "png" || $fileExt == "PNG") {
			$img = imagecreatefrompng($target_file);
		} else {
			$img = imagecreatefromjpeg($target_file);
		}

		$trc = imagecreatetruecolor($wMax, $hMax);

		imagecopyresampled($trc, $img, 0, 0, 0, 0, $wMax, $hMax, $w_org, $h_org);

		imagejpeg($trc, $resizedFile, 80);
	}

	//target_file - original file to be cropped
	//thumbnail - name and location of the cropped file
	//$wMax - maximum with of the cropped file
	//$hMax -maximu height of the cropped file

	public function crop($target_file, $thumbnail, $wthumb, $hthumb ) 
	{
		$ext = $this->getExtension($target_file);

		list($w_org, $h_org) = getimagesize($target_file);
		$src_x = ($w_org/2)-($wthumb/2); //calculation to get the x-coordinate of the starting point
		$src_y = ($h_org/2)-($hthumb/2); //calculation to get the y-coordinate of the starting point
		$ext = strtolower($ext);
		$img = "";

		if($ext == 'png') {
			$img = imagecreatefrompng($target_file);
		} else {
			$img = imagecreatefromjpeg($target_file);
		}

		$tcr = imagecreatetruecolor($wthumb, $hthumb);

		//Cropping starts from the center

		imagecopyresampled($tcr, $img, 0, 0, $src_x, $src_y, $wthumb, $hthumb, $wthumb, $hthumb);

		imagejpeg($tcr, $thumbnail, 85);
	}

	public function getExtension($fileName)
	{
		return strtolower(explode('.', $fileName)[1]);
	}
}