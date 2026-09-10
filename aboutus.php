<?php
include "includes/header.php";
?>
<style>

.about-page {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #222;
    overflow: hidden;
    background: #fff;
}
.about-page * {
    box-sizing: border-box;
}
.about-page img {
    display: block;
}
.about-section-heading {
    text-align: center;
    margin-bottom: 65px;
}

.about-section-heading h2 {
    margin: 16px 0 15px;
    font-size: clamp(42px, 5vw, 65px);
    line-height: 1.05;
    letter-spacing: -2.5px;
    font-weight: 700;
}

.about-section-heading h2 span,
.about-story-content h2 span,
.about-promise h2 span {
    color: #eb3f81;
}

.about-section-heading p {
    max-width: 600px;
    margin: 0 auto;
    color: #777;
    font-size: 15px;
    line-height: 1.8;
}

.about-eyebrow,
.about-section-label {
    color: #eb3f81;
    font-size: 15px;
    /* margin-top: 400px !important; */
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
}

.about-hero {
    height: 680px;
    min-height: 600px;
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.about-hero-image {
    position: absolute;
    inset: 0;
}

.about-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.about-hero-overlay {
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(255,255,255,.97) 0%,
            rgba(255,255,255,.88) 32%,
            rgba(255,255,255,.35) 63%,
            rgba(255,255,255,.04) 100%
        );
}

.about-hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding: 190px 80px 80px; 
}

.about-hero h1 {
    margin: 22px 0 25px;
    max-width: 680px;
    font-size: clamp(52px, 6vw, 88px);
    line-height: 1.02;
    font-weight: 700;
    letter-spacing: -4px;
}

.about-hero h1 span {
    display: block;
    color: #eb3f81;
}
.about-hero p {
    max-width: 540px;
    margin-bottom: 35px;
    color: #555;
    font-size: 19px;
    line-height: 1.85;
}
.about-primary-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 27px;
    background: #eb3f81;
    color: white;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.3px;
    border-radius: 50px;
    transition: all .3s ease;
}
.about-primary-btn:hover {
    color: white;
    transform: translateY(-3px);
    box-shadow:
        0 12px 30px rgba(235,63,129,.25);
}

.about-primary-btn i {
    transition: transform .3s ease;
}
.about-primary-btn:hover i {
    transform: translateX(5px);
}




.about-story {
    max-width: 1720px;
    margin: 0 auto;
    padding: 125px 60px;
    display: grid;
    grid-template-columns: .95fr 1fr;
    gap: 95px;
    align-items: center;
}

.about-story-image {
    height: 610px;
    width: 800px;
    overflow: hidden;
    border-radius: 28px;
    position: relative;
}

.about-story-image::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.12),
            transparent 45%
        )
}

.about-story-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .8s ease;
}

.about-story-image:hover img {
    transform: scale(1.04);
}

.about-story-content {
    max-width: 600px;
}

.about-story-content h2 {
    margin: 18px 0 22px;
    font-size: clamp(43px, 5vw, 65px);
    line-height: 1.04;
    letter-spacing: -2.5px;
}

.about-title-line {
    width: 55px;
    height: 2px;
    background: #eb3f81;
    margin-bottom: 30px;
}

.about-story-content p {
    color: #777;
    font-size: 18px;
    line-height: 1.9;
    margin-bottom: 18px;
}

