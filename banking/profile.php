<?php
session_start();
if(!isset($_SESSION['userid'])){ header('location:login.php');}

$con = new mysqli('localhost','root','','charusat_bank');
$ar = $con->query("select * from useraccounts where id = '$_SESSION[userid]'");
$userData = $ar->fetch_assoc();

// Handle profile picture upload
if(isset($_POST['upload_profile'])) {
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }
    
    // Check file size (5MB maximum)
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        // Generate unique filename
        $new_filename = "profile_" . $_SESSION['userid'] . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database with new profile picture
            if($con->query("UPDATE useraccounts SET profile = '$new_filename' WHERE id = '$_SESSION[userid]'")) {
                echo "<script>alert('Profile picture updated successfully!'); window.location.href='profile.php';</script>";
                $userData['profile'] = $new_filename;
            } else {
                echo "<script>alert('Error updating profile picture in database.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}

// Handle profile picture removal
if(isset($_POST['remove_profile'])) {
    $default_image = "default-avatar.jpg";
    if($con->query("UPDATE useraccounts SET profile = '$default_image' WHERE id = '$_SESSION[userid]'")) {
        echo "<script>alert('Profile picture removed successfully!'); window.location.href='profile.php';</script>";
        $userData['profile'] = $default_image;
    } else {
        echo "<script>alert('Error removing profile picture.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Bankist | Profile</title>
<link href="images/bank.webp" rel="icon">
<link href="images/bank.webp" rel="apple-touch-icon"> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda+One">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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

body {
    background: #f5f7fb;
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
    min-width: 200px;
}

.bankist-navbar .nav-item.dropdown .dropdown-item {
    padding: 10px 20px;
    color: var(--dark);
    display: flex;
    align-items: center;
    transition: all 0.3s;
}

.bankist-navbar .nav-item.dropdown .dropdown-item i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.bankist-navbar .nav-item.dropdown .dropdown-item:hover {
    background: var(--primary-light);
    color: var(--primary);
    transform: translateX(5px);
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
    transition: all 0.3s;
}

.balance-badge:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
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
    padding: 8px 15px;
    border-radius: 30px;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.user-dropdown:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    text-decoration: none;
    border-color: rgba(255,255,255,0.3);
}

.user-dropdown img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    margin-right: 10px;
    border: 2px solid rgba(255,255,255,0.4);
    object-fit: cover;
}

.profile-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 15px;
}

.profile-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: none;
    overflow: hidden;
    position: relative;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, var(--primary), var(--secondary));
}

.profile-tab-nav {
    min-width: 280px;
    background: var(--primary-light);
    border-right: 1px solid #e1e5eb;
}

.profile-tab-nav .p-4 {
    border-bottom: 1px solid #e1e5eb;
}

.img-circle {
    text-align: center;
    margin-bottom: 20px;
    position: relative;
}

.img-circle img {
    height: 140px;
    width: 140px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    object-fit: cover;
    transition: all 0.3s;
}

.img-circle:hover img {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.profile-upload-form {
    display: none;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-top: 15px;
    border: 1px solid #e1e5eb;
}

.nav-pills a.nav-link {
    padding: 15px 25px;
    border-bottom: 1px solid #e1e5eb;
    border-radius: 0;
    color: var(--dark);
    transition: all 0.3s;
    font-weight: 500;
}

.nav-pills a.nav-link i {
    width: 20px;
    margin-right: 12px;
    font-size: 18px;
}

.nav-pills a.nav-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateX(5px);
}

.nav-pills a.nav-link:hover:not(.active) {
    background: rgba(93, 120, 255, 0.1);
    color: var(--primary);
    transform: translateX(5px);
}

.tab-content {
    flex: 1;
    padding: 35px;
}

.form-group {
    margin-bottom: 1.8rem;
}

.form-label {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 10px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    border: 1px solid #e1e5eb;
    border-radius: 10px;
    padding: 14px 18px;
    transition: all 0.3s;
    font-size: 15px;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.3rem rgba(93, 120, 255, 0.15);
    transform: translateY(-2px);
}

.form-control:read-only {
    background-color: #f8f9fa;
    opacity: 0.8;
    cursor: not-allowed;
}

.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 10px;
    padding: 14px 28px;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 15px;
}

.btn-primary:hover {
    background: #4a63e0;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(93, 120, 255, 0.3);
}

.btn-success {
    background: var(--secondary);
    border: none;
    border-radius: 10px;
    padding: 14px 28px;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 15px;
}

.btn-success:hover {
    background: #27ae60;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
}

.btn-outline-secondary {
    border: 2px solid #95a5a6;
    border-radius: 10px;
    padding: 14px 28px;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 15px;
}

.btn-outline-secondary:hover {
    background: #95a5a6;
    color: white;
    transform: translateY(-3px);
}

.btn-sm {
    padding: 10px 20px;
    font-size: 14px;
    margin: 5px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 25px;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item.active {
    color: var(--dark);
    font-weight: 600;
}

.custom-file-input {
    cursor: pointer;
}

.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .profile-container {
        margin: 15px auto;
    }
    
    .profile-tab-nav {
        min-width: 100%;
        border-right: none;
        border-bottom: 1px solid #e1e5eb;
    }
    
    .tab-content {
        padding: 25px;
    }
    
    .bankist-navbar .navbar-nav {
        margin-top: 15px;
    }
    
    .bankist-navbar .nav-link {
        margin: 2px 0;
    }
    
    .img-circle img {
        height: 120px;
        width: 120px;
    }
}

