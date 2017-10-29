<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="ISO8859-1">
    <title><?php echo $_GET["title"]; ?></title>
      </head>
	<body>
	<?php
		$filename = $_GET["filename"];
		$path_parts = pathinfo($filename);

  	if ($path_parts['extension'] != 'mp4') echo "<br><h3>*** Este vídeo não tem a extensão .mp4, portanto só possui a garantia de total funcionamento no Google Chrome. E mesmo assim, poderá ter problemas de lentidão para avançar e retroceder ...</h3><br>";

    echo "<h4>" . $_GET["title"] . "</h4>";

    if (file_exists($path_parts['dirname'] . "/" . $path_parts['filename'] . "..srt"))
		   $filename_srt = $path_parts['dirname'] . "/" . $path_parts['filename'] . "..srt";
		if (file_exists($path_parts['dirname'] . "/" . $path_parts['filename'] . ".srt"))
		   $filename_srt = $path_parts['dirname'] . "/" . $path_parts['filename'] . ".srt";

            	$filename_vtt = $path_parts['dirname'] . "/" . $path_parts['filename'] . ".vtt";

		if (file_exists($filename_srt) && !file_exists($filename_vtt)) {
		   exec("sudo -u jms /usr/bin/ffmpeg -sub_charenc ISO8859-1 -i '$filename_srt' '$filename_vtt'",$output);
		}
	?>
        <video id="video" width="640" height="360" controls autoplay>
			<source src="<?php echo $filename; ?>" type="video/mp4">
			<track label="Português (Brazil)" kind="subtitles" srclang="pt-br" src="<?php echo $filename_vtt; ?>" default>
		</video>
	</body>
</html>