.about-story-stats {
    display: flex;
    gap: 45px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.story-stat {
    display: flex;
    flex-direction: column;
}

.story-stat strong {
    color: #eb3f81;
    font-size: 29px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 8px;
}

.story-stat span {
    color: #777;
    font-size: 12px;
    letter-spacing: .7px;
}







.about-team {
    background: #faf7f8;
    padding: 120px 60px 135px;
    position: relative;
}

.about-team .about-section-heading {
    max-width: 850px;
    margin: 0 auto 70px;
}
.team-grid {
    max-width: 1250px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    align-items: start;
}
.team-card {
    position: relative;
    background: transparent;
    border-radius: 0;
    overflow: visible;
    transition: transform .35s ease;
}
.team-card:hover {
    transform: translateY(-5px);
}
.team-image {
    position: relative;
    width: 100%;
    height: 450px;
    overflow: hidden;
    border-radius: 0;
    background: #f2e8ec;
}
.team-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition:
        transform .7s cubic-bezier(.2, .7, .2, 1);
}
.team-card:hover .team-image img {
    transform: scale(1.04);
}
.team-image::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            to top,
            rgba(0, 0, 0, .12),
            transparent 40%
        );

    opacity: 0;
    transition: opacity .35s ease;
}
.team-card:hover .team-image::after {
    opacity: 1;
}
.team-info {
    padding: 24px 5px 0;
}
.team-info::before {
    content: "";
    display: block;
    width: 42px;
    height: 2px;
    background: #eb3f81;
    margin-bottom: 18px;
    transition: width .35s ease;
}
.team-card:hover .team-info::before {
    width: 70px;
}
.team-info > span {
    display: block;
    color: #eb3f81;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    margin-bottom: 9px;
}
.team-info h3 {
    margin: 0 0 10px;
    color: #222;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -.7px;
    line-height: 1.2;
}
.team-info p {
    max-width: 340px;
    margin: 0;
    color: #777;
    font-size: 15px;
    line-height: 1.8;
}
.team-card::before,
.team-card::after {
    display: none;
}
.team-card:nth-child(2),
.team-card:nth-child(3) {
    margin-top: 0;
}
@media (max-width: 1000px) {
    .about-team {
        padding: 100px 35px 110px;
    }

    .team-grid {
        gap: 22px;
    }

    .team-image {
        height: 380px;
    }

    .team-info {
        padding-top: 22px;
    }

    .team-info h3 {
        font-size: 22px;
    }

    .team-info p {
        font-size: 12px;
    }
}

@media (max-width: 767px) {

    .about-team {
        padding: 80px 20px 90px;
    }

    .about-team .about-section-heading {
        margin-bottom: 55px;
    }

    .team-grid {
        grid-template-columns: 1fr;

        gap: 55px;

        max-width: 380px;
    }

    .team-card {
        width: 100%;
        margin: 0 !important;
    }

    .team-image {
        height: 430px;
    }

    .team-info {
        padding: 22px 4px 0;
    }

    .team-info h3 {
        font-size: 24px;
    }

    .team-info p {
        max-width: 100%;
        font-size: 13px;
    }
}













.about-values {
    padding: 125px 60px 135px;
    background: #fff;
}
.about-values .about-section-heading {
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}
.values-grid {
    max-width: 1250px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}
.value-card {
    padding: 45px 30px;
    background: #faf7f8;
    border-radius: 24px;
    text-align: center;
    border: 1px solid transparent;
    transition: all .35s ease;
}

.value-card:hover {
    transform: translateY(-8px);

    background: #fff;

    border-color: #f2dce5;

    box-shadow:
        0 20px 45px rgba(0,0,0,.06);
}

.value-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: #fff0f6;
    color: #eb3f81;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    transition: all .35s ease;
}

.value-card:hover .value-icon {
    transform: scale(1.08);

    background: #eb3f81;

    color: white;
}

.value-card h3 {
    margin: 0 0 13px;
    font-size: 20px;
    font-weight: 700;
}

.value-card p {
    margin: 0;
    color: #777;
    font-size: 15px;
    line-height: 1.8;
}

.about-collections {
    padding: 120px 60px;
    background: #faf7f8;
}

.collections-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

.collection-card {
    height: 450px;
    position: relative;
    overflow: hidden;
    border-radius: 27px;
    text-decoration: none;
}

.collection-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .7s ease;
}

.collection-card:hover img {
    transform: scale(1.07);
}

.collection-overlay {
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.75),
            rgba(0,0,0,.05) 65%
        );
}

.collection-content {
    position: absolute;

    left: 32px;
    right: 32px;
    bottom: 30px;

    color: white;
}

.collection-content > span {
    font-size: 10px;
    letter-spacing: 2.5px;
    opacity: .8;
}

.collection-content h3 {
    margin: 8px 0 5px;
    font-size: 32px;
    line-height: 1.1;
}

.collection-content p {
    margin: 0 0 18px;
    font-size: 13px;
    opacity: .85;
}

.collection-link {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-size: 10px;
    letter-spacing: 1.5px;
    font-weight: 700;
}

.collection-link i {
    transition: transform .3s ease;
}

.collection-card:hover .collection-link i {
    transform: translateX(5px);
}

.about-benefits {
    max-width: 1400px;
    margin: 120px auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: #faf7f8;
    border-radius: 30px;
    overflow: hidden;
}

.about-benefits-image {
    min-height: 680px;
}

