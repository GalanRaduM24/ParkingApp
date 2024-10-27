<?php
    include "../php/inc/header.php";
?>
<?php
    require '../vendor/autoload.php';

    // Load environment variables
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();

    use GuzzleHttp\Client;

    session_start();

    // Check if the user is logged in or signed up
    $isLoggedIn = isset($_SESSION['username']);

    // Supabase credentials from .env
    $supabaseUrl = getenv('DB_URL');
    $supabaseKey = getenv('DB_KEY');

    // Guzzle client setup
    $client = new Client([
        'base_uri' => $supabaseUrl,
        'headers' => [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
        ]
    ]);

    // Process login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['login'])) {
            // Handle login form submission

            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                // Fetch user by username
                $response = $client->request('GET', '/rest/v1/users', [
                    'query' => [
                        'user_name' => 'eq.' . $username,
                        'select' => '*'
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (count($data) === 1) {
                    // User found, verify the password
                    $row = $data[0];
                    $hashed_password = $row['password'];

                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, set the session variables
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $row['mail'];

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
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } elseif (isset($_POST['signup'])) {
            // Handle signup form submission

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                // Check if the username or email already exists
                $response = $client->request('GET', '/rest/v1/users', [
                    'query' => [
                        'or' => sprintf('(user_name.eq.%s,mail.eq.%s)', $username, $email),
                        'select' => '*'
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (count($data) > 0) {
                    // User already exists
                    echo '<script>alert("Username or email already exists!");</script>';
                } else {
                    // User does not exist, proceed with registration

                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert the user into Supabase
                    $response = $client->request('POST', '/rest/v1/users', [
                        'json' => [
                            'user_name' => $username,
                            'mail' => $email,
                            'password' => $hashed_password,
                        ]
                    ]);

                    if ($response->getStatusCode() === 201) {
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
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
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
