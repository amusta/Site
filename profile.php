<?php
include('connection.php');
include('login/functions.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>


<body>

<style>
    body {
        background: lightblue url("../img/12.png") no-repeat fixed center;
    }


     table, th, td {
         border: 1px solid black;
         border-collapse: collapse;
     }
    th, td {
        padding: 5px;
    }

</style>




<?php


$sql = "SELECT * FROM user WHERE  username = '{$_SESSION['user']['username']}'";
    $result = $conn->query($sql);


        if ($result->num_rows > 0){

            while($row = $result->fetch_assoc()) {



      echo "<h2> Your data  </h2> <b> Name: </b>" . $row["Name"]. "<br><b> Lastname: </b>" . $row["Last_name"] . "<br><b> Address: </b> "
. $row["Adress"]. "<br><b> City: </b>" . $row["City"] . "<br><b> Phone: </b> " . $row["Phone"] . "<br><br><br>";
}
} else { echo "0 results"; }
?>

<table>

    <tr>
        <th>Movie name</th>
        <th>Date of purchase</th>
        <th>Date of return</th>
        <th>Return</th>
    </tr>

<?php
$sql = "SELECT movies.Name,
movie_rental.id, 
movie_rental.date_purchased, 
movie_rental.date_of_return 
FROM movies 
INNER JOIN movie_rental 
ON movies.ID_movies = movie_rental.ID_movies
WHERE  movie_rental.ID_user = '{$_SESSION['user']['ID_user']}'";

$result = $conn->query($sql);


if ($result->num_rows > 0){

    while($row = $result->fetch_assoc()) {



        echo " <tr><td>" . $row["Name"] . "</td><td> "  . $row["date_purchased"]. " </td><td>" . $row["date_of_return"] . " </td>";

        echo" <td><a href='profile.php?ID=". $row["id"] ."' >Vrati</a> </td></tr>";

        if (isset($_GET['ID'])) {
            vrati($_GET['ID']);
            break;
        }

    }
} else { echo "0 results"; }

?>


</table>
</body>
</html>