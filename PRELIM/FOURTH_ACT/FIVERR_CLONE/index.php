<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #F8F9FA;
    }

    .navbar {
      background-color: #0077B6;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .hero {
      background: linear-gradient(to right, #0077B6, #00B4D8);
      color: white;
      padding: 60px 20px;
      text-align: center;
      border-radius: 0 0 20px 20px;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 600;
    }

    .hero p {
      font-size: 1.2rem;
      margin-top: 10px;
    }

    .card-body h1 {
      font-size: 1.8rem;
      font-weight: 600;
    }

    .card-body a.btn {
      margin-top: 15px;
    }

    .card:hover {
      transform: scale(1.02);
      transition: 0.3s ease;
    }

    .btn-primary, .btn-success {
      padding: 10px 20px;
      font-weight: 600;
      border-radius: 30px;
    }

    .btn-primary:hover {
      background-color: #023E8A;
    }

    .btn-success:hover {
      background-color: #0096C7;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: 600;
      text-align: center;
      margin: 60px 0 30px;
      color: #0077B6;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark p-4">
    <a class="navbar-brand" href="#">Fiverr Clone</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="hero">
    <h1>Welcome to Fiverr Clone</h1>
    <p>Connect with top talent or find your next gig—fast, easy, and personalized.</p>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <h1>Hire Top Talent</h1>
            <img src="https://images.unsplash.com/photo-1549923746-c502d488b3ea?q=80&w=1171&auto=format&fit=crop" class="img-fluid rounded mb-3">
            <p>Find skilled freelancers ready to bring your ideas to life.</p>
            <a href="client/login.php" class="btn btn-primary">Join as Client</a>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <h1>Find Freelance Work</h1>
            <img src="https://plus.unsplash.com/premium_photo-1661582394864-ebf82b779eb0?q=80&w=1170&auto=format&fit=crop" class="img-fluid rounded mb-3">
            <p>Showcase your skills and get matched with great opportunities.</p>
            <a href="freelancer/login.php" class="btn btn-success">Join as Freelancer</a>
          </div>
        </div>
      </div>
    </div>

    <div class="section-title">Testimonials From Users</div>
    <div class="row justify-content-center">
      <?php
        $testimonials = [
          ["Sophia M.", "This talent search app helped me discover amazing job opportunities quickly.", "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91"],
          ["Liam K.", "Easy to use and very effective. Found a great match for my skills within a week.", "https://images.unsplash.com/photo-1524504388940-b1c1722653e1"],
          ["Emma T.", "The app’s user interface is smooth and the application process was seamless.", "https://images.unsplash.com/photo-1520813792240-56fc4a3765a7"],
          ["Olivia W.", "I love how the app customizes recommendations based on my profile.", "https://images.unsplash.com/photo-1544005313-94ddf0286df2"],
          ["Ethan L.", "The interview scheduling feature saved me so much time.", "https://images.unsplash.com/photo-1511367461989-f85a21fda167"],
          ["Adam R.", "Found roles that matched my skill set perfectly.", "https://images.unsplash.com/photo-1527980965255-d3b416303d12"],
          ["Ava J.", "Quick, efficient, and easy-to-use. This app significantly improved my job search.", "https://images.unsplash.com/photo-1531123897727-8f129e1688ce"]
        ];

        foreach ($testimonials as $t) {
          echo '
          <div class="col-md-4 mt-4">
            <div class="card h-100 shadow-sm">
              <img src="'.$t[2].'?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="'.$t[0].'" />
              <div class="card-body">
                <h5 class="card-title">'.$t[0].'</h5>
                <p class="card-text">'.$t[1].'</p>
              </div>
            </div>
          </div>';
        }
      ?>
    </div>
  </div>

</body>
</html>
