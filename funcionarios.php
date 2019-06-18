

<?php
include('session.php');
if(!isset($_SESSION['login_user'])){
  header("location: index.php"); // Redirecting To Home Page
}
?>

<?php
$sql = "SELECT IDF FROM funcionarios";
$result = mysqli_query($conn,$sql);
$NumeroFunc = $result->num_rows;

 ?>


  <body style='background:url(wall3.jpg);background-repeat:no-repeat;background-size:100% 130%'>
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


<?php
$conn = mysqli_connect("localhost", "root", "", "hospital");

  $x = 0;
  $arr = array();

  if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
  }

   $sql = "SHOW COLUMNS FROM funcionarios";
   $result = mysqli_query($conn,$sql);
   while($row = mysqli_fetch_array($result)){
       //echo $row['Field']."<br>";
    //   echo "$x ";
       $arr[$x] = $row['Field'];
      // echo "$arr[$x] ";
       $x++;

   }

$conn->close();
//echo  "<tr><td>" . $tabela2;
?>



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

.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  margin-top: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;

}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;

}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #ffffff;
  border:1px solid black;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

</style>
<body>
    <?php if ($login_session == "admin") : ?>


<h1>Estatísticas dos Funcionários</h1>

<div id="profile2" >
<?php echo "Número de Funcionários no hospital: $NumeroFunc"; ?>

</div>

<h1>Adicione um novo funcionário</h1>

<div id="profile2" >
<form action="funcionarios.php" method="post">
    <label for="fname">Nome de funcionário</label>
    <input type="text" id="fname" name="nome" placeholder="Nome...">
    <label for="fname">Password</label>
    <input type="text" id="fname" name="pass" placeholder="Password...">
    <label for="fname">Indique os Serviços</label>
    <input type="text" id="fname" name="serv" placeholder="Cardiologia, Oncologia, ....">




<?php
    /*$i=3;
    while ($i<=$x-1):

?>

    <label class="container"><?php echo $arr[$i]?>
         <input type="checkbox" name="check_list[]" value="value 1">
    <input type="checkbox" name="check_list[]" value="value 2">
    <input type="checkbox" name="check_list[]" value="value 3">
    <input type="checkbox" name="check_list[]" value="value 4">
    <input type="checkbox" name="check_list[]" value="value 5">
        <span class="checkmark"></span>

    </label>

<?php
    $i=$i+1;
    endwhile;
    */
?>




    <input type="submit" name="submit2" onclick="location.href='funcionarios.php'" value="Enviar">
</form>
</div>
  <?php endif; ?>
</body>

<?php



if (isset($_POST['submit2']))
{
  if (empty($_POST['nome']) || empty($_POST['pass']) || empty($_POST['serv'])) {
    echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Preencha todos os campos</div>";
  }

  $ServicosEscolhidos= $_POST['serv'];
  $SerArray = explode(" ", $ServicosEscolhidos);
  $numeros = count($SerArray);
  $numeros2 = count($arr);
  $q = 0;
  $w = 0;
  $Novo = False;

  while($q != $numeros2){
    while($w != $numeros)
    {
    /*  echo $arr[$q] ;
      echo $SerArray[$w] ;*/
      if($arr[$q] == $SerArray[$w])
      {
        $Novo = true;
      }
      $w++;
    }
    $w = 0;
    $q++;
  }

if ($Novo == true) {
  myfnc($arr,$x);
}else{
  echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Preencha com serviços válidos</div>";
}
}

function myfnc($arr,$x)
{
      $NumS = $x - 3;
      $NomeFun = $_POST['nome'];
      $PassFun = $_POST['pass'];
      $Servic= $_POST['serv'];

      $pieces = explode(" ", $Servic);
      $numero = count($pieces);
  /*    echo $numero;
      echo $pieces[0]; // piece1
      echo " ";
      echo $pieces[1]; // piece2*/
      $link = mysqli_connect("localhost", "root", "", "hospital");

      // Check connection
      if($link === false){
          die("ERROR: Could not connect. " . mysqli_connect_error());
      }

      $sql = "INSERT INTO `contas` (`tipo`, `username`, `password`)
      VALUES ('Funcionarios', '{$NomeFun}', '{$PassFun}')";
      if(mysqli_query($link, $sql)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }


      $sql = "INSERT INTO `funcionarios` (`IDF`, `username`, `password`)
      VALUES (NULL, '{$NomeFun}', '{$PassFun}')";
      if(mysqli_query($link, $sql)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }


      $p = 0;
      while($p != $numero)
      {
        $sql = "UPDATE `funcionarios` SET `{$pieces[$p]}` = '1' WHERE `funcionarios`.`username` = '{$NomeFun}'";
        if(mysqli_query($link, $sql)){
            echo "Records inserted successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
        $p++;
      }

/*
      $k = 3;
      while($k != $x)
      {
        $sql = "UPDATE `funcionarios` SET `{$arr[$k]}` = '1' WHERE `funcionarios`.`username` = '{$NomeFun}'";
      //  echo $sql;
        if(mysqli_query($link, $sql)){
            echo "Records inserted successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
          $k++;
      }
*/


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
  <?php if ($login_session == "admin" ) : ?>
    <h1>Lista de todos os Funcionários</h1>
 <table align="center">
 <tr>
   <th>NOME</th>
   <th>PASSWORD</th>
   <th>IDF</th>
   <th>DELETE</th>
 </tr>
 <?php
$conn = mysqli_connect("localhost", "root", "", "hospital");
  // Check connection
        if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
        }

        if(isset($_GET['username'])){
            $idp = $_GET['username'];
            $sql= "DELETE FROM funcionarios WHERE username = '$idp'";
            $result = $conn->query($sql);
            $sql= "DELETE FROM `contas` WHERE `contas`.`username` = '$idp'";
            $result = $conn->query($sql);
        }

          $sql = "SELECT * FROM funcionarios";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
           // output data of each row
           while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["username"]. "</td><td>" .$row["password"]. "</td><td>" . $row["IDF"] . "</td>";
            echo "<td>" . '<a id="remove_note"  href="funcionarios.php?username='.$row['username'].'">'.'X'.'</a>' . "</td>";
            echo "</tr>";
            }
          } else
          { echo "0 results"; }


$conn->close();
?>
</table>
<?php endif; ?>
</body>
</html>

<!DOCTYPE html>
   <html>
     <body>
      <?php if ($login_session != "admin" ) : ?>
       <h1>Não tem acesso a esta área!</h1>
       <?php endif; ?>
     </body>
  </html>
