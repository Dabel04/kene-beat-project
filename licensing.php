<?php include 'header.php'; ?>

<style>
    /* --- PAGE SPECIFIC STYLES (Won't affect other pages) --- */
    body {
        background-color: #000;
        color: #fff;
    }

    /* Hero Section */
    .licensing-hero {
        padding: 120px 0 60px;
        text-align: center;
        background: radial-gradient(circle at center, #1a1a1a 0%, #000 70%);
    }

    .licensing-hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: -1px;
    }

    .licensing-hero h1 span {
        color: #2bee79;
    }

    .licensing-hero p {
        color: #888;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Pricing Grid */
    .pricing-container {
        max-width: 1200px;
        margin: 0 auto 100px;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: start;
    }

    .pricing-card {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 12px;
        padding: 40px 30px;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .pricing-card:hover {
        transform: translateY(-10px);
        border-color: #2bee79;
        box-shadow: 0 10px 30px rgba(43, 238, 121, 0.1);
    }

    /* Highlight Middle Card */
    .pricing-card.featured {
        background: #141414;
        border-color: #2bee79;
        box-shadow: 0 0 20px rgba(43, 238, 121, 0.15);
        transform: scale(1.05);
        z-index: 2;
    }

    .pricing-card.featured:hover {
        transform: scale(1.05) translateY(-10px);
    }

    .badge-recommended {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: #2bee79;
        color: #000;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 20px;
        letter-spacing: 1px;
    }

    .plan-name {
        font-size: 1.5rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
        color: #fff;
    }

    .plan-price {
        font-size: 3rem;
        font-weight: 700;
        color: #2bee79;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
    }

    .plan-price span {
        font-size: 1.2rem;
        margin-top: 10px;
        margin-right: 5px;
        color: #666;
    }

    .divider {
        height: 1px;
        background: #222;
        width: 100%;
        margin: 20px 0;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .feature-list li {
        margin-bottom: 15px;
        color: #aaa;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feature-list li i {
        color: #2bee79;
        font-size: 14px;
    }

    .feature-list li.disabled {
        color: #444;
        text-decoration: line-through;
    }

    .feature-list li.disabled i {
        color: #333;
    }

    .btn-select {
        display: block;
        width: 100%;
        text-align: center;
        padding: 15px;
        margin-top: 30px;
        background: transparent;
        border: 1px solid #2bee79;
        color: #2bee79;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.3s;
    }

    .pricing-card.featured .btn-select,
    .btn-select:hover {
        background: #2bee79;
        color: #000;
    }

    /* FAQ Section */
    .faq-section {
        max-width: 800px;
        margin: 0 auto 100px;
        padding: 0 20px;
    }

    .faq-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .faq-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
    }

    .faq-item {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .faq-question {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #fff;
        transition: 0.3s;
    }

    .faq-question:hover {
        background: #141414;
        color: #2bee79;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        color: #888;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .faq-item.active .faq-answer {
        padding: 20px;
        max-height: 200px;
        border-top: 1px solid #222;
    }

    .faq-icon {
        transition: 0.3s;
    }

    .faq-item.active .faq-icon {
        transform: rotate(180deg);
        color: #2bee79;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .pricing-container {
            grid-template-columns: 1fr;
            max-width: 500px;
        }
        .pricing-card.featured {
            transform: scale(1);
        }
        .pricing-card.featured:hover {
            transform: translateY(-5px);
        }
    }
</style>

<div class="licensing-hero">
    <div class="container">
        <h1>Licensing <span>Options</span></h1>
        <p>Simple, transparent pricing. Choose the license that fits your project needs. Upgrade at any time by paying the difference.</p>
    </div>
</div>

<div class="pricing-container">
    
    <div class="pricing-card">
        <h3 class="plan-name">Basic Lease</h3>
        <div class="plan-price"><span>$</span>25</div>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Perfect for demos and new artists starting out.</p>
        <div class="divider"></div>
        <ul class="feature-list">
            <li><i class="fa fa-check"></i> MP3 File (320kbps)</li>
            <li><i class="fa fa-check"></i> 5,000 Audio Streams</li>
            <li><i class="fa fa-check"></i> 1 Commercial Video</li>
            <li><i class="fa fa-check"></i> Non-Profit Live Performance</li>
            <li><i class="fa fa-check"></i> Instant Download</li>
            <li class="disabled"><i class="fa fa-times"></i> WAV File</li>
            <li class="disabled"><i class="fa fa-times"></i> Tracked Out Stems</li>
        </ul>
        <a href="tracks.php" class="btn-select">Browse Beats</a>
    </div>

    <div class="pricing-card featured">
        <div class="badge-recommended">Most Popular</div>
        <h3 class="plan-name">Premium Lease</h3>
        <div class="plan-price"><span>$</span>100</div>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Professional quality for serious releases.</p>
        <div class="divider"></div>
        <ul class="feature-list">
            <li><i class="fa fa-check"></i> <strong>WAV + MP3 Files</strong></li>
            <li><i class="fa fa-check"></i> 500,000 Audio Streams</li>
            <li><i class="fa fa-check"></i> 10 Commercial Videos</li>
            <li><i class="fa fa-check"></i> For-Profit Live Performance</li>
            <li><i class="fa fa-check"></i> Radio Broadcasting Rights</li>
            <li><i class="fa fa-check"></i> <strong>Tracked Out Stems (+$50)</strong></li>
            <li class="disabled"><i class="fa fa-times"></i> Exclusive Ownership</li>
        </ul>
        <a href="tracks.php" class="btn-select">Browse Beats</a>
    </div>

    <div class="pricing-card">
        <h3 class="plan-name">Exclusive</h3>
        <div class="plan-price"><span>$</span>500+</div>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Full ownership and unlimited rights.</p>
        <div class="divider"></div>
        <ul class="feature-list">
            <li><i class="fa fa-check"></i> WAV + MP3 + Stems</li>
            <li><i class="fa fa-check"></i> <strong>Unlimited Streams</strong></li>
            <li><i class="fa fa-check"></i> Unlimited Videos</li>
            <li><i class="fa fa-check"></i> Unlimited Performance Rights</li>
            <li><i class="fa fa-check"></i> Full Ownership Transferred</li>
            <li><i class="fa fa-check"></i> <strong>Beat Removed from Store</strong></li>
            <li><i class="fa fa-check"></i> Contract Signed</li>
        </ul>
        <a href="contact.php" class="btn-select">Contact to Buy</a>
    </div>

</div>

<div class="faq-section">
    <div class="faq-header">
        <h2>Common Questions</h2>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <span>Can I upgrade my license later?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
        </div>
        <div class="faq-answer">
            Yes! If you buy a Basic Lease and your song blows up, you can upgrade to Premium or Exclusive. You only pay the difference in price. Contact me directly to handle the upgrade.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <span>What are "Tracked Out Stems"?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
        </div>
        <div class="faq-answer">
            Stems are the individual files for every instrument in the beat (Kick, Snare, Piano, Bass, etc.). Having these allows a mixing engineer to mix your vocals perfectly with the beat. This is highly recommended for professional releases.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <span>Is the "Kenton" tag removed?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
        </div>
        <div class="faq-answer">
            Yes. When you purchase any license, you receive a high-quality file with the main voice tags removed. We may leave one small signature tag at the very beginning (intro), but the rest of the beat is tag-free.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <span>What happens if I buy Exclusive Rights?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
        </div>
        <div class="faq-answer">
            Once bought, the beat is instantly removed from the store and cannot be sold to anyone else. You become the new owner of the instrumental. Previous lease owners can still use the beat according to their lease terms, but no new licenses will be sold.
        </div>
    </div>

</div>

<script>
    // Simple FAQ Accordion Logic
    document.querySelectorAll('.faq-question').forEach(item => {
        item.addEventListener('click', () => {
            const parent = item.parentElement;
            
            // Close others
            document.querySelectorAll('.faq-item').forEach(child => {
                if (child !== parent) {
                    child.classList.remove('active');
                }
            });

            // Toggle current
            parent.classList.toggle('active');
        });
    });
</script>

<?php include 'footer.php'; ?>