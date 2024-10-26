<?php
include "../php/inc/header.php";
?>
<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

session_start();

// Check if the user is logged in or signed up
$isLoggedIn = isset($_SESSION['username']);

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Handle login form submission

        // Assuming you have a database connection established
        $host = getenv('DB_HOST');
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        $conn = new mysqli($host, $user, $password, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?"); // Updated column name
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // User found, verify the password
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                // Password is correct, set the session variables
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $row['mail']; // Updated column name

                // Redirect the user to their profile page or any other desired page
                header("Location: profile.php");
                exit();
            } else {
                // Password is incorrect
                echo '<script>alert("Invalid username or password!");</script>';
            }
        } else {
            // User not found
            echo '<script>alert("Invalid username or password!");</script>';
        }

        $stmt->close();
        $conn->close();
    } elseif (isset($_POST['signup'])) {
        // Handle signup form submission

        // Assuming you have a database connection established
        $host = 'localhost';
        $db = 'parking'; // Updated database name
        $user = 'root';
        $password = 'root';

        $conn = new mysqli($host, $user, $password, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the query to check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? OR mail = ?"); // Updated column names
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User already exists
            echo '<script>alert("Username or email already exists!");</script>';
        } else {
            // User does not exist, proceed with registration

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (user_name, mail, password) VALUES (?, ?, ?)"); // Updated column names
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful, set the session variables
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                // Redirect the user to their profile page or any other desired page
                header("Location: profile.php");
                exit();
            } else {
                // Registration failed
                echo '<script>alert("Error during registration. Please try again.");</script>';
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<?php
// Check if the user is logged in or signed up
$isLoggedIn = isset($_SESSION['username']);

// Process sign-out request
if (isset($_GET['signout'])) {
    // Clear the session variables
    session_unset();
    session_destroy();

    // Redirect the user to the login page or any other desired page
    header("Location: profile.php");
    exit();
}
?>

<div class="container">
    <div class="col-md-12">

        <?php if ($isLoggedIn): ?>
            <!-- Display user account information -->
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <p>Username: <?php echo $_SESSION['username']; ?></p>
            <p>Email: <?php echo $_SESSION['email']; ?></p>
            
            <a href="profile.php?signout=true">Sign Out</a>
            
        <?php else: ?>
            <!-- Display login and sign-up forms -->
            <!-- Your existing login form code -->
            <form action="" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit" name="login">Log In</button>
            </form>
            <br>
            <!-- Your existing sign-up form code -->
            <form action="" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div><br></div>
<footer class="fixed-footer">
<?php
include "../php/inc/footer.php";
?>
