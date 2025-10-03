
<?php
session_start();
if(!isset($_SESSION['loginid'])){ header('location:manager_login.php');}
?>
<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Bankist</title>
    <link href="images/bank.jpg" rel="icon">
   <link href="images/bank.jpg" rel="apple-touch-icon">

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.scss"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   </head>

   <?php require 'includes/db_conn.php'; ?>
  <?php require 'includes/function.php'; ?>

   <body>
      <nav>
      
         <div class="logo" >
         <img src="images/bank.webp" width="45" alt="" class="logo-img">
          Bankist
         </div>
        <style> 
        .logo-img{
            margin-bottom: -9px;
        }
        </style>
         <input type="checkbox" id="click">
         <label for="click" class="menu-btn">
         <i class="fas fa-bars"></i>
         </label>
         <ul>
            <li><a class="active" href="#">Home</a></li>
            <li><a href="manager_accounts.php">Accounts</a></li>
            <li><a href="addnewaccount.php">Add new Account</a></li>
            <li><a href="manager_feedback.php">Feedback</a></li>
            <li><a class="active" href="logout.php">Logout</a></li>
         </ul>
      </nav>

      <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
} 
nav{
  display: flex;
  height: 80px;
  width: 100%;
  background: #1b1b1b;
  align-items: center;
  justify-content: space-between;
  padding: 0 50px 0 100px;
  flex-wrap: wrap;
}
nav .logo {
    color: #fff;
    font-size: 29px;
    font-weight: 600;
    /* left: 12px; */
    /* left: 27px; */
    /* align-content: baseline; */
    /* align-items: baseline; */
    margin-left: -24px;
}
nav ul{
  display: flex;
  flex-wrap: wrap;
  list-style: none;
}
nav ul li{
  margin: 0 5px;
}
.logo-img {
    margin-bottom: 6px;
}
nav ul li a{
  color: #f2f2f2;
  text-decoration: none;
  font-size: 18px;
  font-weight: 500;
  padding: 8px 15px;
  border-radius: 5px;
  letter-spacing: 1px;
  transition: all 0.3s ease;
}
nav ul li a.active,
nav ul li a:hover{
  color: #111;
  background: #fff;
}
nav .menu-btn i{
  color: #fff;
  font-size: 22px;
  cursor: pointer;
  display: none;
}
input[type="checkbox"]{
  display: none;
}
@media (max-width: 1000px){
  nav{
    padding: 0 40px 0 50px;
  }
}
@media (max-width: 920px) {
  nav .menu-btn i{
    display: block;
  }
  #click:checked ~ .menu-btn i:before{
    content: "\f00d";
  }
  nav ul{
    position: fixed;
    top: 80px;
    left: -100%;
    background: #111;
    height: 100vh;
    width: 100%;
    text-align: center;
    display: block;
    transition: all 0.3s ease;
  }
  #click:checked ~ ul{
    left: 0;
  }
  nav ul li{
    width: 100%;
    margin: 40px 0;
  }
  nav ul li a{
    width: 100%;
    margin-left: -100%;
    display: block;
    font-size: 20px;
    transition: 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }
  #click:checked ~ ul li a{
    margin-left: 0px;
  }
  nav ul li a.active,
  nav ul li a:hover{
    background: none;
    color: cyan;
  }
}
.content{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  z-index: -1;
  width: 100%;
  padding: 0 30px;
  color: #1b1b1b;
}
.content div{
  font-size: 40px;
  font-weight: 700;
}
dl, ol, ul {
    margin-top: 12px;
    margin-bottom: 1rem;
}
</style>
			
			<body>  
  
<div class="card-body">
				
<h1 style="text-align:center; color:#CC3300;" >Accounts</h1>
              <div class="table-responsive">
               
       
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" >
               
                  <thead>
                    <tr>
                      <th>Id</th>
					  <th>Profile Picture</th>
                      <th>Holder Name</th>
                      <th>Account No.</th>
                      <th>Gender</th>
                      <th>Current Balance	</th>
                      <th>Account type	</th>
                      <th>Contact No.</th>
                      <th>Time</th>
                      <th>view</th>
                      <th>Send Notice</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  
				  <tbody>
          <?php
           $con = new mysqli('localhost','root','','charusat_bank');
           if (isset($_GET['delete'])) 
  {
    if ($con->query("delete from useraccounts where id = '$_GET[delete]'"))
    {
     
    }
  }
  ?>


          <?php
           $con = new mysqli('localhost','root','','charusat_bank');

           $ar = $con->query("select * from userAccounts");
           $userData = $ar->fetch_assoc();
      $i=0;
      $array = $con->query("select * from useraccounts");
      if ($array->num_rows > 0)
      {
        while ($row = $array->fetch_assoc())
        {$i++;
    ?>
      <tr>
        <th scope="row"><?php echo $i ?></th>
        <td>
         <center> <img src="<?php echo "images/".$row['profile'];?>"width="80px " height="80px "alt="image";></center>
        </td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo $row['accountno'] ?></td>
        <td><?php echo $row['gender'] ?></td>
        <td>$ .<?php echo $row['deposit'] ?></td>
        <td><?php echo $row['accounttype'] ?></td>
        <td><?php echo $row['phonenumber'] ?></td>
        <td><?php echo $row['time'] ?></td>
        <td>
          <a href="view.php?id=<?php echo $row['id'] ?>" class='btn btn-success btn-sm' data-toggle='tooltip' title="View More info">View</a></td>
<td>          <a href="manager_notice.php?id=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm" >Send Notice</button>
        </td>


          <td> <a href="manager_home.php?delete=<?php echo $row['id'] ?>" class='btn btn-danger btn-sm'  data-toggle='tooltip' title="Delete this account">Delete</a></td>
        
        
      </tr>
  

          
    </div>
  </div>
</div>
</form>


      <?php
        }
      }
     ?>
  </tbody> 
                </table>
              </div>
            </div>






</body>
</html>
