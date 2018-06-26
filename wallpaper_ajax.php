<?php
/**********************************************
The MIT License (MIT)

Copyright (C) 2018 Darkhost (darkhost225gmail.com) 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
***********************************************/
/* 
POST DATA
(필수) 텍스트 데이터 : line1_txt, line2_1_txt, line2_2_txt
(필수) 종류 데이터 : SelectOS
(선택) 사진 데이터 : $_FILE['bg_upload']
*/

// 폰트 파일
$font = "./font/BMDOHYEON_ttf.ttf";

// 투명 배경
$trans_bg = "./image/transparent_background_1080x1920.png";

// 투명 템플릿
$trans_templet = "./image/transparent_templet_1080x1920.png";

// 기본 템플릿
$normal_templet = "./image/templet_1080x1920.png";

// 템플릿 Image 로드
$trans = imagecreatefrompng($trans_templet);

// 배경 Image 로드
$bg = imagecreatefrompng($trans_bg);

// 글씨 색상 적용 (검정색)
$black = imagecolorallocate($bg, 0, 0, 0);

// 투명 적용
imagealphablending($trans, false);
imagesavealpha($trans, true);

$pictureUpload = false;

// 폰트 사이즈 50
$font_pt = 50;

// Android (내 폰 기준)
if($_POST['SelectOS'] == 1) {
	$line1_x = 200;
	$line1_y = 150;
	$line2_1_x = 135;
	$line2_2_x = 685;
	$line2_y = 270;
}
// Android (민지 폰 기준)
if($_POST['SelectOS'] == 2) {
	$line1_x = 258;
	$line1_y = 135;
	$line2_1_x = 168;
	$line2_2_x = 783;
	$line2_y = 300;
}
// iPhone (기홍 폰 기준)
else if($_POST['SelectOS'] == 3) {
	$line1_x = 252;
	$line1_y = 145;
	$line2_1_x = 120;
	$line2_2_x = 795;
	$line2_y = 255;
}

// 사용자 사진 업로드단
if(isset($_FILES['bg_upload'])) {
	if(is_uploaded_file($_FILES['bg_upload']['tmp_name'])) {
		$uploaddir = "./temp/";
		$uploadfile = $uploaddir . date("Ymd_His_") . $_FILES['bg_upload']['name'];
		
		$ableExt = array ("jpg", "jpeg", "png");
		$path = pathinfo($_FILES['bg_upload']['name']);
		$exts = strtolower($path['extension']);
		if(!in_array($exts, $ableExt)) {
			?><script> alert("사진 파일 (jpg, jpeg, png)만 업로드 할 수 있습니다.");</script><?
		}
		else {
			if(move_uploaded_file($_FILES['bg_upload']['tmp_name'], $uploadfile)) {
				$pictureUpload = true;
				if($exts == "jpg" || $exts == "jpeg") {
					$userimage = imagecreatefromjpeg($uploadfile);
				}
				else if($exts == "png") {
					$userimage = imagecreatefrompng($uploadfile);
				}
				else {
					?><script> alert("사진을 업로드하는데 실패했습니다.");</script><?
				}
				
				imagealphablending($userimage, false);
				imagesavealpha($userimage, true);
				imagecopy($bg, $userimage, 0, 555, 0, 0, 1080, 1920);	
				imagecopy($bg, $trans, 0, 0, 0, 0, 1080, 1920);	
			}
			else {
				$pictureUpload = false;
				?><script> alert("사진을 업로드하는데 실패했습니다.");</script><?
			}
		}
		// 사용자 파일 제거
		unlink($uploadfile);
	}
}
else {
	$pictureUpload = false;
}

// 사진 업로드가 되어있지 않을 경우
if($pictureUpload == false) {
	$userimage = imagecreatefrompng($normal_templet);
	imagealphablending($userimage, false);
	imagesavealpha($userimage, true);
	imagecopy($bg, $userimage, 0, 0, 0, 0, 1080, 1920);
}

imagealphablending($bg, false);
imagesavealpha($bg, true);

imagettftext($bg, $font_pt , 0, $line1_x, $line1_y, $black, $font, $_POST['line1_txt']);
imagettftext($bg, $font_pt , 0, $line2_1_x , $line2_y, $black, $font, $_POST['line2_1_txt']);
imagettftext($bg, $font_pt , 0, $line2_2_x , $line2_y, $black, $font, $_POST['line2_2_txt']);

ob_start();
imagepng($bg);
$imagedata = ob_get_contents();
ob_end_clean();
echo base64_encode($imagedata);
imagedestroy($bg);
?>