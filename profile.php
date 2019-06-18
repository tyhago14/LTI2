<body style='background:url(wall3.jpg);background-repeat:no-repeat;background-size:100% 130%'>
<?php
include('session.php');
if(!isset($_SESSION['login_user'])){
  header("location: index.php"); // Redirecting To Home Page
}
?>
<body style='background-color:#dedffc'>
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





<?php
$conn = mysqli_connect("localhost", "root", "", "hospital");

  $tabela1 = 0;
  $tabela2 = 0;
  $tabela3 = 0;
  $tabela4 = 0;
  $tabela5 = 0;
  if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
  }


 $x=0;
    $sql = "SELECT * FROM temp2";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
      $arr[$x][1] = $row["SENSOR"]; // NA coluna 1 ficam os sensores
      $arr[$x][4] = $row["DATA"]; // NA coluna 4 ficam as datas
      $arr[$x][5] = $row["ESTADO"];// NA coluna 5 ficam os ESTADOS
      $x++;
      }
    }
    $sqldel = "TRUNCATE TABLE temp2;"; // limpamos a temp2
    $resultdel= $conn->query($sqldel);

    //ARR contem tudo de temp2

$l = 0;
@error_reporting(E_ALL ^ E_NOTICE);  // é para desligar um notice que ele esta a aceder ao fullArray quando esta null apesar de ter de fazer isso q
while (!is_null($arr[$l]))
{
  $vsensor = $arr[$l][1];

  $sql2 = "SELECT IDP, SERVICO, AREA FROM pacientes WHERE SENSOR = $vsensor";
  $result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {

     //metemos em arr os valores associados a esse SENSOR
     while($row2 = $result2->fetch_assoc()) {
      $arr[$l][0] = $row2["IDP"]; // NA coluna 0 fica o ID do Paciente
      $arr[$l][2] = $row2["SERVICO"]; // NA coluna 2 fica o serviço em que esta registado o paciente
      $arr[$l][3] = $row2["AREA"];// NA coluna 3 fica a area onde se encontra o paciente
      $x++;
      }
    }

  $vdata = $arr[$l][4];
  $vestado = $arr[$l][5];
  $vidp = $arr[$l][0];
  $vservico = $arr[$l][2];
  $varea = $arr[$l][3];



  $sqlinsert = "INSERT INTO `medicoes` (`IDP`, `SENSOR`, `SERVICO`, `AREA`,`DATA`,`ESTADO`)
      VALUES ('{$vidp}', '{$vsensor}', '{$vservico}', '{$varea}', '{$vdata}', '{$vestado}')";
      if(mysqli_query($conn, $sqlinsert)){
          echo "Records inserted successfully.";
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
      }


  $l++;
}


?>

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

hr {
  display: block;
  margin-top:1em;
  margin-bottom: 1em;
  margin-left: 15px;
  margin-right: 15px;
  border-style: inset;
  border-width: 2px;
}

</style>
<body>
 <?php if ($login_session == "admin" || $FUNC == True) : ?>
<h1>Pesquise por um paciente para ver as suas medições</h1>

<div id="profile2" >
<form action="profile.php" method="post">
    <label for="fname">Sensor</label>
    <input type="text" id="fname" name="sensor" placeholder="Sensor...">
    <hr>
    <label for="pnome">Primeiro Nome</label>
    <input type="text" id="pnome" name="pnome" placeholder="Primeiro Nome...">
    <label for="unome">Ultimo Nome</label>
    <input type="text" id="unome" name="unome" placeholder="Ultimo Nome...">
    <hr>
    <label for="idp">IDP</label>
    <input type="text" id="idp" name="idp" placeholder="ID do Paciente...">
    <input type="submit" name="submit" onclick="location.href='profile.php'" value="Pesquisar">
</form>
</div>
  <?php endif; ?>
</body>

