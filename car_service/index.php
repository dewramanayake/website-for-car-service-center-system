
<!DOCTYPE html>
<html lang="en">
  <head>
   
    <link rel="stylesheet" href="styles.css" />
    <title>Auto Fix</title>
  </head>
  <body>
    <header class="header">
      <nav>
        <div class="nav__bar">
          <div class="logo nav__logo">
            <a href="#"><img src="assets/logo1.png" alt="logo" /></a>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">HOME</a></li>
          <li><a href="#about">ABOUT</a></li>
          <li><a href="#service">SERVICE</a></li>
         
          <li><a href="#price">PRICE</a></li>
          <button class="btn"><a href="co1.html">GET IN TOUCH</a>
        </button>
        <li><a href="#account"><a href="lo.php">ACCOUNT</a>
        </li>
        </ul>
      </nav>
      <div class="section__container header__container" id="home">
        <div class="header__content">
          <h1>We Are Qualified & Professional</h1>
          <div class="header__btn">
            <button class="btn1"><a href="welc.html">Read More</a></button>
          </div>
        </div>
      </div>
    </header>

    

    <section class="section__container experience__container" id="about">
      <div class="experience__content">
        <p class="section__subheader">WHO WE ARE</p>
        <h2 class="section__header"><br>
          We Have Best Of Experience In This Field
        </h2>
        <br>
        <p class="section__description1"><br>
        With a rich legacy spanning with  best of experience,<br> our commitment to excellence in
          car servicing is unwavering.<br><br> Our seasoned team brings a wealth of
          experience to ensure your vehicle receives top-notch care.<br> <br>Trust in
          our expertise to keep your car running smoothly and safely.
        </p>
       
      </div>
    </section>

    <section class="service" id="service">
      <div class="section__container service__container">
        <p class="section__subheader">WHY CHOOSE US</p>
        <h2 class="section__header2">Great Car Service</h2>
        <p class="section__description">
          Trust us to keep your automobile running smoothly and reliably.
        </p>
        <div class="service__grid">
          <div class="service__card">
            <img src="assets/service-1.jpg" alt="service" />
            <h4>Aligned Wheel</h4>
            <p>
              Experience smoother rides and extended tire life with our wheel
              alignment service.
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-2.jpg" alt="service" />
            <h4>Electrical system</h4>
            <p>
              Elevate car's electrical system to peak performance with our
              specialized expertise.
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-3.jpg" alt="service" />
            <h4>System Service</h4>
            <p>
              We utilize cutting-edge diagnostics and techniques to ensure
              optimal condition.
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-4.jpg" alt="service" />
            <h4>Engine Diagnostics</h4>
            <p>
              Unlock the secrets of your car's performance with state-of-the-art
              diagnostic services.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="customisation">
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_service";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT total_projects, transparency, done_projects, got_awards FROM customisation WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_projects = $row['total_projects'];
    $transparency = $row['transparency'];
    $done_projects = $row['done_projects'];
    $got_awards = $row['got_awards'];
} else {
    $total_projects = $transparency = $done_projects = $got_awards = 0;
}

$conn->close();
?>

<section class="customisation">
    <div class="section__container customisation__container">
        <p class="section__subheader">OUR CUSTOMISATION</p>
        <h2 class="section__header"><br>Car Serving Matched with Great Workmanship</h2>
        <p class="section__description"><br><br>Our dedicated team of skilled technicians and mechanics
         takes pride in delivering top-tier servicing for your beloved vehicle.</p>
        <div class="customisation__grid">
            <div class="customisation__card">
                <h4><?php echo $total_projects; ?></h4>
                <p>Total Projects</p>
            </div>
            <div class="customisation__card">
                <h4><?php echo $transparency; ?></h4>
                <p>Transparency</p>
            </div>
            <div class="customisation__card">
                <h4><?php echo $done_projects; ?></h4>
                <p>Done Projects</p>
            </div>
            <div class="customisation__card">
                <h4><?php echo $got_awards; ?></h4>
                <p>Got Awards</p>
            </div>
        </div>
    </div>
</section>

    </section>


    <section>
      <?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_service";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM packages");
$packages = [];
while ($row = $result->fetch_assoc()) {
    $packages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Pricing Plans</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="section__container price__container" id="price">
        <p class="section__subheader">BEST PACKAGES</p>
        <h2 class="section__header2">Our Pricing Plans</h2>
        <p class="section__description">
            We offer a range of affordable and flexible pricing options.
        </p>
        <div class="price__grid">
            <?php foreach ($packages as $package): ?>
                <div class="price__card">
                    <?php if ($package['name'] == 'Platinum Package'): ?>
                        <div class="price__card__ribbon">BEST SELLER</div>
                    <?php endif; ?>
                    <h4><?= strtoupper($package['name']) ?></h4>
                    <h3><sup>Rs</sup><?= number_format($package['price'], 2) ?></h3>
                    <?php foreach (explode(", ", $package['description']) as $service): ?>
                        <p><?= $service ?></p>
                    <?php endforeach; ?>
                    
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script src="script.js"></script>
    
</body>
</html>

<?php
$conn->close();
?>

    </section>
    
 
    <footer class="footer">
      <div class="section__container subscribe__container">
        <div class="subscribe__content">
          <p class="section__subheader">OUR NEWSLETTER</p><br>
          <h2 class="section__header">Get Update With Our Latest Info</h2>
          <br><br>
          <p class="section__description1">
            Get Update with our Service Info and receive exclusive content, expert
            insights, and special offers.
          </p>
        </div>
      </div>
      <div class="section__container footer__container">
        <div class="footer__col">
          <div class="logo footer__logo">
            <a href="#"><img src="assets/logo1.png" alt="logo" /></a>
          </div>
          <p class="section__description1">
          With a rich legacy spanning with  best of experience, our commitment to excellence
            in car servicing is unwavering.
          </p>
         
        </div>
        <div class="footer__col">
          <h4>Our Services</h4>
          <ul class="footer__links">
            <li><a href="#">Skilled Mechanics</a></li>
            <li><a href="#">Routine Maintenance</a></li>
            <li><a href="#">Customized Solutions</a></li>
            <li><a href="#">Competitive Pricing</a></li>
            <li><a href="#">Satisfaction Guaranteed</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Contact Info</h4>
          <ul class="footer__links">
            <li>
              <p>
                Experience the magic of a rejuvenated ride as we pamper your car
                with precision care
              </p>
            </li>
            <li>
              <p>Phone: <span>+94 772407763</span></p>
            </li>
            <li>
              <p>Email: <span>info@autofix.com</span></p>
            </li>
          </ul>
        </div>
      </div>
    </footer>
    

  
    <script src="main.js"></script>
  </body>
</html>
