<body style='background:url(wall3.jpg);background-repeat:no-repeat;background-size:100% 130%'>
<?php
include('session.php');
if(!isset($_SESSION['login_user'])){
  header("location: index.php"); // Redirecting To Home Page
}
?>

<html>
<head>
  <title>Your Home Page</title>
  <link href="style.css" rel="stylesheet" type="text/css">
<style>


.button {
  background-color: #f44336; /* Green 4CAF50*/
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  position: relative;
  left: 0px;
}

.button2 {background-color: #FF8E7F;} /* Blue */
.button3 {background-color: #FF8000;} /* Red margin-left: 0px; */
.button4 {background-color: #DA81F5; color: white;} /* Gray */
.button5 {background-color: #4CAF50;} /* Black */
</style>
</head>
<body>

<div id="profile">
      <div id="texto1" style="width: 56%">
        <?php echo "Bem vindo :"; ?>
        <?php echo $login_session; ?>
</div>
        <div id="texto1" style="width: 43%">
      <button class="button button2" onclick="location.href='profile.php'">Medições</button>
      <button class="button button3" onclick="location.href='pacientes.php'">Pacientes</button>
      <button class="button button4" onclick="location.href='funcionarios.php'">Funcionários</button>
      <button class="button button5" onclick="location.href='servico.php'">Serviços</button>
      <button class="button" onclick="location.href='logout.php'">Sair</button>
      </div>
</div>
</body>
</html>




<!DOCTYPE html>
<html>



<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

</style>
<body>
  <?php if ($login_session == "admin") : ?>
<h1>Crie um novo Serviço</h1>

<div id="profile2" >
<form action="servico.php" method="post">
    <label for="fname">Nome do Serviço</label>
    <input type="text" id="fname" name="firstname" placeholder="Nome...">
    <input type="submit" name="submit" onclick="location.href='servico.php'" value="Enviar">
</form>
</div>
  <?php endif; ?>
</body>

<?php
if (isset($_POST['submit']))
{
  if (empty($_POST['firstname'])) {
    echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Preencha todos os campos</div>";
  }else{myfnc();}

}
function myfnc()
{
      //  echo $_POST['firstname'];
      $NomeServico = $_POST['firstname'];
      $link = mysqli_connect("localhost", "root", "", "hospital");

      // Check connection
      if($link === false){
          die("ERROR: Could not connect. " . mysqli_connect_error());
      }

      // Attempt insert query execution
      $sql = "INSERT INTO servico (servico) VALUES ('$NomeServico')";
      if(mysqli_query($link, $sql)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }

      // Attempt insert query execution
      $sql = "ALTER TABLE `funcionarios` ADD `$NomeServico` BOOLEAN NOT NULL";
      if(mysqli_query($link, $sql)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }

      mysqli_close($link);
}
?>
</html>



<!DOCTYPE html>
<html>
<head>
 <title>Table with database</title>
 <style>
  table {
   border-collapse: collapse;
   width: 50%;
   color: #2898cc;
   margin-top: 30px;
   font-family: monospace;
   font-size: 25px;
   text-align: center;
     }
  th {
   background-color: #2898cc;
   color: white;
    }
  tr:nth-child(even) {background-color: #f2f2f2}
 </style>
</head>
<body>
 <table align="center">
 <tr>
  <th>IDS</th>
  <th>SERVIÇO</th>
  <th>DELETE</th>
 </tr>
 <h1>Todos os Serviços atuais</h1>
 <?php

$conn = mysqli_connect("localhost", "root", "", "hospital");
  // Check connection
        if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
        }

        if(isset($_GET['SERVICO'])){
            $idp = $_GET['SERVICO'];
            $sql= "DELETE FROM servico WHERE SERVICO = '$idp'";
            $result = $conn->query($sql);
            $sql= "ALTER TABLE `funcionarios` DROP `$idp`";
            $result = $conn->query($sql);
        }


          $sql = "SELECT * FROM servico";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
           // output data of each row
           while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["IDS"]. "</td><td>" .$row["SERVICO"] ;
            echo "<td>" . '<a id="remove_note"  href="servico.php?SERVICO='.$row['SERVICO'].'">'.'X'.'</a>' . "</td>";
            }
          } else
          { echo "0 results"; }


$conn->close();
?>
</table>
</body>
</html>
