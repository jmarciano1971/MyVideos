<?php
define("ROW_PER_PAGE",4);
require_once('db.php');
?>
<html>
<head>
	<title>MyVideos by JMS</title>
<style>
body{width:615px;font-family:arial;letter-spacing:1px;line-height:20px;}
.tbl-qa{width: 100%;font-size:0.9em;background-color: #f5f5f5;}
.tbl-qa th.table-header {padding: 5px;text-align: left;padding:10px;}
.tbl-qa .table-row td {padding:10px;background-color: #FDFDFD;vertical-align:top;}
.button_link {color:#FFF;text-decoration:none; background-color:#428a8e;padding:10px;}
#keyword{border: #CCC 1px solid; border-radius: 4px; padding: 7px;background:url("demo-search-icon.png") no-repeat center right 7px;}
.btn-page{margin-right:10px;padding:5px 10px; border: #CCC 1px solid; background:#FFF; border-radius:4px;cursor:pointer;}
.btn-page:hover{background:#F0F0F0;}
.btn-page.current{background:#F0F0F0;}
</style>
</head>
<body>
<?php

  function getTextBetweenTags($string, $tagname) {
		    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
		    preg_match($pattern, $string, $matches);
		    return $matches[1];
  }

	$search_keyword = '';
	if(!empty($_POST['search']['keyword'])) {
		$search_keyword = $_POST['search']['keyword'];
	}
	$sql = 'select idMovie, idFile, c16 , c02, c16, c19, c22, c00, c08, c14,c01,c03, premiered from movie WHERE c00 LIKE :keyword OR c16 LIKE :keyword order by premiered desc ';

	/* Pagination Code starts */
	$per_page_html = '';
	$page = 1;
	$start=0;
	if(!empty($_POST["page"])) {
		$page = $_POST["page"];
		$start=($page-1) * ROW_PER_PAGE;
	}
	$limit=" limit " . $start . "," . ROW_PER_PAGE;
	$pagination_statement = $pdo_conn->prepare($sql);
	$pagination_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pagination_statement->execute();

	$row_count = $pagination_statement->rowCount();
	if(!empty($row_count)){
		$per_page_html .= "<div style='text-align:center;margin:20px 0px;'>";
		$page_count=ceil($row_count/ROW_PER_PAGE);
		if($page_count>1) {
			for($i=1;$i<=$page_count;$i++){
				if($i==$page){
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
				} else {
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
				}
			}
		}
		$per_page_html .= "</div>";
	}

	$query = $sql.$limit;
	$pdo_statement = $pdo_conn->prepare($query);
	$pdo_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pdo_statement->execute();
	$result = $pdo_statement->fetchAll();
?>
<form name='frmSearch' action='' method='post'>
<div style='text-align:right;margin:20px 0px;'><input type='text' name='search[keyword]' value="<?php echo $search_keyword; ?>" id='keyword' maxlength='25'></div>
<?php echo $per_page_html; ?>
<table class='tbl-qa'>
  <thead>
	<tr>
	  <th class='table-header' width='20%'>Capa</th>
	  <th class='table-header' width='20%'>Título Original</th>
	  <th class='table-header' width='20%'>Título no Brasil</th>
	  <th class='table-header' width='20%'>Lançamento</th>
		<th class='table-header' width='20%'>Gênero</th>
	</tr>
  </thead>
  <tbody id='table-body'>
	<?php
	if(!empty($result)) {
		foreach($result as $row) {

			$img = getTextBetweenTags($row['c08'], "thumb");

			$piece = explode('/',$row['c22'], 6);

		  $filename = "/media/filmes/" . $piece[count($piece) - 1];
			if (!file_exists($filename))
				 $filename = "/media/filmes/" . $piece[count($piece) - 2] . '/' . $piece[count($piece) - 1 ];

		  $filename = utf8_encode($filename);


	?>
	  <tr class='table-row'>
		<td>
    <?php echo "<a href=\"videoPlayer.php?filename=$filename&poster=$img\">";?><img src=<?php echo $img; ?> title="<?php echo utf8_encode($row['c01']); ?>" height="160" width="120">
		</a></td>
		<td><?php echo $row['c16']; ?></td>
		<td title="<?php echo utf8_encode($row['c03']); ?>"><?php echo utf8_encode($row['c00']); ?></td>
		<td><?php echo $row['premiered']; ?></td>
		<td><?php var_dump($piece); ?></td>
	  </tr>
    <?php
		}
	}
	?>
  </tbody>
</table>
<?php echo $per_page_html; ?>
</form>
</body>
</html>
