<?php include 'header.php'; ?>

<?php
// 1. CATCH DATA FROM SERVICES PAGE
// If the user came from services.php, these variables will have data.
$prefill_name    = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
$prefill_email   = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$service_type    = isset($_GET['service']) ? htmlspecialchars($_GET['service']) : '';
$beat_name       = isset($_GET['beat']) ? htmlspecialchars($_GET['beat']) : '';
$project_details = isset($_GET['details']) ? htmlspecialchars($_GET['details']) : '';

// 2. CONSTRUCT PRE-FILLED MESSAGE
$prefill_message = "";
if ($service_type) {
    $prefill_message .= "I'm interested in: $service_type\n";
}
if ($beat_name) {
    $prefill_message .= "Reference Track/Beat: $beat_name\n";
}
if ($project_details) {
    $prefill_message .= "\nProject Details:\n$project_details";
}
?>

<style>
    body { background-color: #000; color: #fff; }

    .contact-hero {
        padding: 120px 0 60px;
        text-align: center;
        background: radial-gradient(circle at center, #1a1a1a 0%, #000 70%);
    }
    
    .contact-hero h1 { font-size: 3rem; font-weight: 800; text-transform: uppercase; }
    .contact-hero span { color: #2bee79; }

    .contact-container {
        max-width: 1000px;
        margin: 0 auto 80px;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 50px;
    }

    /* Left Info Column */
    .contact-info-box {
        background: #0f0f0f;
        padding: 40px;
        border-radius: 12px;
        border: 1px solid #222;
    }
    
    .info-item { margin-bottom: 30px; }
    .info-item h4 { color: #2bee79; font-size: 1.1rem; margin-bottom: 10px; font-weight: 700; text-transform: uppercase; }
    .info-item p, .info-item a { color: #ccc; font-size: 1rem; text-decoration: none; line-height: 1.6; }
    .info-item a:hover { color: #fff; }

    .social-links { display: flex; gap: 15px; margin-top: 20px; }
    .social-links a { 
        width: 40px; height: 40px; 
        background: #222; 
        display: flex; align-items: center; justify-content: center; 
        border-radius: 50%; color: white; transition: 0.3s; 
    }
    .social-links a:hover { background: #2bee79; color: black; transform: translateY(-3px); }

    /* Right Form Column */
    .contact-form {
        background: #0f0f0f;
        padding: 40px;
        border-radius: 12px;
        border: 1px solid #222;
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #888; font-size: 0.9rem; font-weight: 600; }
    
    .form-control {
        width: 100%;
        background: #050505;
        border: 1px solid #333;
        padding: 15px;
        color: white;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        font-family: inherit;
    }
    
    .form-control:focus { border-color: #2bee79; box-shadow: 0 0 0 2px rgba(43, 238, 121, 0.1); }

    .btn-send {
        width: 100%;
        background: #2bee79;
        color: #000;
        font-weight: 800;
        padding: 15px;
        border: none;
        border-radius: 8px;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
        letter-spacing: 1px;
    }
    
    .btn-send:hover { background: white; transform: translateY(-2px); }

    @media (max-width: 768px) {
        .contact-container { grid-template-columns: 1fr; }
    }
</style>

<div class="contact-hero">
    <div class="container">
        <h1>Get In <span>Touch</span></h1>
        <p style="color:#888;">Questions about a beat? Ready to work? Let's talk.</p>
    </div>
</div>

<div class="contact-container">
    
    <div class="contact-info-box">
        <div class="info-item">
            <h4><i class="fa fa-envelope mr-2"></i> Email</h4>
            <a href="mailto:info@kenton.com">info@kenton.com</a>
        </div>
        
        <div class="info-item">
            <h4><i class="fa fa-map-marker mr-2"></i> Studio</h4>
            <p>Los Angeles, CA<br>(Available for remote work worldwide)</p>
        </div>

        <div class="info-item">
            <h4><i class="fa fa-clock-o mr-2"></i> Hours</h4>
            <p>Mon - Fri: 9am - 6pm<br>Sat: 10am - 4pm</p>
        </div>

        <div class="social-links">
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-youtube-play"></i></a>
            <a href="#"><i class="fa fa-soundcloud"></i></a>
        </div>
    </div>

    <form class="contact-form" action="includes/send_email.php" method="POST">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?php echo $prefill_name; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="your@email.com" value="<?php echo $prefill_email; ?>" required>
        </div>

        <div class="form-group">
            <label>Subject</label>
            <select name="subject" class="form-control">
                <option value="General Inquiry">General Inquiry</option>
                <option value="Buying a Beat">Buying a Beat</option>
                <option value="Collab/Service" <?php if($service_type) echo 'selected'; ?>>Service / Collaboration</option>
                <option value="Support">Technical Support</option>
            </select>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="6" placeholder="How can I help you?" required><?php echo $prefill_message; ?></textarea>
        </div>

        <button type="submit" class="btn-send">Send Message <i class="fa fa-paper-plane ml-2"></i></button>
    </form>

</div>

<?php include 'footer.php'; ?>