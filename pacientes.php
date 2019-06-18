

<body style='background:url(wall3.jpg);background-repeat:no-repeat;background-size:100% 130%'>
<?php
include('session.php');
if(!isset($_SESSION['login_user'])){
  header("location: index.php"); // Redirecting To Home Page
}
?>

<?php
$conn = mysqli_connect("localhost", "root", "", "hospital");

$ArrayFunc = [];
$Y = 0;
$FUNC = False;
$sql = "SELECT username FROM funcionarios";
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)){
  $ArrayFunc[$Y] =  $row['username'];
  $Y++;
}

if (in_array($login_session, $ArrayFunc)) {
    $FUNC = True;
}



$sql = "SELECT IDP FROM pacientes WHERE SENSOR != 0";
$result = mysqli_query($conn,$sql);
$NumeroPac = $result->num_rows;


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
  <?php if ($login_session == "admin" || $FUNC == True) : ?>

    <h1>Estatísticas dos Pacientes</h1>

<?php
 $con = mysqli_connect("localhost", "root", "", "hospital");
?>
<!DOCTYPE HTML>
<html>
<head>
 <meta charset="utf-8">
 <title>TechJunkGigs</title>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([

 ['class Name','Students'],
 <?php
			$query = "SELECT SERVICO, COUNT(IDP) TOTAL FROM pacientes WHERE SENSOR != 0 GROUP BY SERVICO";
      $A =5;
			 $exec = mysqli_query($con,$query);
			 while($row = mysqli_fetch_array($exec)){

			 echo "['".$row['SERVICO']."',".$row['TOTAL']."],";
			 }
			 ?>

 ]);

 var options = {
 title: 'Pacientes por Serviço hospitalar',
 backgroundColor: { fill: "#2898cc",fillOpacity: 0.45 } ,
 is3D: true
 ,chartArea:{left:30,top:30,bottom:30,width:"100%",height:"100%"}
 ,height: 300
 };
 var chart = new google.visualization.PieChart(document.getElementById("columnchart12"));
 chart.draw(data,options);

 }

    </script>

</head>
<body>

 <div class="container-fluid">
   <div id="columnchart12"></div>
   <div id="profile2" ><?php echo "Número de Pacientes atualmente no hospital: ",$NumeroPac ?></div>
 </div>

</body>
</html>



<h1>Adicione um novo Paciente</h1>

<div id="profile2" >
<form action="pacientes.php" method="post">
    <label for="fname">Nome do Paciente</label>
    <input type="text" id="fname" name="nome" placeholder="Nome...">
    <label for="fname">Nome de Apelido</label>
    <input type="text" id="fname" name="apelido" placeholder="Apelido...">
    <label for="fname">Indique qual área de internamento</label>
    <input type="text" id="fname" name="area" placeholder="Area...">
    <label for="fname">Número do sensor</label>
    <input type="text" id="fname" name="sensor" placeholder="Sensor...">
    <label for="fname">Número do NIF</label>
    <input type="text" id="fname" name="nif" placeholder="NIF...">
    <label for="fname">Serviço</label>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "hospital");
     $arr = array();
     $x=0;
      if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
      }

    $sql = "SELECT SERVICO FROM servico";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
      $arr[$x] = $row["SERVICO"];
      $x++;
      }
    }
    $conn->close();
     ?>

     <select name="ServicoPas">
       <?php
       $a = 0;
           while($a != $x)
           {
             echo "<option value=$arr[$a] >$arr[$a]</option>";
             $a++;
           }
        ?>
     </select>

    <input type="submit" name="submit2" onclick="location.href='pacientes.php'" value="Enviar">
</form>
</div>
  <?php endif; ?>
</body>

<?php


