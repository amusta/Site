<?php
include('connection.php');
include('login/functions.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Movie rental</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<?php

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 12;
$offset = ($pageno-1) * $no_of_records_per_page;



?>
<body>


<div class="jumbotron text-center">
    <div class="topright">


      <?php if (isLoggedIn()){ ?>

          <a href="profile.php" class="button">Profile</a> <br>

          <a href="login/logout.php" class="button">Logout</a>

          <?php    if(isset($_SESSION['user'])) { } ?>
        <?php } else { ?>

            <a href="login/admin/home.php" class="button">Login</a>  <br>

        <?php } ?>


        <?php if (isAdmin()){ ?>
            <a href="login/admin/home.php" class="button">AdminView</a>
        <?php } ?>

    </div>

    <form action="" class ="example" method="post"  >


        <b> Search by: </b><select name="search_type" id="search_type" >
            <option value="Name">Name</option>
            <option value="Year">Year</option>
            <option value="Genre">Genre</option>
        </select>
            <input type="text" placeholder="Search" name="term" />
        <button type="submit"><i class="fa fa-search"></i></button>

    </form>

</div>
    <?php

    if (!empty($_REQUEST['term'])) {
        $type = ($_REQUEST['search_type']);
        $term = ($_REQUEST['term']);

        $total_pages_sql = "SELECT COUNT(*) FROM movies WHERE  " . $type . " LIKE '%" . $term . "%'";
        $pages =$conn->query($total_pages_sql);
        $total_rows = mysqli_fetch_array($pages)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        $sql = "SELECT * FROM movies WHERE quantity>0 AND " . $type . " LIKE '%" . $term . "%' LIMIT $offset, $no_of_records_per_page";
        $result = $conn->query($sql);

    }else{
        $total_pages_sql = "SELECT COUNT(*) FROM movies";
        $pages =$conn->query($total_pages_sql);
        $total_rows = mysqli_fetch_array($pages)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);
        $sql = "SELECT *  FROM movies WHERE quantity>0 LIMIT $offset, $no_of_records_per_page ";
        $result = $conn->query($sql);

    }

    ?>


<div class="container">
<div class="row">

    <?php
if ($result->num_rows > 0){

    while($row = $result->fetch_assoc()) {
?>

        <div class="col-sm-3 text-center">
        <p> <?php  echo '<img src="../img/'.$row["Img"].'. " style="width:150px;height:200px;" >'; ?>  </br>
            <?php  echo  $row["Name"]  ?>  </br>

            (<?php  echo  $row["Year"]  ?>.)  </br>



            <?php if(isAdmin()) echo "<a href='home.php?ID=". $row["ID_movies"] ."' ><button type=\"button\" class=\"btn btn-danger btn-sm\">Delete</button></a> "; ?>

            <?php if(isLoggedIn() && !isAdmin())  echo"<a href='home.php?ID=". $row["ID_movies"] ."'><button type=\"button\" class=\"btn btn-primary btn-sm\">Posudi</button></a> "; ?>

            <?php

            if(isAdmin()) {


                if (isset($_GET['ID'])) {
                    delete($_GET['ID']);
                    break;
                }

            }

            if(isLoggedIn()) {


                if (isset($_GET['ID'])) {
                    posudi($_GET['ID']);
                    break;
                }

            }



            ?>

            </p>
        </div>


        <?php

  }
} else { echo "0 results"; }


?>


</div>
</div>

<div>
<ul class="pagination justify-content-center" >

    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>"#">&laquo;</a>
    </li>

    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>"#">&raquo;</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
</div>

</body>
</html>