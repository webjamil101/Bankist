<?php
session_start();
if(!isset($_SESSION['userid'])){ header('location:login.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bankist | Funds Transfer</title>
    <link href="images/bank.webp" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda+One">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php 
$con = new mysqli('localhost','root','','charusat_bank');
$ar = $con->query("select * from useraccounts where id = '$_SESSION[userid]'");
$userData = $ar->fetch_assoc();
?>

<?php require 'includes/function.php'; ?>

<body>
<style>
:root {
    --primary: #5d78ff;
    --primary-light: #eef1ff;
    --secondary: #2ecc71;
    --dark: #2c3e50;
    --light: #f8f9fa;
    --danger: #e74c3c;
    --warning: #f39c12;
    --info: #3498db;
    --gray: #95a5a6;
}

body {
    background: #f5f7fb;
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.bankist-navbar {
    background: linear-gradient(135deg, var(--primary) 0%, #3a56e4 100%);
    padding: 12px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
}

.bankist-navbar .navbar-brand {
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
}

.bankist-navbar .navbar-brand img {
    margin-right: 10px;
}

.bankist-navbar .nav-link {
    color: rgba(255,255,255,0.85) !important;
    font-weight: 500;
    padding: 8px 16px !important;
    margin: 0 4px;
    border-radius: 6px;
    transition: all 0.3s;
}

.bankist-navbar .nav-link:hover, .bankist-navbar .nav-link.active {
    background: rgba(255,255,255,0.15);
    color: white !important;
}

.bankist-navbar .nav-item.dropdown .dropdown-menu {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 8px 0;
}

.bankist-navbar .nav-item.dropdown .dropdown-item {
    padding: 8px 16px;
    color: var(--dark);
    display: flex;
    align-items: center;
}

.bankist-navbar .nav-item.dropdown .dropdown-item i {
    margin-right: 8px;
    width: 20px;
    text-align: center;
}

.bankist-navbar .nav-item.dropdown .dropdown-item:hover {
    background: var(--primary-light);
    color: var(--primary);
}

.balance-badge {
    background: rgba(255,255,255,0.2);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.balance-badge:hover {
    background: rgba(255,255,255,0.25);
}

.notification-btn, .message-btn {
    background: rgba(255,255,255,0.15);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 5px;
    transition: all 0.3s;
}

.notification-btn:hover, .message-btn:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.user-dropdown {
    display: flex;
    align-items: center;
    color: white;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 30px;
    transition: all 0.3s;
}

.user-dropdown:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    text-decoration: none;
}

.user-dropdown img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    margin-right: 8px;
    border: 2px solid rgba(255,255,255,0.3);
}

.transfer-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 0 15px;
}

.transfer-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    padding: 30px;
    margin-bottom: 30px;
    border: none;
    position: relative;
}

.transfer-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, var(--primary), var(--secondary));
}

.transfer-header {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.transfer-header i {
    font-size: 28px;
    color: var(--primary);
    margin-right: 15px;
}

.transfer-header h2 {
    color: var(--dark);
    font-weight: 700;
    margin: 0;
}

.form-label {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #e1e5eb;
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(93, 120, 255, 0.25);
}

.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #4a63e0;
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--gray);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.account-info-card {
    background: var(--primary-light);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid var(--primary);
}

.account-info-card h5 {
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 15px;
}

.transfer-history {
    margin-top: 40px;
}

.transfer-history h3 {
    color: var(--dark);
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 10px;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.transaction-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.transaction-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 18px;
    background: var(--warning);
}

.transaction-details {
    flex: 1;
}

.transaction-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 5px;
}

.transaction-date {
    color: var(--gray);
    font-size: 0.85rem;
}

.transaction-amount {
    font-weight: 700;
    color: var(--danger);
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
}

.alert-warning {
    background: #fff8e6;
    color: #856404;
    border-left: 4px solid var(--warning);
}

.alert-info {
    background: #e6f3ff;
    color: #0c5460;
    border-left: 4px solid var(--info);
}

.alert-danger {
    background: #fde8e8;
    color: #721c24;
    border-left: 4px solid var(--danger);
}

.input-group-append .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

