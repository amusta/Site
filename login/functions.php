<?php




	session_start();

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'projekt');

	// variable declaration
	$username = "";
	$email    = "";
	$Name     = "";
	$Last_name= "";
    $Adress   = "";
	$City     = "";
	$Phone    = null;

    $Genre     = "";
    $Year      = null;
    $quantity = null;

	$errors   = array(); 

	// call the register() function if register_btn is clicked
	if (isset($_POST['register_btn'])) {
		register();
	}

if (isset($_POST['create_movie_btn'])) {
    create_movie();
}

	// call the login() function if register_btn is clicked
	if (isset($_POST['login_btn'])) {
		login();
	}


	// REGISTER USER
	function register(){
		global $db, $errors;

		// receive all input values from the form
		$username    =  e($_POST['username']);
		$email       =  e($_POST['email']);
		$password_1  =  e($_POST['password_1']);
		$password_2  =  e($_POST['password_2']);
		$Name        =  e($_POST['Name']);
		$Last_name   =  e($_POST['Last_name']);
		$Adress      =  e($_POST['Adress']);
		$City        =  e($_POST['City']);
		$Phone       =  e($_POST['Phone']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { 
			array_push($errors, "Username is required"); 
		}
		if (empty($email)) { 
			array_push($errors, "Email is required"); 
		}
		if (empty($password_1)) { 
			array_push($errors, "Password is required"); 
		}
		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1);//encrypt the password before saving in the database

			if (isset($_POST['user_type'])) {
				$user_type = e($_POST['user_type']);
				$query = "INSERT INTO user (username, email, user_type, password, Name, Last_name, Adress, City, Phone) 
						  VALUES('$username', '$email', '$user_type', '$password', '$Name', '$Last_name', '$Adress', '$City', '$Phone')";
				mysqli_query($db, $query);
				$_SESSION['success']  = "New user successfully created!!";
				header('location: home.php');
			}else{
				$query = "INSERT INTO user (username, email, user_type, password, Name, Last_name, Adress, City, Phone) 
						  VALUES('$username', '$email', 'user', '$password', '$Name', '$Last_name', '$Adress', '$City', '$Phone')";
				mysqli_query($db, $query);

				// get id of the created user
				$logged_in_user_id = mysqli_insert_id($db);

				$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
				$_SESSION['success']  = "You are now logged in";
				header('location: index.php');				
			}

		}

	}

function create_movie(){
    global $db, $errors;
    // receive all input values from the form


    $Name       =  e($_POST['Name']);
    $Genre      =  e($_POST['Genre']);
    $Year       =  e($_POST['Year']);
    $quantity  = e($_POST['quantity']);
    $Img       = e($_POST['Img']);

    // form validation: ensure that the form is correctly filled
    if (empty($Name)) {
        array_push($errors, "Add movie name");
    }
    if (empty($Genre)) {
        array_push($errors, "Add movie genre");
    }
    if (empty($Year)) {
        array_push($errors, "Add movie year");
    }
    if (empty($quantity)) {
        array_push($errors, "Add quantity movie in store");
    }


    if (count($errors) == 0) {




            $query = "INSERT INTO movies (Name, Genre, Year, quantity, Img) 
						  VALUES('$Name', '$Genre', '$Year', '$quantity', '$Img')";
            mysqli_query($db, $query);
            $_SESSION['success']  = "New movie successfully created!!";
       //     header('location: home.php');








        }


    else{

    }

}

	// return user array from their id
	function getUserById($id){
		global $db;
		$query = "SELECT * FROM user WHERE id=" . $id;
		$result = mysqli_query($db, $query);

		$user = mysqli_fetch_assoc($result);
		return $user;
	}

	// LOGIN USER
	function login(){
		global $db, $username, $errors;

		// grap form values
		$username = e($_POST['username']);
		$password = e($_POST['password']);

		// make sure form is filled properly
		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		// attempt login if no errors on form
		if (count($errors) == 0) {
			$password = md5($password);

			$query = "SELECT * FROM user WHERE username='$username' AND password='$password' LIMIT 1";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) { // user found
				// check if user is admin or user
				$logged_in_user = mysqli_fetch_assoc($results);
				if ($logged_in_user['user_type'] == 'admin') {

					$_SESSION['user'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";
					header('location: admin/home.php');		  
				}else{
					$_SESSION['user'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";

					header('location: ../home.php');
				}
			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}

	function isLoggedIn()

	{
		if (isset($_SESSION['user'])) {
			return true;
		}else{
			return false;
		}
	}

	function isAdmin()
	{
		if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
			return true;
		}else{
			return false;
		}
	}

	// escape string
	function e($val){
		global $db;
		return mysqli_real_escape_string($db, trim($val));
	}

	function display_error() {
		global $errors;

		if (count($errors) > 0){
			echo '<div class="error">';
				foreach ($errors as $error){
					echo $error .'<br>';
				}
			echo '</div>';
		}
	}
	function delete($id){
	    global $db;

        $sql = "DELETE FROM movies WHERE ID_movies='$id'";

        if (mysqli_query($db, $sql)) {
            header('Location: http://localhost/projekt/home.php');
        } else {
            echo "Error: CAN'T DELETE PURCHASED MOVIE <br> DETAILS:" . $sql . "<br>" . mysqli_error($db);
        }
    }

function posudi($id){
    global $db;




       $ID_user = $_SESSION['user']['ID_user'];

        $sql = "INSERT INTO movie_rental  (ID_movies, ID_user, date_purchased, date_of_return) 
						  VALUES('$id', '$ID_user', CURRENT_DATE,CURRENT_DATE + 7 )";

        mysqli_query($db, $sql);

        $sql = "UPDATE movies SET quantity=quantity-1 WHERE ID_movies='$id'";

        mysqli_query($db, $sql);

    header('Location: http://localhost/projekt/home.php');
}
function vrati($id){
    global $db;


    $sql = "DELETE FROM movie_rental WHERE  id='$id'";

    mysqli_query($db, $sql);

    $sql = "UPDATE movies SET quantity=quantity+1 WHERE ID_movies='$id'";

    mysqli_query($db, $sql);

    header('Location: http://localhost/projekt/profile.php');
}


?>