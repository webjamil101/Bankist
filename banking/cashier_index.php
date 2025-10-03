<?php
$con = new mysqli('localhost','root','','charusat_bank');
session_start();
if(!isset($_SESSION['cashid'])){ header('location:cashier_index.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bankist | Cashier Portal</title>
    <link href="images/bank.webp" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda+One">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #f5f7fb;
    }

    .bankist-navbar {
        background: linear-gradient(135deg, var(--primary) 0%, #3a56e4 100%);
        padding: 12px 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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

    .transaction-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 15px;
    }

    .transaction-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 30px;
        border: none;
        position: relative;
    }

    .transaction-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, var(--primary), var(--secondary));
    }

    .transaction-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .transaction-header i {
        font-size: 28px;
        color: var(--primary);
        margin-right: 15px;
    }

    .transaction-header h2 {
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

    .btn-success {
        background: var(--secondary);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #95a5a6;
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
        padding: 25px;
        margin: 20px 0;
        border-left: 4px solid var(--primary);
    }

    .account-info-card h5 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 20px;
    }

    .transaction-form {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
    }

    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin: 15px 0;
    }

    .input-group-append .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    @media (max-width: 768px) {
        .transaction-container {
            margin: 15px auto;
        }
        
        .transaction-card {
            padding: 20px;
        }
    }
    </style>
</head>
<body>
<nav class="navbar bankist-navbar">
    <div class="container">
        <a href="#" class="navbar-brand">
            <img src="images/bank.webp" width="40" alt="Bankist Logo">
            Bankist | Cashier Portal
        </a>
        <div class="navbar-nav ml-auto">
            <a href="logout.php" class="btn btn-outline-light">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </div>
    </div>
</nav>

<?php require 'includes/function.php'; ?>

<?php 
$note = "";
if (isset($_POST['withdrawOther'])) { 
    $accountNo = $_POST['otherNo'];
    $checkNo = $_POST['checkno'];
    $amount = $_POST['amount'];
    
    // Check if function exists
    if (!function_exists('setOtherBalance')) {
        function setOtherBalance($amount, $type, $accountno) {
            global $con;
            if ($type == 'debit') {
                return $con->query("UPDATE otheraccounts SET deposit = deposit - $amount WHERE accountno = '$accountno'");
            } else {
                return $con->query("UPDATE otheraccounts SET deposit = deposit + $amount WHERE accountno = '$accountno'");
            }
        }
    }
    
    if(setOtherBalance($amount, 'debit', $accountNo)) {
        $note = '<div class="alert alert-success">Transaction completed successfully!</div>';
    } else {
        $note = '<div class="alert alert-danger">Transaction failed!</div>';
    }
}