<!DOCTYPE html>
<html>
<head>
 <title>Table with database</title>
 <style>
  table {
   border-collapse: collapse;
   width: 85%;
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
  <th>IDP</th>
  <th>SENSOR</th>
  <th>SERVICO</th>
  <th>AREA</th>
  <th>DATA</th>
  <th>ESTADO</th>
 </tr>

 <?php




if ($login_session == "admin" || $FUNC == True){

if (isset($_POST['submit']))
{
  if ( (!empty($_POST['sensor'])) || (!empty($_POST['idp'])) || ( (!empty($_POST['pnome'])) && (!empty($_POST['unome']))) )
  {
        myfnc();
  }
  else{
    echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Preencha todos os campos</div>";
  }

}
}
else
{
myfnccli($login_session);
}

function myfnc()

{


$conn = mysqli_connect("localhost", "root", "", "hospital");




      $sensor= $_POST['sensor'];
      $idp = $_POST['idp'];
      $primeiro = $_POST['pnome'];
      $ultimo = $_POST['unome'];

      if( (!empty($_POST['pnome'])) && (!empty($_POST['unome'])) )
      {
        $sqlidp = "SELECT IDP FROM pacientes WHERE (NOME = '$primeiro') AND (APELIDO = '$ultimo')";
        $resultidp = $conn->query($sqlidp);
       if ($resultidp->num_rows > 0)
       {
          while($row2 = $resultidp->fetch_assoc())
          {
              $idp = $row2["IDP"];
          }
        }
      }




  // Check connection
        if ($conn->connect_error)
        {
         die("Connection failed: " . $conn->connect_error);
        }

          $sql = "SELECT * FROM medicoes WHERE (SENSOR = $sensor) ";
          $result = $conn->query($sql);
          if ($result->num_rows > 0)
          {
           // output data of each row
           while($row = $result->fetch_assoc())
           {
            echo "<tr><td>" . $row["IDP"]. "</td><td>" .$row["SENSOR"]. "</td><td>" . $row["SERVICO"] . "</td><td>". $row["AREA"] . "</td><td>". $row["DATA"] . "</td><td>". $row["ESTADO"] . "</td><td>";
            $Found = true;
            }
          }

          $sql = "SELECT * FROM medicoes WHERE (IDP = $idp)";
          $result = $conn->query($sql);
          if ($result->num_rows > 0)
          {
           // output data of each row
           while($row = $result->fetch_assoc())
           {
            echo "<tr><td>" . $row["IDP"]. "</td><td>" .$row["SENSOR"]. "</td><td>" . $row["SERVICO"] . "</td><td>". $row["AREA"] . "</td><td>". $row["DATA"] . "</td><td>". $row["ESTADO"] . "</td><td>";
            $Found = true;
            }
          }



$conn->close();
if (!$Found == true)
{
  echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Nenhum registo encontrado</div>";
}
}


function myfnccli($tipo)

{


$conn = mysqli_connect("localhost", "root", "", "hospital");


        $sqlcli = "SELECT IDP FROM pacientes WHERE (NOME = '$tipo')";
        $resultcli = $conn->query($sqlcli);
       if ($resultcli->num_rows > 0)
       {
        while($row3 = $resultcli->fetch_assoc())
        {
            echo $idp;
            $idp = $row3["IDP"];
        }
        }

        $sql = "SELECT * FROM medicoes WHERE (IDP = $idp)";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
           // output data of each row
           while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["IDP"]. "</td><td>" .$row["SENSOR"]. "</td><td>" . $row["SERVICO"] . "</td><td>". $row["AREA"] . "</td><td>". $row["DATA"] . "</td><td>". $row["ESTADO"] . "</td><td>";
            $Found = true;

            }
          }


if (!$Found == true)
{
  echo "<div style ='font:25px Arial,tahoma,sans-serif;color:#ff0000'> Nenhum registo encontrado</div>";
}
}

?>
</table>



</body>

</html>





</html>
