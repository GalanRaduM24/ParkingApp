<?php
    include "../api/inc/header.php";
?>
<div class="container"> 
<div class="col-md-12">
<h1>Contact Page</h1>
<br><br>
<form method="POST">
  <div>
    <p for="phone">Phone Number: 077 777 7777</p>
  </div>
  <div>
    <p for="email">Email: parkingBucCustomer@gmail.com</p>
  </div>
  <div>
    <label for="message">Send Ticket:</label>
    <textarea id="message" name="message"></textarea>
  </div>
  <button type="submit">Submit</button>
</form>
<br>
</div> 
</div> 

<div><br></div>
<footer class="fixed-footer">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];

    // Display the message in the console
    echo '<script>';
    echo 'console.log("Message: ' . $message . '");';
    echo 'alert("Ticket was sent");'; // Display the alert
    echo '</script>';
}

include "../api/inc/footer.php";

?>