if (isset($_POST['withdraw'])) {
    // Check if functions exist
    if (!function_exists('setBalance')) {
        function setBalance($amount, $type, $accountno) {
            global $con;
            if ($type == 'debit') {
                return $con->query("UPDATE useraccounts SET deposit = deposit - $amount WHERE accountno = '$accountno'");
            } else {
                return $con->query("UPDATE useraccounts SET deposit = deposit + $amount WHERE accountno = '$accountno'");
            }
        }
    }
    
    if (!function_exists('makeTransactionCashier')) {
        function makeTransactionCashier($action, $amount, $checkno, $userid) {
            global $con;
            return $con->query("INSERT INTO transaction (userid, action, debit, checkno, date) 
                              VALUES ('$userid', '$action', '$amount', '$checkno', NOW())");
        }
    }
    
    if(setBalance($_POST['amount'], 'debit', $_POST['accountNo']) && 
       makeTransactionCashier('withdraw', $_POST['amount'], $_POST['checkno'], $_POST['userid'])) {
        $note = '<div class="alert alert-success">Withdrawal completed successfully!</div>';
    } else {
        $note = '<div class="alert alert-danger">Withdrawal failed!</div>';
    }
}

if (isset($_POST['deposit'])) {
    if (!function_exists('setBalance')) {
        function setBalance($amount, $type, $accountno) {
            global $con;
            if ($type == 'debit') {
                return $con->query("UPDATE useraccounts SET deposit = deposit - $amount WHERE accountno = '$accountno'");
            } else {
                return $con->query("UPDATE useraccounts SET deposit = deposit + $amount WHERE accountno = '$accountno'");
            }
        }
    }
    
    if (!function_exists('makeTransactionCashier')) {
        function makeTransactionCashier($action, $amount, $checkno, $userid) {
            global $con;
            return $con->query("INSERT INTO transaction (userid, action, credit, checkno, date) 
                              VALUES ('$userid', '$action', '$amount', '$checkno', NOW())");
        }
    }
    
    if(setBalance($_POST['amount'], 'credit', $_POST['accountNo']) && 
       makeTransactionCashier('deposit', $_POST['amount'], $_POST['checkno'], $_POST['userid'])) {
        $note = '<div class="alert alert-success">Deposit completed successfully!</div>';
    } else {
        $note = '<div class="alert alert-danger">Deposit failed!</div>';
    }
}
?>

<div class="transaction-container">
    <div class="transaction-card">
        <div class="transaction-header">
            <i class="fa fa-university fa-lg"></i>
            <h2>Account Information & Transactions</h2>
        </div>
        
        <?php echo $note; ?>
        
        <form role="form" method="POST">
            <div class="form-group">
                <label class="form-label">Account Number</label>
                <div class="input-group">
                    <input type="text" name="otherNo" placeholder="Enter Account Number" class="form-control" required />
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" name="get">
                            <i class="fa fa-search"></i> Get Account Info
                        </button>
                    </div>
                </div>
            </div>
        </form>
        
        <?php 
        if (isset($_POST['get'])) {
            $array2 = $con->query("SELECT * FROM otheraccounts WHERE accountno = '$_POST[otherNo]'");
            $array3 = $con->query("SELECT * FROM useraccounts WHERE accountno = '$_POST[otherNo]'");
            
            if ($array2->num_rows > 0) { 
                $row2 = $array2->fetch_assoc();
                echo "
                <div class='account-info-card'>
                    <h5>Other Bank Account Information</h5>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label class='form-label'>Account No.</label>
                                <input type='text' value='$row2[accountno]' class='form-control' readonly>
                            </div>
                            <div class='form-group'>
                                <label class='form-label'>Account Holder Name</label>
                                <input type='text' value='$row2[holdername]' class='form-control' readonly>
                            </div>
                            <div class='form-group'>
                                <label class='form-label'>Bank Name</label>
                                <input type='text' value='$row2[bankname]' class='form-control' readonly>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label class='form-label'>Current Balance</label>
                                <input type='text' value='$. " . number_format($row2['deposit'], 2) . "' class='form-control' readonly>
                            </div>
                            <div class='transaction-form'>
                                <form method='POST'>
                                    <input type='hidden' value='$row2[accountno]' name='otherNo'>
                                    <div class='form-group'>
                                        <label class='form-label'>Check Number</label>
                                        <input type='number' name='checkno' class='form-control' placeholder='Enter Check Number' required>
                                    </div>
                                    <div class='form-group'>
                                        <label class='form-label'>Withdrawal Amount</label>
                                        <input type='number' name='amount' class='form-control' placeholder='Enter Amount' min='3000' max='$row2[deposit]' required>
                                    </div>
                                    <button type='submit' name='withdrawOther' class='btn btn-success btn-block'>
                                        <i class='fa fa-money'></i> Process Withdrawal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
            } elseif ($array3->num_rows > 0) {
                $row2 = $array3->fetch_assoc();
                echo "
                <div class='account-info-card'>
                    <h5>Bankist Account Information</h5>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label class='form-label'>Account No.</label>
                                <input type='text' value='$row2[accountno]' class='form-control' readonly>
                            </div>
                            <div class='form-group'>
                                <label class='form-label'>Account Holder Name</label>
                                <input type='text' value='$row2[name]' class='form-control' readonly>
                            </div>
                            <div class='form-group'>
                                <label class='form-label'>Current Balance</label>
                                <input type='text' value='$. " . number_format($row2['deposit'], 2) . "' class='form-control' readonly>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='transaction-form'>
                                <h6>Withdrawal</h6>
                                <form method='POST'>
                                    <input type='hidden' value='$row2[accountno]' name='accountNo'>
                                    <input type='hidden' value='$row2[id]' name='userid'>
                                    <div class='form-group'>
                                        <label class='form-label'>Check Number</label>
                                        <input type='number' name='checkno' class='form-control' placeholder='Enter Check Number' required>
                                    </div>
                                    <div class='form-group'>
                                        <label class='form-label'>Withdrawal Amount</label>
                                        <input type='number' name='amount' class='form-control' placeholder='Enter Amount' min='3000' max='$row2[deposit]' required>
                                    </div>
                                    <button type='submit' name='withdraw' class='btn btn-primary btn-block btn-sm'>
                                        <i class='fa fa-arrow-down'></i> Process Withdrawal
                                    </button>
                                </form>
                            </div>
                            
                            <div class='transaction-form mt-3'>
                                <h6>Deposit</h6>
                                <form method='POST'>
                                    <input type='hidden' value='$row2[accountno]' name='accountNo'>
                                    <input type='hidden' value='$row2[id]' name='userid'>
                                    <div class='form-group'>
                                        <label class='form-label'>Check Number</label>
                                        <input type='number' name='checkno' class='form-control' placeholder='Enter Check Number' required>
                                    </div>
                                    <div class='form-group'>
                                        <label class='form-label'>Deposit Amount</label>
                                        <input type='number' name='amount' class='form-control' placeholder='Enter Amount' required>
                                    </div>
                                    <button type='submit' name='deposit' class='btn btn-success btn-block btn-sm'>
                                        <i class='fa fa-arrow-up'></i> Process Deposit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
            } else {
                echo "<div class='alert alert-warning'>Account No. $_POST[otherNo] does not exist</div>";
            }
        }
        ?>
    </div>
</div>

<script>
$(document).ready(function(){
    // Add focus effect to form inputs
    $('.form-control').focus(function(){
        $(this).parent().addClass('focused');
    }).blur(function(){
        $(this).parent().removeClass('focused');
    });
});
</script>
</body>
</html>