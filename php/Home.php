<?php
    include "../php/inc/header.php";
?>

  <main>
    <section id="parking-lots" class="container">
      <h2>Home</h2>
      <div class="row">
        <div class="col-md-12">
          <img class="parking-lot-image" src="../images/parcare_ateneu.jpg" alt="Parking Lot 1" id="image">
        </div>
      </div>
    </section>

<section id="app-description" class="container">
  <div class="row">
    <div class="col-lg-6">
      <div class="description-box">
        <p class="text-center">Welcome to ParkingBucharest, your go-to web app for hassle-free parking spot management and bookings. Whether you're a driver in search of available parking spaces or a parking lot owner looking to efficiently manage your spots, our user-friendly interface and robust features are tailored to meet your needs.</p>
      </div>
      <div class="description-box">
        <p class="text-center">With ParkingBucharest, our goal is to simplify the parking experience, empowering drivers to easily find and book parking spots, while enabling parking lot owners to efficiently manage their spaces. Join us today and enjoy a seamless parking experience!</p>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="description-box key-features-box">
        <p class="text-center">Key Features:</p>
        <ul class="text-center">
          <li>Search and Browse Parking Spots: Easily find parking spots in your desired location, explore detailed information, and check availability.</li>
          <li>Booking System: Reserve parking spots in advance to ensure a seamless experience upon arrival.</li>
          <li>User Profiles: Create an account to save preferences, access booking history, and manage account settings.</li>
          <li>Secure Payments: Enjoy peace of mind with hassle-free, secure payment options for your parking bookings.</li>
          <li>Customer Support: Our dedicated support team is here to assist you with any inquiries or assistance you may need.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

  <section id="reviews" class="container">
    <h2>Customer Reviews</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <div class="profile-picture">
                <img src="../images/profile1.jpg" alt="Profile Picture">
              </div>
              John Doe
            </h5>
            <p class="card-text">"Great parking service! Very convenient and user-friendly."</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <div class="profile-picture">
                <img src="../images/profile2.jpg" alt="Profile Picture">
              </div>
              Jane Smith
            </h5>
            <p class="card-text">"I love using this app to find parking spots. It saves me a lot of time and hassle."</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <div class="profile-picture">
                <img src="../images/profile3.jpg" alt="Profile Picture">
              </div>
              David Johnson
            </h5>
            <p class="card-text">"The customer support team was very helpful and resolved my issue quickly. Highly recommended!"</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  </main>

  <script>
    let displayImages = document.querySelectorAll('.parking-lot-image');
    let counter = 5;
    let randomNumber;

    let randomImage = [
      "../images/parcare_ateneu.jpg",
      "../images/parcare_casa-poporului.jpg"
    ];

    function random() {
      randomNumber = parseInt(Math.random() * 2);
      displayImages.forEach(function (image) {
        image.classList.add('fade-out');
      });
      setTimeout(function () {
        displayImages.forEach(function (image) {
          image.src = randomImage[randomNumber];
        });
        displayImages.forEach(function (image) {
          image.classList.remove('fade-out');
        });
      }, 500);
    }

    function auto() {
      counter--;
      random();

      if (counter === 0) {
        counter = 5;
      }
    }

    function stopAuto() {
      clearInterval(autoImage);
    }

    function startAuto() {
      autoImage = setInterval(auto, 2500);
    }

    let autoImage = setInterval(auto, 2500);
  </script>

<div><br></div>
<footer>
<?php
    include "../php/inc/footer.php";
?>


