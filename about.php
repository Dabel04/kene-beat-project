<?php
include 'header.php';
?>

<style>
    /* --- ANIMATIONS --- */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-up {
        opacity: 0; /* Hidden by default until animation starts */
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }

    /* --- PAGE STYLING --- */
    .about-section {
        padding: 80px 0;
        border-bottom: 1px solid #111;
        position: relative;
        overflow: hidden;
    }

    /* Floating background shapes for visual depth */
    .bg-shape {
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(43,238,121,0.05) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%;
        z-index: -1;
    }

    .about-heading {
        color: white;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 25px;
        letter-spacing: -1px;
    }
    
    .about-text {
        color: #aaa;
        font-size: 1.15rem;
        line-height: 1.9;
        margin-bottom: 25px;
    }

    /* --- IMAGE STYLING --- */
    .img-wrapper {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        border: 1px solid #222;
        transition: 0.5s;
    }
    
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .img-wrapper:hover {
        border-color: #2bee79;
        box-shadow: 0 0 30px rgba(43, 238, 121, 0.2);
    }

    .img-wrapper:hover img {
        transform: scale(1.1); /* Zoom effect */
    }

    /* --- STATS STRIP --- */
    .stats-container {
        display: flex;
        justify-content: space-around;
        background: #0f0f0f;
        padding: 40px;
        border-radius: 15px;
        border: 1px solid #222;
        margin: 40px 0;
    }
    .stat-box h3 { font-size: 3rem; font-weight: 800; color: #2bee79; margin: 0; }
    .stat-box p { color: #666; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 2px; margin: 0; }

    /* --- SERVICE CARDS --- */
    .service-card {
        background: #0a0a0a;
        border: 1px solid #222;
        padding: 40px 30px;
        border-radius: 12px;
        text-align: center;
        transition: 0.4s;
        height: 100%;
        position: relative;
        top: 0;
    }
    
    .service-card:hover {
        border-color: #2bee79;
        background: #0f0f0f;
        top: -10px;
        box-shadow: 0 15px 30px rgba(43, 238, 121, 0.15);
    }
    
    .service-icon {
        font-size: 45px;
        color: #2bee79;
        margin-bottom: 25px;
        transition: 0.4s;
    }
    
    .service-card:hover .service-icon {
        transform: scale(1.2) rotate(5deg);
        text-shadow: 0 0 15px #2bee79;
    }

    .service-title { color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 15px; }
    .service-desc { color: #666; font-size: 0.95rem; line-height: 1.6; }
</style>

<div class="hero" style="min-height: 450px; display:flex; align-items:center; position:relative; overflow:hidden;">
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: url('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=1600') center/cover; opacity: 0.3;"></div>
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: radial-gradient(circle, rgba(0,0,0,0.6) 0%, #000 100%);"></div>
    
    <div class="content animate-up" style="text-align: center; width: 100%; position:relative; z-index:2;">
        <h1 style="font-size: 4.5rem; text-shadow: 0 10px 30px rgba(0,0,0,0.8);">We Are <span style="color:#2bee79;">Sound.</span></h1>
        <p style="color: #ccc; font-size: 1.4rem; margin-top: 10px; font-weight:300;">Elevating music production for the modern artist.</p>
    </div>
</div>

<div class="container about-section">
    <div class="bg-shape" style="top: 10%; left: -100px;"></div>
    
    <div class="row align-items-center">
        <div class="col-lg-6 animate-up delay-1">
             <h4 style="color: #2bee79; text-transform: uppercase; letter-spacing: 2px; font-size: 14px; margin-bottom: 10px;">Our Mission</h4>
             <h2 class="about-heading">Redefining the<br>Industry Standard.</h2>
             <p class="about-text">
                KentonTheProducer started with a simple idea: independent artists deserve major-label quality without the gatekeepers.
             </p>
             <p class="about-text">
                We combine analog warmth with digital precision. Whether you need a hard-hitting Trap beat or a soulful R&B composition, our library is curated to inspire your next hit.
             </p>
             
             <div class="stats-container">
                 <div class="stat-box">
                     <h3>5k+</h3>
                     <p>Beats Sold</p>
                 </div>
                 <div class="stat-box">
                     <h3>10+</h3>
                     <p>Years Active</p>
                 </div>
                 <div class="stat-box">
                     <h3>4.9</h3>
                     <p>Star Rating</p>
                 </div>
             </div>
        </div>
        
        <div class="col-lg-6 animate-up delay-2">
             <div class="img-wrapper" style="height: 500px;">
                <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?q=80&w=1000" alt="Studio Mixing">
             </div>
        </div>
    </div>
</div>

<div class="container about-section" style="border:none;">
    <div class="text-center animate-up" style="margin-bottom: 50px;">
        <h2 class="about-heading">The Vibe</h2>
        <p style="color: #666;">Inside the studio where the magic happens.</p>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4 animate-up delay-1">
            <div class="img-wrapper" style="height: 300px;">
                <img src="https://images.unsplash.com/photo-1525201548942-d8732f6617a0?q=80&w=600" alt="Microphone">
            </div>
        </div>
        <div class="col-md-4 mb-4 animate-up delay-2">
            <div class="img-wrapper" style="height: 300px;">
                <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d?q=80&w=600" alt="Keyboard">
            </div>
        </div>
        <div class="col-md-4 mb-4 animate-up delay-3">
            <div class="img-wrapper" style="height: 300px;">
                <img src="https://images.unsplash.com/photo-1519683109079-d5f539e1c42a?q=80&w=600" alt="Speakers">
            </div>
        </div>
    </div>
</div>

<div class="container about-section">
    <div class="bg-shape" style="bottom: 10%; right: -100px;"></div>
    
    <div class="row">
        <div class="col-md-4 mb-4 animate-up delay-1">
            <div class="service-card">
                <i class="fa fa-sliders service-icon"></i>
                <h3 class="service-title">Mix & Mastering</h3>
                <p class="service-desc">
                    Get professional sonic balance. We use top-tier plugins and hardware to make your tracks radio-ready.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4 animate-up delay-2">
            <div class="service-card">
                <i class="fa fa-globe service-icon"></i>
                <h3 class="service-title">Global Distribution</h3>
                <p class="service-desc">
                    We help monetize your music by getting it onto Spotify, Apple Music, and stores worldwide.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4 animate-up delay-3">
            <div class="service-card">
                <i class="fa fa-music service-icon"></i>
                <h3 class="service-title">Custom Production</h3>
                <p class="service-desc">
                    Need a specific sound? We create custom instrumentals tailored to your unique style and vision.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-bottom: 80px;">
    <div class="section-box animate-up delay-2" style="background: linear-gradient(45deg, #111, #0a0a0a); text-align: center; padding: 70px; border-radius: 20px; border: 1px solid #222; position:relative; overflow:hidden;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:200px; height:200px; background:#2bee79; filter:blur(150px); opacity:0.1;"></div>
        
        <h2 style="color: white; margin-bottom: 20px; position:relative; z-index:2;">Ready to create your next hit?</h2>
        <p style="color: #999; margin-bottom: 40px; position:relative; z-index:2;">Join thousands of artists using our platform today.</p>
        <a href="tracks.php" style="position:relative; z-index:2; background: #2bee79; border: none; color: black; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 800; font-size: 1.1rem; transition:0.3s; display:inline-block; box-shadow: 0 0 20px rgba(43,238,121,0.4);" 
           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 40px rgba(43,238,121,0.6)'" 
           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 0 20px rgba(43,238,121,0.4)'">
           BROWSE BEATS
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>