if (isset($_POST['submit2']))
{
  if (empty($_POST['nome']) || empty($_POST['apelido']) || empty($_POST['sensor']) || empty($_POST['area']) || empty($_POST['nif'])) {
    echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Preencha todos os campos</div>";
  }else{myfnc($arr,$x);}



}
function myfnc($arr,$x)
{

      $SerPas = $_POST['ServicoPas'];
      $NomePas = $_POST['nome'];
      $ApelidoPas = $_POST['apelido'];
      $AreaPas = $_POST['area'];
      $SensorPas = $_POST['sensor'];
      $NifPas = $_POST['nif'];

      $link = mysqli_connect("localhost", "root", "", "hospital");

      // Check connection
      if($link === false){
          die("ERROR: Could not connect. " . mysqli_connect_error());
      }

      $sql = "INSERT INTO `contas` (`tipo`, `username`, `password`)
      VALUES ('Pacientes', '{$NomePas}', '{$NifPas}')";
      if(mysqli_query($link, $sql)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }

      $sql = "INSERT INTO `pacientes` (`IDP`, `SENSOR`, `SERVICO`, `AREA`,`NOME`,`APELIDO`,`NIF`)
      VALUES (NULL, '{$SensorPas}', '{$SerPas}', '{$AreaPas}', '{$NomePas}', '{$ApelidoPas}', '{$NifPas}')";
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
   width: 70%;
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
  <?php if ($login_session == "admin" || $FUNC == True) : ?>
  <h1>Lista de todos os Pacientes</h1>
 <table align="center">
   <tr>
     <th>NOME</th>
     <th>APELIDO</th>
     <th>NIF</th>
     <th>SERVICO</th>
     <th>AREA</th>
     <th>SENSOR</th>
     <th>IDP</th>
     <th>DELETE</th>
   </tr>

<?php
$conn = mysqli_connect("localhost", "root", "", "hospital");

$myArray = [];
$fullArray = [];

$sql = "SHOW COLUMNS FROM funcionarios";
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($result)){
  $fullArray[] = (string) $row['Field'];
}

$queryuser = sprintf("SELECT * FROM funcionarios WHERE username = '$login_session'");
$resultuser = $conn->query($queryuser);

$news = mysqli_fetch_array($resultuser);

$l = 0;
@error_reporting(E_ALL ^ E_NOTICE);  // é para desligar um notice que ele esta a aceder ao fullArray quando esta null apesar de ter de fazer isso q

while (!is_null($fullArray[$l]))
{
    if ($news[$l] == 1)
    {
      $myArray[] = $fullArray[$l];
    }
    $l++;
}

?>

<div class="container">
        <!-- Example columns -->
        <div class="row">
            <div class="col">
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "root";

              $conn = mysqli_connect("localhost", "root", "", "hospital");
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }


            if(isset($_GET['NOME'],$_GET['IDP'])){
                $nome = $_GET['NOME'];
                $idp = $_GET['IDP'];

          //      $sql= "DELETE FROM pacientes WHERE NOME = '$nome'";
          //      $result = $conn->query($sql);
          //      $sql= "DELETE FROM `contas` WHERE `contas`.`username` = '$nome'";
          //      $result = $conn->query($sql);
                $sql= "UPDATE `pacientes` SET `SENSOR` = '0' WHERE `pacientes`.`IDP` = $idp;";
                $result = $conn->query($sql);
          //    $sql= "UPDATE `medicoes` SET `SENSOR` = '' WHERE `medicoes`.`IDP` = $idp";
        //      $result = $conn->query($sql);


}


            $i = 3;
            while ($i < count($myArray))
            {
                echo $myArray[$i];
                $i++;
            }
            //SET a := $myArray[0];
            //echo @a;

            $ids = join("','",$myArray);

            if($login_session == "admin")
            {
              $sql = sprintf("SELECT * FROM pacientes");
            }else{$sql = sprintf("SELECT * FROM pacientes WHERE SERVICO IN ('$ids')");}


                $result = $conn->query($sql);

            echo "<tr><td>" . $row["NOME"]. "</td><td>" .$row["APELIDO"]. "</td><td>" . $row["NIF"] . "</td><td>". $row["SERVICO"] . "</td><td>". $row["AREA"] . "</td><td>". $row["SENSOR"] . "</td><td>". $row["IDP"]. "</td><td>";
            while($row = mysqli_fetch_assoc($result))
            {
                echo "<tr>";
                echo "<td>" . $row['NOME'] . "</td>";
                echo "<td>" . $row['APELIDO'] . "</td>";
                echo "<td>" . $row['NIF'] . "</td>";
                echo "<td>" . $row['SERVICO'] . "</td>";
                echo "<td>" . $row['AREA'] . "</td>";
                echo "<td>" . $row['SENSOR'] . "</td>";
                echo "<td>" . $row['IDP'] . "</td>";
                echo "<td>" . '<a id="remove_note"  href="pacientes.php?NOME='.$row['NOME'].'&IDP='.$row['IDP'].' ">'.'X'.'</a>' . "</td>";
                echo "</tr>";
            }
            mysqli_close($conn);
            ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
     </tr>




<!DOCTYPE html>
   <html>
     <body>
      <?php if ($login_session != "admin" && $FUNC != True) : ?>
       <h1>Não tem acesso a esta área!</h1>
       <?php endif; ?>
     </body>
  </html>