@media (max-width: 768px) {
    .transfer-container {
        margin: 15px auto;
    }
    
    .transfer-card {
        padding: 20px;
    }
    
    .bankist-navbar .navbar-nav {
        margin-top: 15px;
    }
    
    .bankist-navbar .nav-link {
        margin: 2px 0;
    }
}
</style>

<nav class="navbar navbar-expand-xl bankist-navbar">
    <div class="container">
        <a href="home.php" class="navbar-brand">
            <img src="images/bank.webp" width="40" alt="Bankist Logo">
            Bankist
        </a>
    
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon" style="color: white; filter: invert(1);">☰</span>
        </button>
        
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <div class="navbar-nav mr-auto">
                <a href="home.php" class="nav-item nav-link">Dashboard</a>
                <a href="account.php" class="nav-item nav-link">Accounts</a>
                <a href="statement.php" class="nav-item nav-link">Statements</a>
                <a href="funds_transfer.php" class="nav-item nav-link active">Transfers</a>
            </div>

            <div class="navbar-nav ml-auto d-flex align-items-center">
                <a href="" class="btn balance-badge">
                    <i class="fa fa-wallet mr-2"></i> 
                    $. <?php echo number_format($userData['deposit'], 2); ?>
                </a>  
                
                <a href="notice.php" class="notification-btn">
                    <i class="fa fa-bell-o"></i>
                </a>
                
                <button type="button" class="message-btn" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-envelope-o"></i>
                </button>
                
                <div class="nav-item dropdown ml-2">
                    <a href="#" data-toggle="dropdown" class="user-dropdown">
                        <img src="<?php echo "images/".$userData['profile'];?>" alt="Profile">
                        <?php echo $userData['name']; ?> <i class="fa fa-caret-down ml-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="profile.php" class="dropdown-item"><i class="fa fa-user-o"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="transfer-container">
    <div class="transfer-card">
        <div class="transfer-header">
            <i class="fa fa-university fa-lg"></i>
            <h2>Bank Transfer</h2>
        </div>
        
        <form role="form" method="POST">
            <div class="form-group">
                <label class="form-label">Receiver Account Number</label>
                <div class="input-group">
                    <input type="text" name="otherNo" placeholder="Enter Receiver Account Number" class="form-control" required />
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" name="get">Get Account Info</button>
                    </div>
                </div>
            </div>
        </form>
        
        <?php 
        if (isset($_POST['get'])) {
            $array2 = $con->query("select * from otheraccounts where accountno = '$_POST[otherNo]'");
            $array3 = $con->query("select * from useraccounts where accountno = '$_POST[otherNo]'");
            
            if ($array2->num_rows > 0) { 
                $row2 = $array2->fetch_assoc();
                echo "
                <div class='account-info-card'>
                    <h5>Account Information</h5>
                    <form method='POST'>
                        <div class='form-group'>
                            <label class='form-label'>Account No.</label>
                            <input type='text' value='$row2[accountno]' name='otherNo' class='form-control' readonly required>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>Account Holder Name</label>
                            <input type='text' class='form-control' value='$row2[holdername]' readonly required>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>Bank Name</label>
                            <input type='text' class='form-control' value='$row2[bankname]' readonly required>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>Transfer Amount</label>
                            <input type='number' name='amount' class='form-control' min='3000' max='$userData[deposit]' required>
                        </div>
                        <button type='submit' name='transfer' class='btn btn-primary btn-block'>Transfer Funds</button>
                    </form>
                </div>
                ";
            } elseif ($array3->num_rows > 0) {
                $row2 = $array3->fetch_assoc();
                echo "
                <div class='account-info-card'>
                    <h5>Account Information</h5>
                    <form method='POST'>
                        <div class='form-group'>
                            <label class='form-label'>Account No.</label>
                            <input type='text' value='$row2[accountno]' name='otherNo' class='form-control' readonly required>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>Account Holder Name</label>
                            <input type='text' class='form-control' value='$row2[name]' readonly required>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>Transfer Amount</label>
                            <input type='number' name='amount' class='form-control' min='3000' max='$userData[deposit]' required>
                        </div>
                        <button type='submit' name='transferSelf' class='btn btn-primary btn-block'>Transfer Funds</button>
                    </form>
                </div>
                ";
            } else {
                echo "<div class='alert alert-danger'>Account No. $_POST[otherNo] does not exist</div>";
            }
        }
        ?>
        
        <?php
        // Handle the transfer functionality
        if (isset($_POST['transferSelf'])) {
            $amount = $_POST['amount'];
            
            // Check if functions exist, otherwise define them
            if (!function_exists('setBalance')) {
                function setBalance($amount, $type, $accountno) {
                    global $con;
                    if ($type == 'debit') {
                        $con->query("UPDATE useraccounts SET deposit = deposit - $amount WHERE accountno = '$accountno'");
                    } else {
                        $con->query("UPDATE useraccounts SET deposit = deposit + $amount WHERE accountno = '$accountno'");
                    }
                }
            }
            
            if (!function_exists('makeTransaction')) {
                function makeTransaction($action, $amount, $otherAccount) {
                    global $con, $userData;
                    $con->query("INSERT INTO transaction (userid, action, debit, other, date) 
                                VALUES ('$userData[id]', '$action', '$amount', '$otherAccount', NOW())");
                }
            }
            
            setBalance($amount, 'debit', $userData['accountno']);
            setBalance($amount, 'credit', $_POST['otherNo']);
            makeTransaction('transfer', $amount, $_POST['otherNo']);
            echo "<script>alert('Transfer Successful');window.location.href='funds_transfer.php'</script>";
        }
        
        if (isset($_POST['transfer'])) {
            $amount = $_POST['amount'];
            
            if (!function_exists('setBalance')) {
                function setBalance($amount, $type, $accountno) {
                    global $con;
                    if ($type == 'debit') {
                        $con->query("UPDATE useraccounts SET deposit = deposit - $amount WHERE accountno = '$accountno'");
                    } else {
                        $con->query("UPDATE useraccounts SET deposit = deposit + $amount WHERE accountno = '$accountno'");
                    }
                }
            }
            
            if (!function_exists('makeTransaction')) {
                function makeTransaction($action, $amount, $otherAccount) {
                    global $con, $userData;
                    $con->query("INSERT INTO transaction (userid, action, debit, other, date) 
                                VALUES ('$userData[id]', '$action', '$amount', '$otherAccount', NOW())");
                }
            }
            
            setBalance($amount, 'debit', $userData['accountno']);
            makeTransaction('transfer', $amount, $_POST['otherNo']);
            echo "<script>alert('Transfer Successful');window.location.href='funds_transfer.php'</script>";
        }
        ?>
    </div>
    
    <div class="transfer-history">
        <h3>Transfer History</h3>
        <?php
        $array = $con->query("select * from transaction where userid = '$userData[id]' AND action = 'transfer' order by date desc");
        if ($array->num_rows > 0) {
            while ($row = $array->fetch_assoc()) {
                echo "
                <div class='transaction-item'>
                    <div class='transaction-icon'>
                        <i class='fa fa-exchange'></i>
                    </div>
                    <div class='transaction-details'>
                        <div class='transaction-title'>Transfer to Account No. $row[other]</div>
                        <div class='transaction-date'>$row[date]</div>
                    </div>
                    <div class='transaction-amount'>-$. " . number_format($row['debit'], 2) . "</div>
                </div>
                ";
            }
        } else {
            echo "<div class='alert alert-info'>You have made no transfers yet.</div>";
        }
        ?>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message to Manager</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Recipient:</label>
                        <input type="text" value="Manager" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" name="msg" rows="4" required></textarea>
                    </div>
                    <?php
                    if (isset($_POST['send'])) {
                        if ($con->query("insert into feedback (message,userid) values ('$_POST[msg]','$_SESSION[userid]')")) {
                            echo '<script>alert("Message sent successfully")</script>';
                        } else {
                            echo "<div class='alert alert-danger'>Not sent. Error: ".$con->error."</div>";
                        }
                    }
                    ?>  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="send" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Add animation to form elements
    $('.form-control').focus(function(){
        $(this).parent().addClass('focused');
    }).blur(function(){
        $(this).parent().removeClass('focused');
    });
});
</script>
</body>
</html>