.about-benefits-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.about-benefits-content {
    padding: 85px 75px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.about-benefits-content h2 {
    margin: 15px 0 18px;
    font-size: clamp(40px, 4vw, 58px);
    line-height: 1.05;
    letter-spacing: -2px;
}

.benefits-intro {
    max-width: 520px;
    margin-bottom: 30px;
    color: #777;
    line-height: 1.8;
    font-size: 14px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #eadfe3;
}

.benefit-item:last-child {
    border-bottom: none;
}

.benefit-icon {
    width: 52px;
    height: 52px;
    flex-shrink: 0;
    border-radius: 50%;
    background: white;
    color: #eb3f81;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.benefit-item h3 {
    margin: 0 0 5px;
    font-size: 16px;
}

.benefit-item p {
    margin: 0;
    color: #888;
    font-size: 12px;
}



.about-quote {
    height: 650px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
}
.about-quote-image {
    position: absolute;
    inset: 0;
}
.about-quote-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.about-quote-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            90deg,
            rgba(0,0,0,.67),
            rgba(0,0,0,.22),
            rgba(0,0,0,.04)
        );
}
.about-quote-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    padding: 30px 60px;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.about-quote-content > span,
.about-quote-content h2,
.about-quote-content p {
    width: 100%;
    max-width: 500px;
}

.about-quote-content > span {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
}
.about-quote-content h2 {
    margin: 22px 0;
    font-size: clamp(50px, 6vw, 85px);
    line-height: .98;
    letter-spacing: -4px;
}
.about-quote-content p {
    max-width: 450px;
    margin: 0 0 30px;
    font-size: 16px;
    line-height: 1.8;
    opacity: .9;
}
.about-light-btn {
    display: inline-flex;
    width: fit-content !important;
    max-width: none !important;
    align-self: flex-end;
    align-items: center;
    gap: 12px;
    padding: 16px 27px;
    border: 1px solid white;
    border-radius: 50px;
    color: white;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    transition: all .3s ease;
}
.about-light-btn:hover {
    background: white;
    color: #222;
}
.about-light-btn i {
    transition: transform .3s ease;
}
.about-light-btn:hover i {
    transform: translateX(5px);
}






.about-promise {
    max-width: 850px;
    margin: auto;
    padding: 135px 30px;
    text-align: center;
}
.about-promise h2 {
    margin: 18px 0 22px;
    font-size: clamp(43px, 5vw, 65px);
    line-height: 1.04;
    letter-spacing: -2.5px;
}
.about-promise p {
    max-width: 650px;
    margin: 0 auto 35px;
    color: #777;
    line-height: 1.9;
    font-size: 16px;
}



