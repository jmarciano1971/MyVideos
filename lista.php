<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="ISO8859-1">
    <title></title>
      </head>
  <body>
	<?php

  include_once("connect.inc");

	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}


	$query = "select idMovie, idFile, c16 , c02, c16, c19, c22 from movie order by premiered desc;";
	$result = $mysqli->query($query);

	/* numeric array */
	while ($row = $result->fetch_array(MYSQLI_NUM)) {

    	$parte = explode('/',$row[6]);

		$filename = "/media/filmes/" . $parte[count($parte) - 1];
        if (!file_exists($filename))
	       $filename = "/media/filmes/" . $parte[count($parte) - 2] . '/' . $parte[count($parte) - 1 ];

		$filename = utf8_encode($filename);
                echo "<p>";
        	echo "<a href=\"videoPlayer.php?filename=$filename\">" . utf8_encode($row[2]);
		echo "</a></p>";

	}

    /* free result set */
	$result->close();

	/* close connection */
	$mysqli->close();



?>
  </body>
</html>
