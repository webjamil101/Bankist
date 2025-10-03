<?php
session_start();
if(!isset($_SESSION['userid'])){ 
    header('location:login.php');
    exit();
}

$con = new mysqli('localhost','root','','charusat_bank');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $dob = $con->real_escape_string($_POST['dob']);
    $email = $con->real_escape_string($_POST['email']);
    $phonenumber = $con->real_escape_string($_POST['phonenumber']);
    $occupation = $con->real_escape_string($_POST['occupation']);
    $city = $con->real_escape_string($_POST['city']);
    $userid = $_SESSION['userid'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
        $message_type = "danger";
    }
    // Validate phone number (basic validation)
    elseif (!preg_match('/^[0-9]{10,15}$/', $phonenumber)) {
        $message = "Invalid phone number format";
        $message_type = "danger";
    }
    // Validate date of birth
    elseif (!empty($dob) && strtotime($dob) > strtotime('-18 years')) {
        $message = "You must be at least 18 years old";
        $message_type = "danger";
    }
    else {
        // Update query
        $update_query = "UPDATE useraccounts SET 
                        dob = ?, 
                        email = ?, 
                        phonenumber = ?, 
                        occupation = ?, 
                        city = ? 
                        WHERE id = ?";
        
        $stmt = $con->prepare($update_query);
        if ($stmt) {
            $stmt->bind_param("sssssi", $dob, $email, $phonenumber, $occupation, $city, $userid);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "Profile updated successfully!";
                    $message_type = "success";
                    
                    // Update session data if needed
                    $_SESSION['user_email'] = $email;
                    
                } else {
                    $message = "No changes were made to your profile.";
                    $message_type = "info";
                }
            } else {
                $message = "Error updating profile: " . $stmt->error;
                $message_type = "danger";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $con->error;
            $message_type = "danger";
        }
    }
    
    // Store message in session to display on redirect
    $_SESSION['update_message'] = $message;
    $_SESSION['update_message_type'] = $message_type;
    
    // Redirect back to profile page
    header('Location: profile.php');
    exit();
} else {
    // If not POST request, redirect to profile page
    header('Location: profile.php');
    exit();
}

$con->close();
?>