@media (max-width: 1100px) {
    .about-story {
        grid-template-columns: 1fr;
        gap: 55px;
        padding: 95px 40px;
    }
    .about-story-image {
        height: 550px;
    }
    .about-story-content {
        max-width: 750px;
    }
    .team-grid {
        gap: 30px;
    }
    .team-image {
        height: 390px;
    }
    .team-card:nth-child(2) {
        margin-top: 30px;
    }
    .team-card:nth-child(3) {
        margin-top: 60px;
    }
    .team-card::before {
        font-size: 72px;
        top: -35px;
    }
    .values-grid {
        grid-template-columns: repeat(2, 1fr);

        max-width: 800px;
    }

    .about-benefits {
        margin-left: 30px;
        margin-right: 30px;
    }

    .about-benefits-content {
        padding: 60px 45px;
    }
}
@media (max-width: 767px) {

    .about-section-heading {
        margin-bottom: 45px;
    }

    .about-section-heading h2 {
        font-size: 39px;

        letter-spacing: -1.5px;
    }

    .about-section-heading p {
        font-size: 14px;
    }

    .about-hero {
        height: 650px;

        min-height: 600px;
    }

    .about-hero-image img {
        object-position: 65% center;
    }

    .about-hero-overlay {
        background:
            linear-gradient(
                180deg,
                rgba(255,255,255,.94),
                rgba(255,255,255,.72),
                rgba(255,255,255,.30)
            );
    }

    .about-hero-content {
        padding: 40px 25px;
    }

    .about-eyebrow {
        font-size: 9px;
        letter-spacing: 2px;
    }

    .about-hero h1 {
        font-size: 52px;

        letter-spacing: -2.5px;
    }

    .about-hero p {
        font-size: 15px;

        line-height: 1.7;
    }

    .about-story {
        padding: 75px 25px;

        gap: 40px;
    }

    .about-story-image {
        height: 430px;

        border-radius: 22px;
    }

    .about-story-content h2 {
        font-size: 39px;

        letter-spacing: -1.5px;
    }

    .about-story-content p {
        font-size: 14px;

        line-height: 1.8;
    }

    .about-story-stats {
        gap: 20px;

        justify-content: space-between;
    }

    .story-stat strong {
        font-size: 23px;
    }

    .story-stat span {
        font-size: 9px;
    }


    .about-team {
        padding: 80px 25px 90px;
    }

    .about-team .about-section-heading {
        margin-bottom: 65px;
    }

    .team-grid {
        grid-template-columns: 1fr;

        gap: 70px;

        max-width: 380px;
    }

    .team-card {
        width: 100%;

        max-width: 360px;

        margin: 0 auto !important;
    }

    .team-image {
        height: 430px;

        border-radius:
            180px
            180px
            25px
            25px;
    }

    .team-card::before {
        font-size: 80px;

        top: -30px;

        left: -8px;
    }

    .team-info {
        padding-top: 23px;
    }

    .team-info h3 {
        font-size: 23px;
    }

    .team-info p {
        font-size: 13px;

        max-width: 320px;
    }

    .team-card::after {
        opacity: 1;

        transform: none;

        right: 4px;

        bottom: 4px;
    }


    /* VALUES */

    .about-values {
        padding: 80px 20px 90px;
    }

    .values-grid {
        grid-template-columns: 1fr;

        gap: 15px;
    }

    .value-card {
        padding: 32px 25px;
    }


    /* COLLECTIONS */

    .about-collections {
        padding: 80px 20px;
    }

    .collections-grid {
        grid-template-columns: 1fr;

        gap: 15px;
    }

    .collection-card {
        height: 400px;
    }

    .collection-content h3 {
        font-size: 29px;
    }


    /* BENEFITS */

    .about-benefits {
        grid-template-columns: 1fr;

        margin: 0 15px 80px;

        border-radius: 22px;
    }

    .about-benefits-image {
        min-height: 400px;
    }

    .about-benefits-content {
        padding: 55px 28px;
    }

    .about-benefits-content h2 {
        font-size: 39px;

        letter-spacing: -1.5px;
    }

    .benefits-intro {
        font-size: 14px;
    }

    .about-quote {
        height: 600px;
    }

    .about-quote-content {
        padding: 35px 25px;
    }

    .about-quote-content > span {
        font-size: 9px;

        letter-spacing: 2px;
    }

    .about-quote-content h2 {
        font-size: 55px;

        letter-spacing: -2.5px;
    }

    .about-quote-content p {
        font-size: 14px;
    }

    .about-promise {
        padding: 80px 25px;
    }

    .about-promise h2 {
        font-size: 39px;

        letter-spacing: -1.5px;
    }
    .about-promise p {
        font-size: 14px;
    }
}
</style>


<main class="about-page">
<section class="about-hero">
    <div class="about-hero-image">
        <img
            src="images/abouttt.png"
            alt="Beauty products and skincare">
    </div>
    <div class="about-hero-overlay"></div>
    <div class="about-hero-content">
        <span class="about-eyebrow">
            WELCOME TO OUR BEAUTY WORLD
        </span>

        <h1>
            Beauty Made
            <span>Personal.</span>
        </h1>
        <p>
            Discover carefully selected beauty essentials designed
            to make every routine feel a little more special.
        </p>
        <a href="product.php" class="about-primary-btn">
            EXPLORE OUR COLLECTION
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

<section class="about-story">
    <div class="about-story-image">
        <img
            src="images/about.png"
            alt="Beauty products and skincare routine">
    </div>


    <div class="about-story-content">
        <span class="about-section-label">
            OUR STORY
        </span>
        <h2>
            More Than Beauty.
            <span>A Feeling.</span>
        </h2>
        <div class="about-title-line"></div>
        <p>
            We believe beauty is more than appearance.
            It's about feeling confident, comfortable and
            completely yourself.
        </p>
        <p>
            Our collection brings together carefully selected
            products across skincare, makeup, haircare, bodycare
            and fragrance, making it easier to discover products
            that naturally fit into your everyday routine.
        </p>
        <p>
            Whether you're creating your morning skincare ritual,
            getting ready for a special occasion or simply taking
            a moment for yourself, we're here to make every beauty
            moment feel special.
        </p>
        <div class="about-story-stats">
            <div class="story-stat">
                <strong>100+</strong>
                <span>BEAUTY PRODUCTS</span>
            </div>

            <div class="story-stat">
                <strong>5</strong>
                <span>BEAUTY CATEGORIES</span>
            </div>

            <div class="story-stat">
                <strong>100%</strong>
                <span>CUSTOMER CARE</span>
            </div>
        </div>
    </div>
