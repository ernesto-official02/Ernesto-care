<?php
session_start();
require_once 'config.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['full_name'] : '';
$isAdminLoggedIn = isset($_SESSION['admin_id']);

// Process login form
if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: index.php");
            exit;
        } else {
            $loginError = "Invalid email or password";
        }
    } catch(PDOException $e) {
        $loginError = "Login failed: " . $e->getMessage();
    }
}

// Process registration form
if (isset($_POST['register_submit'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['phone'];
    
    // Validate passwords match
    if ($password !== $confirmPassword) {
        $registerError = "Passwords do not match";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $registerError = "Email already exists";
            } else {
                // Insert new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fullName, $email, $hashedPassword, $phone]);
                
                $registerSuccess = "Registration successful! You can now login.";
            }
        } catch(PDOException $e) {
            $registerError = "Registration failed: " . $e->getMessage();
        }
    }
}

// Process appointment form
if (isset($_POST['appointment_submit'])) {
    $patientName = $_POST['patientName'];
    $patientEmail = $_POST['patientEmail'];
    $patientPhone = $_POST['patientPhone'];
    $appointmentDate = $_POST['appointmentDate'];
    $appointmentTime = $_POST['appointmentTime'];
    $doctor = $_POST['doctor'];
    $reason = $_POST['reason'];
    $userId = $isLoggedIn ? $_SESSION['user_id'] : null;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, patient_name, patient_email, patient_phone, appointment_date, appointment_time, doctor, reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $patientName, $patientEmail, $patientPhone, $appointmentDate, $appointmentTime, $doctor, $reason]);
        
        $appointmentSuccess = "Appointment booked successfully!";
    } catch(PDOException $e) {
        $appointmentError = "Appointment booking failed: " . $e->getMessage();
    }
}

// Process contact form
if (isset($_POST['contact_submit'])) {
    $name = $_POST['contact_name'];
    $email = $_POST['contact_email'];
    $phone = $_POST['contact_phone'];
    $message = $_POST['contact_message'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $message]);
        
        $contactSuccess = "Message sent successfully!";
    } catch(PDOException $e) {
        $contactError = "Failed to send message: " . $e->getMessage();
    }
}

// Check for logout parameter
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logoutSuccess = "You have been successfully logged out.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complete Responsive Hospital Website Design Tutorial</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="style.css">

  <style>
    /* ... existing styles ... */
    
    .admin-settings-option {
      margin-top: 15px;
      text-align: center;
      border-top: 1px solid #eee;
      padding-top: 15px;
    }
    
    .admin-settings-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background-color: #f0f0f0;
      color: #333;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    
    .admin-settings-btn:hover {
      background-color: #e0e0e0;
      color: #000;
    }
    
    .admin-settings-btn i {
      font-size: 16px;
    }
    
    .admin-login-option {
      margin-top: 15px;
      text-align: center;
      border-top: 1px solid #eee;
      padding-top: 15px;
    }
    
    .admin-login-link {
      color: #4a6cf7;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .admin-login-link:hover {
      color: #3a5ce5;
      text-decoration: underline;
    }
    
    /* ... existing styles ... */
  </style>
</head>

