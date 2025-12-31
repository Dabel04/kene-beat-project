<?php include 'header.php'; ?>

<style>
    /* --- PAGE SPECIFIC STYLES --- */
    body {
        background-color: #000;
        color: #fff;
    }

    /* Hero Section */
    .services-hero {
        padding: 140px 0 80px;
        text-align: center;
        background: radial-gradient(circle at center, #1a1a1a 0%, #000 70%);
        border-bottom: 1px solid #1a1a1a;
    }

    .services-hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: -2px;
        margin-bottom: 15px;
    }

    .services-hero h1 span {
        color: #2bee79;
    }

    .services-hero p {
        color: #888;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Services Grid */
    .services-grid {
        max-width: 1200px;
        margin: 80px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .service-card {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-10px);
        border-color: #2bee79;
        box-shadow: 0 10px 30px rgba(43, 238, 121, 0.1);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        background: rgba(43, 238, 121, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: #2bee79;
        font-size: 30px;
    }

    .service-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: white;
    }

    .service-card p {
        color: #888;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .service-price {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 5px;
    }

    .service-price span {
        font-size: 0.9rem;
        color: #555;
        font-weight: 400;
    }

    /* "Make a Song" Workflow Section */
    .workflow-section {
        background: #0a0a0a;
        padding: 100px 0;
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
    }

    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .steps-container {
        display: flex;
        justify-content: center;
        gap: 50px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        flex-wrap: wrap;
    }

    .step-item {
        flex: 1;
        min-width: 250px;
        text-align: center;
        position: relative;
    }

    /* Connecting Line (Desktop Only) */
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 25px;
        right: -35%;
        width: 70%;
        height: 2px;
        background: #222;
        z-index: 0;
    }

    .step-number {
        width: 50px;
        height: 50px;
        background: #1a1a1a;
        border: 2px solid #2bee79;
        color: #2bee79;
        font-weight: 800;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        z-index: 1;
    }

    .step-item h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .step-item p {
        color: #666;
        font-size: 0.9rem;
    }

    /* Booking Form */
    .booking-section {
        max-width: 800px;
        margin: 100px auto;
        padding: 0 20px;
    }

    .booking-form {
        background: #0f0f0f;
        padding: 40px;
        border-radius: 15px;
        border: 1px solid #222;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #aaa;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        background: #050505;
        border: 1px solid #333;
        padding: 15px;
        color: white;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #2bee79;
    }

    .btn-submit {
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
    }

    .btn-submit:hover {
        background: white;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .services-grid { grid-template-columns: 1fr; }
        .step-item:not(:last-child)::after { display: none; }
        .steps-container { flex-direction: column; gap: 30px; }
    }
</style>

<div class="services-hero">
    <div class="container">
        <h1>Work With <span>Kenton</span></h1>
        <p>Premium production services for serious artists. From custom beats to full song production.</p>
    </div>
</div>

<div class="services-grid">
    
    <div class="service-card">
        <div class="icon-box"><i class="fa fa-sliders"></i></div>
        <h3>Mixing & Mastering</h3>
        <p>I will mix your vocals with any of my beats to professional radio standards. Crisp vocals, hard hitting drums.</p>
        <div class="service-price">$150 <span>/ Song</span></div>
    </div>

    <div class="service-card" style="border-color: #2bee79; background: #141414;">
        <div class="icon-box" style="background: #2bee79; color: #000;"><i class="fa fa-microphone"></i></div>
        <h3>Full Song Production</h3>
        <p>Choose a track from my catalog and let's work together to turn it into a complete song. Arrangement, effects, and feedback.</p>
        <div class="service-price">$300 <span>/ Project</span></div>
    </div>

    <div class="service-card">
        <div class="icon-box"><i class="fa fa-music"></i></div>
        <h3>Custom Beat</h3>
        <p>I will create a beat from scratch tailored exactly to your style, voice, and vision. Exclusive rights included.</p>
        <div class="service-price">$500+ <span>/ Exclusive</span></div>
    </div>

</div>

<div class="workflow-section">
    <div class="section-title">
        <h2>How We <span style="color:#2bee79;">Collaborate</span></h2>
        <p style="color:#666;">The process to turn a beat into your next hit.</p>
    </div>

    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <h4>Choose A Beat</h4>
            <p>Browse my <a href="tracks.php" style="color:#2bee79;">Catalog</a> and find the vibe that speaks to you. Purchase the lease.</p>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <h4>Record Your Demo</h4>
            <p>Record your vocals over the beat. It doesn't have to be perfect, just capture the idea.</p>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <h4>Let's Work</h4>
            <p>Send me the files below. I'll handle the mixing, arrangement, and final polish.</p>
        </div>
    </div>
</div>

<div class="booking-section">
    <div class="section-title">
        <h2>Start A <span style="color:#2bee79;">Project</span></h2>
    </div>
    
    <?php if(isset($_GET['success'])): ?>
        <div style="background: rgba(43,238,121,0.1); border: 1px solid #2bee79; color: #2bee79; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            <i class="fa fa-check-circle"></i> Request sent! I'll check my admin panel and email you shortly.
        </div>
    <?php endif; ?>

    <form class="booking-form" action="includes/submit_service.php" method="POST">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label>Your Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Service Type</label>
            <select name="service_type" class="form-control">
                <option value="Full Song Production">Full Song Production (Collaboration)</option>
                <option value="Mixing & Mastering">Mixing & Mastering</option>
                <option value="Custom Beat">Custom Beat</option>
            </select>
        </div>
        <div class="form-group">
            <label>Which Beat? (Optional)</label>
            <input type="text" name="beat_name" class="form-control" placeholder="e.g. 'Night Rider' or paste link">
        </div>
        <div class="form-group">
            <label>Project Details</label>
            <textarea name="project_details" class="form-control" rows="5" placeholder="Tell me about your vision for the song..." required></textarea>
        </div>
        <button type="submit" class="btn-submit">Send Request <i class="fa fa-paper-plane ml-2"></i></button>
    </form>
</div>
<?php include 'footer.php'; ?>