</section>




<section class="about-team"> 
    <div class="about-section-heading"> 
        <span class="about-section-label"> MEET THE TEAM </span> 
        <h2> The People Behind <span>The Brand.</span> </h2>
         <!-- <p> A small team with a shared passion for beauty, thoughtful products 
            and creating a better shopping experience.
         </p>  -->
        </div> 
         <div class="team-grid"> <div class="team-card">
             <div class="team-image"> 
                <img src="images/account.png" alt="Linda Bytyqi - Founder">
             </div> 
        <div class="team-info">
             <span>FOUNDER</span>
              <h3>Lorem Ipsum</h3> 
              <p> Creating a beauty experience built around confidence, care and individuality. </p>
        </div> 
    </div> 
    <div class="team-card">
        <div class="team-image">
             <img src="images/account.png" alt="Emma Johnson - Beauty Specialist"> 
        </div>
             <div class="team-info"> 
                <span>BEAUTY SPECIALIST</span> 
                <h3>Lorem Ipsum</h3>
                 <p> Passionate about discovering products that make everyday beauty routines feel effortless. </p>
                 </div> 
                </div> 
                <div class="team-card">
                     <div class="team-image"> 
                        <img src="images/account.png" alt="Sophia Williams - Customer Experience">
                     </div> 
                <div class="team-info"> 
                    <span>CUSTOMER EXPERIENCE</span> 
                    <h3>Lorem Ipsum</h3>
                     <p> Making sure every customer feels valued from their first visit to every order after. </p>
                </div> 
            
        </div>
    </div> 
</section>





<section class="about-values">
    <div class="about-section-heading">
        <span class="about-section-label">
            WHAT WE BELIEVE IN
        </span>

        <h2>
            Beauty With
            <span>Purpose.</span>
        </h2>

        <p>
            Everything we do is centered around making beauty
            feel simple, inspiring and personal.
        </p>

    </div>


    <div class="values-grid">
        <div class="value-card">
            <div class="value-icon">
                <i class="bi bi-gem"></i>
            </div>
            <h3>Quality First</h3>
            <p>
                Carefully selected products chosen with quality,
                beauty and everyday use in mind.
            </p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="bi bi-stars"></i>
            </div>
            <h3>Beauty For Everyone</h3>
            <p>
                Beauty is personal. Your routine should reflect
                what makes you feel confident and comfortable.
            </p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="bi bi-flower1"></i>
            </div>
            <h3>Everyday Self-Care</h3>
            <p>
                Taking care of yourself doesn't have to be
                complicated. Small moments matter.
            </p>
        </div>
        <div class="value-card">
            <div class="value-icon">
                <i class="bi bi-heart"></i>
            </div>
            <h3>Confidence Matters</h3>
            <p>
                We believe the best beauty look is the one
                that makes you feel like yourself.
            </p>
        </div>
    </div>
</section>



<section class="about-quote">
    <div class="about-quote-image">
        <img
            src="images/fundd.png"
            alt="Beauty skincare ritual">
    </div>
    <div class="about-quote-overlay"></div>
    <div class="about-quote-content">
        <div class="about-quote-wrapper">
        <span>
            YOUR BEAUTY. YOUR ROUTINE. YOUR WAY.
        </span>
        <h2>
            Take Time
            <br>
            For Yourself.
        </h2>
        <p>
            Discover products that make your everyday
            beauty routine feel special.
        </p>
        <a href="product.php"
           class="about-light-btn">
            SHOP COLLECTION
            <i class="bi bi-arrow-right"></i>
        </a>
        </div>
    </div>
</section>



<section class="about-promise">
    <span class="about-section-label">
        OUR PROMISE
    </span>
    <h2>
        Beauty Should Feel
        <span>Good.</span>
    </h2>
    <p>
        We're committed to creating a shopping experience
        that feels simple, inspiring and trustworthy — from
        discovering your next favorite product to receiving
        it at your door.
    </p>
    <!-- <a href="categories.php"
       class="about-primary-btn">
        START SHOPPING
        <i class="bi bi-arrow-right"></i>
    </a> -->
</section>

</main>
<?php
include "includes/footer.php";
?>
