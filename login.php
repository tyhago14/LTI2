<body style='background:url(wall3.jpg);background-repeat:no-repeat;background-size:100% 130%'>
<?php
session_start(); // Starting Session
$error = ''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
  if (empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Nome de utilizador ou palavra passe errada";
  }
  else{
    // Define $username and $password
    $username = $_POST['username'];
    $password = $_POST['password'];
    // mysqli_connect() function opens a new connection to the MySQL server.
    $conn = mysqli_connect("localhost", "root", "", "hospital");
    // SQL query to fetch information of registerd users and finds user match.
    $query = "SELECT username, password from contas where username=? AND password=? LIMIT 1";
    // To protect MySQL injection for Security purpose
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($username, $password);
    $stmt->store_result();
    if($stmt->fetch()) //fetching the contents of the row {
    {$_SESSION['login_user'] = $username;}   // Initializing Session
    else{
        $error = "Nome de utilizador ou palavra passe errada";
      }
    }
    header("location: profile.php"); // Redirecting To Profile Page
    mysqli_close($conn); // Closing Connection
  }

?>
