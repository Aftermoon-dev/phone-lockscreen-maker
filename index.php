<?php
/**********************************************
The MIT License (MIT)

Copyright (C) 2018 Darkhost (darkhost225@gmail.com) 

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
?>
<!DOCTYPE html>
<html lang=ko>
<head>
<title>잠금화면 만들기 (Beta)</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<style>
   .wf-active body {
    font-family: 'NanumGothic';
   }
	html, body { 
		background-color: #e5e5e5;
	}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Script -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/loadingoverlay.js"></script>
<script src="js/placeholders.js"> </script>

<!-- Using NanumGothic -->
<script script src="https://www.google.com/jsapi">
   google.load( "webfont", "1" );
   google.setOnLoadCallback(function() {
    WebFont.load({ custom: {
     families: [ "NanumGothic" ],
     urls: [ "https://fonts.googleapis.com/earlyaccess/nanumgothic.css" ]
    }});
   });
</script>
</html>
</head>
<body>
<center>
<h1>잠금화면 만들기 (Beta)</h1>
<h3>Made by Darkhost (darkhost225@gmail.com)</h3>
현재 베타이며, 아직 자료가 많지 않아 스마트폰 기종에 따라 위치가 안맞을 수 있습니다.
<br>
사진 업로드시 잠금화면 생성 후 바로 삭제됩니다.
<br>
<br>
<form method="post" name="frmMake" id="frmMake">
첫 번째 줄 문구 입력 : 
<input type="text" id="line1" placeholder="(ex. 아무것도 안했는데) " style="width:300px"/> 
<br>
두 번째 줄 첫 번째 문구 입력 : 
<input type="text" id="line2_1" placeholder="(ex. 벌써) " style="width:100px"/> 
<br>
두 번째 줄 두 번째 문구 입력 : 
<input type="text" id="line2_2" placeholder="(ex. 이야..) " style="width:100px"/> 
<br>
OS 선택 :
<select name="SelectOS" id="SelectOS">
	<option value="">OS 선택</option>
	<option value="1">Android (갤럭시 S9 기준)</option>
	<option value="2">Android (위 기준이 안맞는 경우 선택)</option>
	<option value="3">iOS</option>
</option>
</select>
</form>
<form method="post" name="frmFile" id="frmFile" enctype="multipart/form-data" >
<br>
	배경 사진 (선택) :
	<input type="file" name="bg_upload" id="bg_upload" accept="image/jpeg, image/png" onchange="fileCheck()">
<br>
<br>
<input type="button" id="make" name="make" value="만들기" >
</form>
<br>
<br>
<br>
<p id="content"></p>
</center>
</body>

<!-- AJAX -->
<script>
$("#make").click(function() {
	if ($("#line1").val() == "" || 
	$("#line2_1").val() == "" || 
	$("#line2_2").val() == "" || 
	$("#SelectOS option:selected").val() == 0) {
		alert("정보가 부족합니다. 모든 정보를 입력해주세요.");
		return false;
	}
	
	else {
		$.LoadingOverlay("show");
		var formData = new FormData();
		formData.append("line1_txt", $("#line1").val());
		formData.append("line2_1_txt", $("#line2_1").val());
		formData.append("line2_2_txt", $("#line2_2").val());
		formData.append("SelectOS", $("#SelectOS option:selected").val());
		if($("input[name=bg_upload]")[0].files[0] != null) {
			formData.append("bg_upload", $("input[name=bg_upload]")[0].files[0]);
		}

		$.ajax({
			url:"./wallpaper_ajax.php",
			type:"post",
			data: formData,
			contentType: false,
			processData: false,
			success:function (data) {
				$("#content").html('<img src="data:image/png;base64, '+ data +'" width="500">');
			}
		})	
	}
	$.LoadingOverlay("hide");
})

function fileCheck() {
	var file = $("#bg_upload")[0].files[0];
	if(file.type.indexOf("jpeg" || "png") == -1) {
		alert("jpg, jpeg, png 파일만 업로드할 수 있습니다.");
		fileReset();
	}
}

function fileReset() {
	var agent = navigator.userAgent.toLowerCase();
	
	if((navigator.appName == "Netscape"	&& agent.indexOf("trident")) != -1 || agent.indexOf("msie") != -1) {
		$("#bg_upload".replaceWith($("#bg_upload").clone(true)));
	}
	else {
		$("#bg_upload").val("");
	}
}
</script>