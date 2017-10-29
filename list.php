<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="ISO8859-1">
    <title></title>
    <script src="jquery.min.js"></script>
    <script>
    function searchFilter(page_num) {
        page_num = page_num?page_num:0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type: 'POST',
            url: 'getData.php',
            data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
            beforeSend: function () {
                $('.loading-overlay').show();
            },
            success: function (html) {
                $('#posts_content').html(html);
                $('.loading-overlay').fadeOut("slow");
            }
        });
    }
    </script>

  </head>
  <body>

<div class="post-search-panel">
    <input type="text" id="keywords" placeholder="Type keywords to filter posts" onkeyup="searchFilter()"/>
    <select id="sortBy" onchange="searchFilter()">
        <option value="">Sort By</option>
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>
</div>
<div class="post-wrapper">
    <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
    <div id="posts_content">
    <?php
    //Include pagination class file
    include('Pagination.php');

    //Include database configuration file
    include('dbConfig.php');

    $limit = 10;

    //get number of rows
    $queryNum = $db->query("SELECT COUNT(*) as postNum FROM movie ".$whereSQL.$orderSQL);
    $resultNum = $queryNum->fetch_assoc();
    $rowCount = $resultNum['postNum'];

    //initialize pagination class
    $pagConfig = array(
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);

    //get rows
    $query = $db->query("select idMovie, idFile, c16 , c02, c16, c19, c22 from movie $whereSQL $orderSQL LIMIT $start,$limit");

echo ("select idMovie, idFile, c16 , c02, c16, c19, c22 from movie $whereSQL $orderSQL LIMIT $start,$limit");

    if($query->num_rows > 0){ ?>
        <div class="posts_list">
        <?php
            while($row = $query->fetch_array(MYSQLI_NUM)){
                $postID = $row[0];
        ?>
            <div class="list_item"><a href="javascript:void(0);"><h2><?php echo $row[6]; ?></h2></a></div>
        <?php } ?>
        </div>
        <?php echo $pagination->createLinks(); ?>
    <?php } ?>
    </div>
</div>

</body>
</html>
