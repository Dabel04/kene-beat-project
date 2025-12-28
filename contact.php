<?php
include 'header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Producer's email — change this to yours
        $to = "your@email.com"; // ← PUT YOUR EMAIL HERE
        $subject = "New Contact Message from KentonTheProducer.com";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            $success = "Message sent! I'll get back to you soon.";
            // Clear form
            $name = $email = $message = '';
        } else {
            $error = "Sorry, something went wrong. Try again later.";
        }
    }
}
?>

<style>
    body { background-color: #000; color: white; }
    
    .contact-container {
        margin-top: var(--nav-height);
        padding: 80px 20px;
        min-height: 80vh;
    }

    .contact-card {
        max-width: 700px;
        margin: 0 auto;
        background: #0a0a0a;
        border: 1px solid #222;
        border-radius: 16px;
        padding: 50px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }

    .contact-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .contact-header h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }

    .contact-header p {
        color: #888;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        color: #ccc;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        background: #111;
        border: 1px solid #333;
        color: white;
        padding: 15px;
        border-radius: 12px;
        outline: none;
        font-size: 16px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #2bee79;
        box-shadow: 0 0 15px rgba(43, 238, 121, 0.2);
    }

    .form-group textarea {
        min-height: 180px;
        resize: vertical;
    }

    .submit-btn {
        background: #2bee79;
        color: black;
        font-weight: bold;
        padding: 15px 40px;
        border: none;
        border-radius: 50px;
        font-size: 18px;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
    }

    .submit-btn:hover {
        background: white;
        transform: translateY(-2px);
    }

    .alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
    }

    .alert-success {
        background: rgba(43, 238, 121, 0.1);
        border: 1px solid #2bee79;
        color: #2bee79;
    }

    .alert-error {
        background: rgba(255, 71, 87, 0.1);
        border: 1px solid #ff4757;
        color: #ff4757;
    }
</style>

<div class="container contact-container">
    <div class="contact-card">
        <div class="contact-header">
            <h1>Get In Touch</h1>
            <p>Custom beats, collabs, licensing, or just wanna chat? Hit me up.</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" name="name" id="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
            </div>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>