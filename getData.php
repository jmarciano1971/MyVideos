<?php
if(isset($_POST['page'])){
    //Include pagination class file
    include('Pagination.php');

    //Include database configuration file
    include('dbConfig.php');

    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 10;

    //set conditions for search
    $whereSQL = $orderSQL = '';
    $keywords = $_POST['keywords'];
    $sortBy = $_POST['sortBy'];
    if(!empty($keywords)){
        $whereSQL = "WHERE title LIKE '%".$keywords."%'";
    }
    if(!empty($sortBy)){
        $orderSQL = " ORDER BY id ".$sortBy;
    }else{
        $orderSQL = " ORDER BY id DESC ";
    }

    //get number of rows
    $queryNum = $db->query("SELECT COUNT(*) as postNum FROM movie ".$whereSQL.$orderSQL);
    $resultNum = $queryNum->fetch_assoc();
    $rowCount = $resultNum['postNum'];

    //initialize pagination class
    $pagConfig = array(
        'currentPage' => $start,
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);

    //get rows
    $query = $db->query("select idMovie, idFile, c16 , c02, c16, c19, c22 from movie $whereSQL $orderSQL LIMIT $start,$limit");

    if($query->num_rows > 0){ ?>
        <div class="posts_list">
        <?php
            while($row = $query->fetch_assoc(MYSQLI_NUM)){
                $postID = $row[0];
        ?>
            <div class="list_item"><a href="javascript:void(0);"><h2><?php echo $row[6]; ?></h2></a></div>
        <?php } ?>
        </div>
        <?php echo $pagination->createLinks(); ?>
<?php } } ?>