<body class="<?php echo $isLoggedIn ? 'logged-in' : ''; ?>" <?php echo $isLoggedIn ? 'data-username="'.htmlspecialchars($userName).'"' : ''; ?>>


  <!-- header navbar section start  -->

  <header>

    <!-- logo name  -->
    <a href="#" class="logo">
      <img src="./assets/img/logo.png" alt="Health Care Logo" class="logo-img" loading="lazy">
    </a>

    <!-- navbar link  -->
    <nav class="navbar">
      <ul>
        <li><a href="#home">home</a></li>
        <li><a href="#about">about</a></li>
        <li><a href="#doctor">doctor</a></li>
        <li><a href="#review">review</a></li>
        <li><a href="#contact">contact</a></li>
        <li><a href="#blog">blog</a></li>
        <?php if ($isLoggedIn): ?>
          <li class="user-menu-container">
            <div class="user-initial"><?php echo htmlspecialchars(substr($userName, 0, 1)); ?></div>
            <div class="user-menu">
              <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
              <div class="logout-btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
              </div>
            </div>
          </li>
        <?php else: ?>
          <li><a href="#" class="login-btn">login</a></li>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="fas fa-bars"></div>
  </header>
  <!-- header navbar section end  -->

  <!-- Login Form Dialog -->
  <dialog id="loginDialog" class="login-dialog">
    <div class="dialog-header">
      <h2>Login</h2>
      <button class="close-btn">&times;</button>
    </div>
    <form id="loginForm" method="POST" action="index.php">
      <?php if (isset($loginError)): ?>
        <div class="error-message"><?php echo htmlspecialchars($loginError); ?></div>
      <?php endif; ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label class="checkbox-container">
          <input type="checkbox" name="remember"> Remember me
        </label>
      </div>
      <div class="form-group">
        <a href="#" class="forgot-password">Forgot Password?</a>
      </div>
      <div class="form-buttons">
        <button type="button" class="cancel-btn">Cancel</button>
        <button type="submit" name="login_submit" class="submit-btn">Login</button>
      </div>
      <div class="register-option">
        <p>Don't have an account? <a href="#" class="register-link">Register now</a></p>
      </div>
      <div class="admin-login-option">
        <p>Are you an administrator? <a href="admin_login.php" class="admin-login-link">Admin Login</a></p>
      </div>
      <?php if ($isAdminLoggedIn): ?>
      <div class="admin-settings-option">
        <a href="admin_settings.php" class="admin-settings-btn"><i class="fas fa-cog"></i> Admin Settings</a>
      </div>
      <?php endif; ?>
    </form>
  </dialog>

  <!-- Register Form Dialog -->
  <dialog id="registerDialog" class="login-dialog">
    <div class="dialog-header">
      <h2>Register</h2>
      <button class="close-btn">&times;</button>
    </div>
    <form id="registerForm" method="POST" action="index.php">
      <?php if (isset($registerError)): ?>
        <div class="error-message"><?php echo htmlspecialchars($registerError); ?></div>
      <?php endif; ?>
      <?php if (isset($registerSuccess)): ?>
        <div class="success-message"><?php echo htmlspecialchars($registerSuccess); ?></div>
      <?php endif; ?>
      <div class="form-group">
        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" required>
      </div>
      <div class="form-group">
        <label for="registerEmail">Email</label>
        <input type="email" id="registerEmail" name="registerEmail" required>
      </div>
      <div class="form-group">
        <label for="registerPassword">Password</label>
        <input type="password" id="registerPassword" name="registerPassword" required>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required>
      </div>
      <div class="form-group">
        <label class="checkbox-container">
          <input type="checkbox" name="terms" required> I agree to the Terms and Conditions
        </label>
      </div>
      <div class="form-buttons">
        <button type="button" class="cancel-btn">Cancel</button>
        <button type="submit" name="register_submit" class="submit-btn">Register</button>
      </div>
      <div class="login-option">
        <p>Already have an account? <a href="#" class="login-link">Login here</a></p>
      </div>
    </form>
  </dialog>

  <!-- home section start  -->

  <section id="home" class="home">

    <div class="row">
      <!-- home heading  -->
      <div class="content">
        <h1><span>Health</span> you can trust,<span>Care </span>you deserve</h1>
        <p>Your well-being is our top priority. Trust our expert team to provide compassionate, high-quality care</p>
        <div class="toggle-content" style="display: none;">
          <p>We offer comprehensive healthcare services including:</p>
          <ul>
            <li>24/7 Emergency Care</li>
            <li>Specialized Medical Departments</li>
            <li>Advanced Diagnostic Facilities</li>
            <li>Patient-Centered Approach</li>
            <li>Experienced Medical Professionals</li>
          </ul>
        </div>
        <div class="button-container">
          <button class="button read-more-btn">read more</button>
          <button class="button appointment-btn" onclick="openAppointmentDialog()">Book Appointment</button>
        </div>
      </div>
    </div>
  </section>
  <!-- home section end  -->

  <!-- Appointment Dialog Box -->
  <dialog id="appointmentDialog" class="appointment-dialog">
    <div class="dialog-header">
      <h2>Book an Appointment</h2>
      <button class="close-btn" onclick="closeAppointmentDialog()">&times;</button>
    </div>
    <form id="appointmentForm" method="POST" action="index.php">
      <?php if (isset($appointmentError)): ?>
        <div class="error-message"><?php echo htmlspecialchars($appointmentError); ?></div>
      <?php endif; ?>
      <?php if (isset($appointmentSuccess)): ?>
        <div class="success-message"><?php echo htmlspecialchars($appointmentSuccess); ?></div>
      <?php endif; ?>
      <div class="form-group">
        <label for="patientName">Full Name</label>
        <input type="text" id="patientName" name="patientName" required>
      </div>
      <div class="form-group">
        <label for="patientEmail">Email</label>
        <input type="email" id="patientEmail" name="patientEmail" required>
      </div>
      <div class="form-group">
        <label for="patientPhone">Phone Number</label>
        <input type="tel" id="patientPhone" name="patientPhone" required>
      </div>
      <div class="form-group">
        <label for="appointmentDate">Preferred Date</label>
        <input type="date" id="appointmentDate" name="appointmentDate" required>
      </div>
      <div class="form-group">
        <label for="appointmentTime">Preferred Time</label>
        <select id="appointmentTime" name="appointmentTime" required>
          <option value="">Select a time</option>
          <option value="09:00">09:00 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="14:00">02:00 PM</option>
          <option value="15:00">03:00 PM</option>
          <option value="16:00">04:00 PM</option>
        </select>
      </div>
      <div class="form-group">
        <label for="doctor">Select Doctor</label>
        <select id="doctor" name="doctor" required>
          <option value="">Select a doctor</option>
          <option value="Dr. Harpal Singh">Dr. Harpal Singh (Cardiologist)</option>
          <option value="Dr. Divya">Dr. Divya (Pediatrician)</option>
          <option value="Dr. sankalp Thakur">Dr. sankalp Thakur (Neurologist)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="reason">Reason for Appointment</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>
      </div>
      <div class="form-buttons">
        <button type="button" class="button cancel-btn" onclick="closeAppointmentDialog()">Cancel</button>
        <button type="submit" name="appointment_submit" class="button submit-btn">Book Appointment</button>
      </div>
    </form>
  </dialog>

  <hr class="section-divider">

  <!-- about section start  -->

  <section id="about" class="about">

    <h1 class="heading">about our facility</h1>
    <h3 class="title">learn and explore our facility</h3>

    <div class="box-container">

      <!-- start here  -->
      <div class="box">
        <!-- about images  -->
        <div class="images">
          <img src="./assets/img/about-1.webp" alt="">
        </div>

        <!-- about heading & text  -->
        <div class="content">
          <h3>ambulance services</h3>
          <p> to provide fast and reliable medical transportation during emergencies. 
            Our fully equipped ambulances are staffed with trained paramedics and advanced life support systems to ensure patients receive critical care while on the way to the hospital.</p>
          <div class="toggle-content" style="display: none;">
            <p>Contact us for ambulance services:</p>
            <p class="contact-info"><i class="fas fa-phone"></i> +91 54368 1698</p>
          </div>
          <button class="button learn-more-btn">learn more</button>
        </div>
      </div>
      <!-- end here  -->


      <!-- start here  -->
      <div class="box">
        <!-- about images  -->
        <div class="images">
          <img src="./assets/img/about-2.webp" alt="">
        </div>

        <!-- about heading & text  -->
        <div class="content">
          <h3>emergency rooms</h3>
          <p>Our Emergency Room (ER) is open 24/7, providing immediate and high-quality care for patients facing critical and life-threatening conditions.
             Staffed with experienced doctors, nurses, and paramedics, our ER is equipped with advanced medical technology to handle a wide range of emergencies, including trauma, heart attacks, strokes, and pediatric care
      </p>
          <div class="toggle-content" style="display: none;">
            <p>Contact us for emergency services:</p>
            <p class="contact-info"><i class="fas fa-phone"></i> +91 54368 1698</p>
          </div>
          <button class="button learn-more-btn">learn more</button>
        </div>
      </div>
      <!-- end here  -->


      <!-- start here  -->
      <div class="box">
        <!-- about images  -->
        <div class="images">
          <img src="./assets/img/about-3.webp" alt="">
        </div>

        <!-- about heading & text  -->
        <div class="content">
          <h3>Telehealth Services</h3>
          <p> Our Telehealth Services make it easy and convenient for you to consult with our experienced doctors from the comfort of your home. Through secure video calls and online consultations, you can receive medical advice, follow-up care, prescription refills, and health guidance without the need to visit the hospital. 
            Whether you need general checkups, specialist consultations, or post-treatment support, our telehealth team is here to assist you with safe, timely, and reliable care</p>
          <a href="#"><button class="button">learn more</button></a>
        </div>
      </div>
      <!-- end here  -->
    </div>
  </section>

  <!-- about section end  -->

  <hr class="section-divider">

  <!-- card section start  -->

  <section id="doctor" class="card">

    <div class="container">

      <h1 class="heading">doctors</h1>
      <h3 class="title">our professional doctors</h3>

      <div class="box-container">

        <!-- start here  -->
        <div class="box">
          <img src="./assets/img/doctors-1.jpg" alt="">

          <div class="content">
            <a href="#">
              <h2>Dr. Harpal Singh</h2>
            </a>
            <p>Cardiologists</p>

            <!-- card icons  -->
            <div class="icons">
              <a href="#" class="fab fa-facebook-f"></a>
              <a href="#" class="fab fa-twitter"></a>
              <a href="#" class="fab fa-instagram"></a>
            </div>
          </div>
        </div>
        <!-- end here  -->

        <!-- start here  -->
        <div class="box">
          <img src="./assets/img/doctors-2.png" alt="">

          <div class="content">
            <a href="#">
              <h2>Dr. Divya </h2>
            </a>
            <p>Pediatricians</p>

            <!-- card icons  -->
            <div class="icons">
              <a href="#" class="fab fa-facebook-f"></a>
              <a href="#" class="fab fa-twitter"></a>
              <a href="#" class="fab fa-instagram"></a>
            </div>
          </div>
        </div>
        <!-- end here  -->

        <!-- start here  -->
        <div class="box">
          <img src="./assets/img/doctor-4.jpeg" alt="">

          <div class="content">
            <a href="#">
              <h2>Dr. sankalp Thakur</h2>
            </a>
            <p>Neurologists </p>

            <!-- card icons  -->
            <div class="icons">
              <a href="#" class="fab fa-facebook-f"></a>
              <a href="#" class="fab fa-twitter"></a>
              <a href="#" class="fab fa-instagram"></a>
            </div>
          </div>
        </div>
        <!-- end here  -->

      </div>
    </div>
  </section>
  <!-- card section end  -->

  <hr class="section-divider">

  <!-- review section start  -->

  <section id="review" class="review">

    <h1 class="heading">our patient review</h1>
    <h3 class="title">what patient says about us</h3>

    <div class="box-container">

      <!-- start here  -->
      <div class="box">
        <i class="fas fa-quote-left"></i>
        <p>The doctors and staff at this hospital are truly amazing. I received quick treatment in the Emergency Room, and they made me feel safe and cared for throughout my stay. Highly recommend their services!"
          — Rohit S.</p>

        <div class="images">
          <img src="./images/img-1.jpg" alt="">

          <div class="info">
            <h3>Rohit S</h3>
            <span>date : 11 2025</span>
          </div>
        </div>
      </div>
      <!-- end here  -->


      <!-- start here  -->
      <div class="box">
        <i class="fas fa-quote-left"></i>
        <p>I used their telehealth service for an online consultation, and it was so smooth and helpful. The doctor listened patiently and guided me properly without me having to visit in person. Great experience!"
          — Meena K</p>

        <div class="images">
          <img src="./images/img-2.jpg" alt="">

          <div class="info">
            <h3>Meena K</h3>
            <span>date : 11 2025</span>
          </div>
        </div>
      </div>
      <!-- end here  -->


      <!-- start here  -->
      <div class="box">
        <i class="fas fa-quote-left"></i>
        <p>Their free health checkup camp was a wonderful experience. The staff was polite, and the checkup was thorough. It's great to see a hospital that truly cares about the community."
          — Anil R.</p>

        <div class="images">
          <img src="./images/img-3.jpg" alt="">

          <div class="info">
            <h3>Anil R.</h3>
            <span>date : 11 2025</span>
          </div>
        </div>
      </div>
      <!-- end here  -->
    </div>
  </section>
  <!-- review section end  -->

  <hr class="section-divider">

  <!-- contact section start  -->

  <section id="contact" class="contact">

    <h1 class="heading">contact us</h1>
    <h3 class="title">you can talk to us any times,</h3>

    <div class="row">

      <!-- form images  -->
      <div class="images">
        <img src="./assets/img/contact.webp" alt="">
      </div>

      <div class="form-container">
        <?php if (isset($contactError)): ?>
          <div class="error-message"><?php echo htmlspecialchars($contactError); ?></div>
        <?php endif; ?>
        <?php if (isset($contactSuccess)): ?>
          <div class="success-message"><?php echo htmlspecialchars($contactSuccess); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php">
          <input type="text" placeholder="full name" name="contact_name" required>
          <input type="email" placeholder="enter your email" name="contact_email" required>
          <input type="number" placeholder="phone" name="contact_phone" required>
          <textarea name="contact_message" cols="30" rows="10" required></textarea>
          <button type="submit" name="contact_submit" value="send">send</button>
        </form>
      </div>
    </div>




  </section>



  <!-- contact section end  -->

  <hr class="section-divider">

  <!-- blog section start  -->

  <section id="blog" class="blog">

    <h1 class="heading">blog</h1>
    <h3 class="title">Latest Health Insights & Updates</h3>

    <div class="blog-container">
      <!-- Blog Post 1 -->
      <article class="blog-card">
        <div class="blog-image">
          <img src="./assets/img/blog 1.png" alt="Diabetes Management">
          <div class="blog-category">Health</div>
        </div>
        <div class="blog-content">
          <div class="blog-meta">
            <span><i class="far fa-calendar-alt"></i> May 15, 2023</span>
            <span><i class="far fa-user"></i> Dr. Harpal Singh</span>
          </div>
          <h2 class="blog-title">Understanding Diabetes Management</h2>
          <p class="blog-excerpt">Learn about the latest approaches to managing diabetes effectively, including diet, exercise, and medication options.</p>
          <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>

      <!-- Blog Post 2 -->
      <article class="blog-card">
        <div class="blog-image">
          <img src="./assets/img/blog 2.png" alt="COVID-19 Vaccine">
          <div class="blog-category">Vaccination</div>
        </div>
        <div class="blog-content">
          <div class="blog-meta">
            <span><i class="far fa-calendar-alt"></i> June 3, 2023</span>
            <span><i class="far fa-user"></i> Dr. Divya</span>
          </div>
          <h2 class="blog-title">COVID-19 Vaccine: What You Need to Know</h2>
          <p class="blog-excerpt">Stay informed about the latest developments in COVID-19 vaccines, their effectiveness, and who should receive them.</p>
          <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>

      <!-- Blog Post 3 -->
      <article class="blog-card">
        <div class="blog-image">
          <img src="./assets/img/blog 3.png" alt="Epidemic Prevention">
          <div class="blog-category">Prevention</div>
        </div>
        <div class="blog-content">
          <div class="blog-meta">
            <span><i class="far fa-calendar-alt"></i> July 12, 2023</span>
            <span><i class="far fa-user"></i> Dr. Sankalp Thakur</span>
          </div>
          <h2 class="blog-title">Preventing Epidemics: Community Health Strategies</h2>
          <p class="blog-excerpt">Discover effective strategies for preventing the spread of infectious diseases in your community.</p>
          <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>
    </div>
  </section>



  <!-- blog section end  -->

  <hr class="section-divider">

  <!-- footer section start  -->

  <section class="footer">

    <div class="box">
      <a href="#" class="logo">
        <img src="./assets/img/logo.png" alt="Health Care Logo" class="logo-img" loading="lazy">
      </a>

      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam laboriosam quisquam facere pariatur ratione porro
        et vero dolore accusamus id?</p>
    </div>

    <div class="box">
      <h2 class="logo"><span>S</span>hare</h2>

      <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
      <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
      <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
      <a href="#"><i class="fab fa-pinterest-p"></i> Pinterest</a>
    </div>



    <h1 class="credit">created by <span>Ernesto Care</span> all right reserved.</h1>
  </section>

  <!-- footer section end  -->


  <!-- jquery cdn link  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- custom js file link  -->
  <script src="main.js"></script>
</body>

</html>