/* Animation for dropdown */
.dropdown-menu {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Profile image preview */
#imagePreview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    margin: 15px auto;
    display: none;
    border: 3px solid var(--primary);
}
</style>
</head>

<body>
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
                <a href="funds_transfer.php" class="nav-item nav-link">Transfers</a>
                <a href="profile.php" class="nav-item nav-link active">Profile</a>
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
                        <img src="images/<?php echo $userData['profile']; ?>" alt="Profile" onerror="this.src='images/default-avatar.jpg'">
                        <?php echo $userData['name']; ?> <i class="fa fa-caret-down ml-2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="profile.php" class="dropdown-item">
                            <i class="fa fa-user-circle mr-2"></i> My Profile
                        </a>
                        <a href="account.php" class="dropdown-item">
                            <i class="fa fa-credit-card mr-2"></i> My Accounts
                        </a>
                        <a href="statement.php" class="dropdown-item">
                            <i class="fa fa-file-text mr-2"></i> Statements
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="settings.php" class="dropdown-item">
                            <i class="fa fa-cog mr-2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item text-danger">
                            <i class="fa fa-sign-out mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

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

<div class="profile-container">
    <h1 class="mb-3">Account Settings</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
    
    <div class="profile-card d-block d-sm-flex">
        <div class="profile-tab-nav">
            <div class="p-4">
                <div class="img-circle">
                    <img src="images/<?php echo $userData['profile']; ?>" alt="Profile Picture" id="currentProfileImage" onerror="this.src='images/default-avatar.jpg'">
                    <div class="pt-3">
                        <button type="button" class="btn btn-primary btn-sm" id="uploadProfileBtn">
                            <i class="bi bi-upload"></i> Upload
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="removeProfileBtn">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                </div>
                
                <!-- Profile Upload Form -->
                <div class="profile-upload-form" id="profileUploadForm">
                    <h6 class="text-center mb-3">Upload New Profile Picture</h6>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture" accept="image/*" required>
                                <label class="custom-file-label" for="profile_picture">Choose file</label>
                            </div>
                        </div>
                        <div id="imagePreview" class="text-center"></div>
                        <div class="form-group text-center mt-3">
                            <button type="submit" name="upload_profile" class="btn btn-success btn-sm mr-2">
                                <i class="bi bi-check-circle"></i> Upload
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" id="cancelUpload">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Remove Profile Form -->
                <form method="POST" id="removeProfileForm" class="d-none">
                    <input type="hidden" name="remove_profile" value="1">
                </form>
                
                <h4 class="text-center mt-3"><?php echo $userData['name']; ?></h4>
                <p class="text-center text-muted mb-0">Account No: <?php echo $userData['accountno']; ?></p>
                <p class="text-center text-muted small">Member since: <?php echo date('M Y', strtotime($userData['time'])); ?></p>
            </div>
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
                    <i class="fa fa-user-circle mr-2"></i> 
                    Account Details
                </a>
                <a class="nav-link" id="security-tab" data-toggle="pill" href="#security" role="tab" aria-controls="security" aria-selected="false">
                    <i class="fa fa-shield mr-2"></i> 
                    Security
                </a>
            </div>
        </div>
        
        <div class="tab-content" id="v-pills-tabContent">
            <!-- Account Details Tab -->
            <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                <h3 class="mb-4">Account Information</h3>
                <form method="POST" action="updateprofile.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="<?php echo $userData['name']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" value="<?php echo $userData['accountno']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" value="<?php echo $userData['dob']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $userData['email']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phonenumber" value="<?php echo $userData['phonenumber']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation" value="<?php echo $userData['occupation']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" value="<?php echo $userData['city']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Account Type</label>
                                <input type="text" class="form-control" value="<?php echo $userData['accounttype']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Current Balance</label>
                                <input type="text" class="form-control" value="$. <?php echo number_format($userData['deposit'], 2); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Account Created</label>
                                <input type="text" class="form-control" value="<?php echo $userData['time']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save mr-2"></i> Update Profile
                            </button>
                            <button type="reset" class="btn btn-outline-secondary px-4 ml-2">
                                <i class="fa fa-undo mr-2"></i> Reset Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                <h3 class="mb-4">Security Settings</h3>
                <form method="POST" action="updatepassword.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-key mr-2"></i> Change Password
                            </button>
                        </div>
                    </div>
                </form>
                
                <hr class="my-5">
                
                <h5 class="mb-3">Two-Factor Authentication</h5>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    Enhance your account security by enabling two-factor authentication.
                </div>
                <button type="button" class="btn btn-outline-primary">
                    <i class="fa fa-mobile mr-2"></i> Enable 2FA
                </button>
            </div>
        </div>
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
    
    // Profile image upload functionality
    $('#uploadProfileBtn').click(function(){
        $('#profileUploadForm').slideToggle(300);
    });
    
    $('#cancelUpload').click(function(){
        $('#profileUploadForm').slideUp(300);
        $('#profile_picture').val('');
        $('.custom-file-label').text('Choose file');
        $('#imagePreview').hide();
    });
    
    // Profile image remove functionality
    $('#removeProfileBtn').click(function(){
        if(confirm('Are you sure you want to remove your profile picture? It will be replaced with the default avatar.')) {
            $('#removeProfileForm').submit();
        }
    });
    
    // File input change handler
    $('#profile_picture').change(function(){
        var fileName = $(this).val().split('\\').pop();
        $('.custom-file-label').text(fileName);
        
        // Show image preview
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Preview">').show();
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Bootstrap file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    // Dropdown animation
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });
    
    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(300);
    });
});
</script>
</body>
</html>