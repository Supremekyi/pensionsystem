<?php
$con= new mysqli("localhost", "root", "", "dbhrsystem");

$Emp_ID= $_POST['Emp_ID'];


if ($con->connect_error)
{
  die("Failed to connect :".$con->connect_error);
}
else 
{
  $stmt=$con->prepare("select * from tblemppersonalrecords where Emp_ID=?");
  $stmt->bind_param("s", $Emp_ID, );
  $stmt->execute();
  $stmt_result=$stmt->get_result();
    if ($stmt_result->num_rows > 0)
    {
      $data=$stmt_result->fetch_assoc();
        if ($data['Emp_ID']==$Emp_ID)
        {
          echo " ";
        }
        else
        {
          echo "<h2>Invalid ID No.</h2>";
        }
    }else
    {
      echo "<h2>No Results</h2>";
    }
}

$sql = "SELECT * FROM tblemppersonalrecords where Emp_ID=$Emp_ID";
$result = mysqli_query($con, $sql);


//mysqli_close($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pension System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="container vh-100">
		<div class="row justify-content-center h-100">
			<div class="card w-100 my-auto shadow">
				<div class="card-header text-center bg-primary text-white">
					<h2>Pension System</h2>
				</div>
          <div class="card-body">
					  <form action="" method="post">
              <div class="form-group">
                <div class="container">
                             
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Employee's ID</th>
                            <th>Firstname</th>
                            <th>Middlename</th>
                            <th>Surname</th>
                            <th>Address</th>
                            <th>Date of Birth</th>
                            <th>Age</th>
                            <th>Dependents</th>
                            
                            <th>Lump Sum Pension (LSP)</th>
                            <th>Basic Monthly Pension (BMP)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <?php 
                           
                              while($row = mysqli_fetch_assoc($result)) {
                                
                            ?>			
                            <td><?php echo $row['Emp_ID']; ?></td>
                            <td><?php echo $row['FirstName']; ?></td>
                            <td><?php echo $row['MiddleName']; ?></td>
                            <td><?php echo $row['LastName']; ?></td>
                            <td><?php echo $row['Address']; ?></td>
                            <td><?php echo $row['DateOfBirth']; ?></td>
                            <td><?php
                                  $sql1= "SELECT `FirstName`,`DateOfBirth`, CURRENT_DATE(), TIMESTAMPDIFF( YEAR, `DateOfBirth`,CURRENT_DATE()) AS age FROM tblemppersonalrecords";
                                  $result1 = mysqli_query($con, $sql1);
                                  $row1 = mysqli_fetch_assoc($result1);
                                  echo $row1['age'];
                                ?>
                            </td>
                            <td><?php echo $row['dependents']; ?></td>
                            <td><?php
                                  $sql2= "SELECT * FROM tblrankhistory where Emp_ID=$Emp_ID";
                                  $result2 = mysqli_query($con, $sql2);
                                  $row2 = mysqli_fetch_assoc($result2);
                                  
                                  $sql3= "SELECT * FROM tblrank where Rank_ID=$row2[Rank_ID]";
                                  $result3 = mysqli_query($con, $sql3);
                                  $row3 = mysqli_fetch_assoc($result3);
                                  //echo $row3['SalaryAmount'];
                                  $LSP=$row3['SalaryAmount']*18;
                                  if ($row['dependents']>=4)
                                  {
                                    $tax=$LSP*.10;
                                    $netLSP=$LSP-$tax;
                                    echo $netLSP;
                                  }
                                  else{
                                    $tax=$LSP*.12;
                                    $netLSP=$LSP-$tax;
                                    echo $netLSP;


                                  }
                                ?></td>
                            <td><?php  
                                 $sql4= "SELECT `Emp_ID`,`DateEffectivity`, CURRENT_DATE(), TIMESTAMPDIFF( YEAR, `DateEffectivity`,CURRENT_DATE()) AS age FROM tblrankhistory where Emp_ID=$Emp_ID";
                                 $result4 = mysqli_query($con, $sql4);
                                 $row4 = mysqli_fetch_assoc($result4);
                                 
                                
                                  $monthservice= $row4['age']*12;
                                  $BMP=((0.025*$row3['SalaryAmount'])*$monthservice)/12;
                                  echo $BMP;
                                ?></td>
                            <?php 
    		                      }
                            //}
                            ?>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                
						      
					  </form>
				</div>
				<div class="card-footer text-right">
					<small>&copy; KYI Solutions</small>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

                            