<?php
    include "../php/inc/header.php";
?>

<div class="container">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1>Map of parking lots</h1>

            <iframe src="https://www.google.com/maps/d/u/0/embed?mid=13li0ENeuQ0ndby__SseVLDOdio-G_M0&ehbc=2E312F" width="940" height="680"></iframe>
    <?php

    require '../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();


    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT name, locuri FROM parkings";
    $result = $conn->query($sql);
    ?>
    
    <?php while ($row = $result->fetch_assoc()): ?>
        <p><?php echo $row['name'] . " (" . $row['locuri'] . " spaces)"; ?></p>
    <?php endwhile; ?>
    
</div>
</div>
</div>
<div><br></div>
<footer>
<?php
    include "../php/inc/footer.php";
    $conn->close();
?>
