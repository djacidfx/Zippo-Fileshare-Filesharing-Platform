<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="pages/hfo/all.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="pages/hfo/text.css">
</head>
<body>
    <!-- Loader -->
    <div class="loader"></div>

    <!-- Link to Header -->
    <?php include "pages/hfo/main-header.php"?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>A Bright Way To Share <div class="change-colors">Files</div></h1>
            <p>Store, share, and download files. Share files with whole groups or send files to other accounts.</p>
            <div class="btn-container">
                <a href="pages/signup.php" class="btn btn-primary">Sign Up</a>
                <a href="pages/login.php" class="btn btn-primary">Login</a>
            </div>
        </div>
        <img src="pages/assets/hero-image.png" alt="Laptop, tablet, and phone">
    </section>
    <!-- End of Hero Section -->

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="content">
            <h2>About Us</h2>
            <p>Zippo Fileshare is a cutting-edge file sharing platform that allows users to share files with other accounts, or share files with everyone in a group.</p>
        </div>
    </section>
    <!-- End of About Sectiom -->

    <!-- Contact Section -->
    <div class="contact-section" id="contact">
        <div class="container">
            <div class="col-md-12">
                <h2>Contact Us</h2>
                <p>Have any questions or feedback? Feel free to reach out to us using the contact form below.</p>
                <div class="contact-form">
                    <form action="pages/process_contact.php" method="POST">
                        <input type="text" name="name" placeholder="Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <textarea name="message" placeholder="Message" required></textarea>
                        <button type="submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Contact Section -->

    <!-- Link to Footer -->
    <?php include "pages/hfo/footer.php"?>
    
    <!-- Script for all.js that will apear on all pages -->
    <script src="pages/hfo/all.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>