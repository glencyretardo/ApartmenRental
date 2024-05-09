<?php
session_start();
require_once 'connection.php';

$message = [];

if (isset($_POST['submit'])) {
    // After fetching user inputs
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Debugging output for email and password
    echo "Email: " . $email . "<br>";
    echo "Password: " . $password . "<br>";

    // Debugging output for SQL query
    $sqlQuery = "SELECT * FROM admin_table WHERE email = ?";
    echo "SQL Query: " . $sqlQuery . "<br>";

    // Prepare and execute the query to fetch user data based on email
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    // Rest of your code continues here...
    
    if ($result->num_rows == 1) {
        // User found, fetch user data
        $row = $result->fetch_assoc();

        // Verify password
        if ($password === $row['password']) {
            // Password matches, set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['admin_id'] = $row['admin_id'];

            // Redirect to home page after successful login
            header('location: home.php');
            exit();
        } else {
            // Debugging output for password verification
            echo "Password Verification Failed!<br>";
            echo "Stored Password: " . $row['password'] . "<br>";
            echo "Provided Password: " . $password . "<br>";

            $message[] = 'Incorrect email or password!';
        }
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="loginstyles.css">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>

    <section class="form-container">
        <form action="" method="post">
            <h3>Login now</h3>
            <input type="email" name="email" class="box" placeholder="Enter your email" required
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            <input type="password" name="pass" class="box" placeholder="Enter your password" required>
            <input type="submit" class="btn" name="submit" value="Login now">
            <p>Don't have an account? <a href="register.php">Register now</a></p>
            <p> <a href="home.php">back to home</a></p>
        </form>
    </section>

</body>

</html>
