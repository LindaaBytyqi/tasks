<?php
include "includes/header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Ultimate Guide to Makeup Application</title>

    <style>
        .blog-page {
            max-width: 1400px !important;
            margin: 80px auto;
            padding: 0 25px;
            font-family: Arial, sans-serif;
        }

        .blog-category {
            text-align: center;
            color: #e06d88;
            font-size: 25px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .blog-title {
            text-align: center;
            font-size: 42px;
            line-height: 1.2;
            color: #222;
            margin-bottom: 20px;
        }

        .blog-meta {
            text-align: center;
            color: #777;
            font-size: 17px;
            margin-bottom: 40px;
        }

        .blog-image {
            width: 100%;
            height: 500px;
            overflow: hidden;
            margin-bottom: 45px;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .blog-content {
            max-width: 1100px;
            margin: 0 auto;
            color: #444;
            font-size: 17px;
            line-height: 1.8;
        }

        .blog-content h2 {
            color: #222;
            font-size: 27px;
            margin-top: 40px;
            margin-bottom: 15px;
        }

        .blog-content p {
            margin-bottom: 22px;
            font-size: 22px;
        }

        .blog-content ul {
            padding-left: 25px;
            margin-bottom: 25px;
        }

        .blog-content li {
            margin-bottom: 12px;
            font-size: 18px;
        }

        .blog-content strong {
            color: #222;
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 50px auto 20px;
            padding: 12px 28px;
            background: #e06d88;
            color: white;
            text-decoration: none;
            font-size: 15px;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .back-button:hover {
            background: #d45c78;
        }

        @media (max-width: 768px) {
            .blog-page {
                margin: 50px auto;
            }

            .blog-title {
                font-size: 30px;
            }

            .blog-image {
                height: 300px;
            }

            .blog-content {
                font-size: 16px;
            }

            .blog-content h2 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<section class="blog-page">

    <div class="blog-category">MAKEUP</div>

    <h1 class="blog-title">
        The Ultimate Guide to Makeup Application
    </h1>

    <div class="blog-meta">
        April 20, 2026 &nbsp; • &nbsp; Glowify Beauty Team
    </div>

    <div class="blog-image">
        <img src="images/makeu.png" alt="Makeup Application">
    </div>

    <div class="blog-content">

        <p>
            Makeup is more than just adding color to your face. It's a way
            to enhance your natural beauty, express your personality, and
            feel confident in your own skin. Whether you're getting ready
            for a special occasion or creating your everyday look, the
            right application technique can make all the difference.
        </p>

        <p>
            In this simple guide, we'll walk you through the essential
            steps of creating a beautiful and long-lasting makeup look.
        </p>


        <h2>1. Start With Clean Skin</h2>

        <p>
            Before applying makeup, always start with a clean and
            moisturized face. Cleansing removes dirt and excess oil,
            while moisturizer creates a smooth base for your makeup.
        </p>

        <ul>
            <li><strong>Cleanser:</strong> Remove impurities and excess oil.</li>
            <li><strong>Moisturizer:</strong> Keep your skin hydrated.</li>
            <li><strong>SPF:</strong> Protect your skin during the day.</li>
        </ul>


        <h2>2. Apply Your Primer</h2>

        <p>
            Primer helps create a smooth surface and can help your makeup
            last longer throughout the day. Choose a primer based on your
            skin type and the finish you prefer.
        </p>


        <h2>3. Create Your Base</h2>

        <p>
            Apply foundation evenly using a makeup sponge, brush, or your
            fingertips. Start with a small amount and gradually build
            coverage where you need it most.
        </p>

        <ul>
            <li>Choose a foundation that matches your skin tone.</li>
            <li>Blend carefully around the jawline and hairline.</li>
            <li>Use concealer to cover specific areas.</li>
        </ul>


        <h2>4. Add Color and Definition</h2>

        <p>
            Once your base is complete, add blush, bronzer, and highlighter
            to bring dimension back to your face. A small amount of product
            can create a natural and fresh-looking result.
        </p>


        <h2>5. Don't Forget the Eyes</h2>

        <p>
            Your eyes can become the main focus of your makeup look.
            Start with a neutral eyeshadow and gradually add definition.
            Finish with eyeliner and mascara for a polished appearance.
        </p>


        <h2>6. Finish With Your Lips</h2>

        <p>
            Complete your look with your favorite lip product. For an
            everyday appearance, try a nude or soft pink shade. For a
            more dramatic look, choose a stronger color.
        </p>


        <h2>7. Set Your Makeup</h2>

        <p>
            Finally, use a setting spray to help your makeup stay fresh
            throughout the day. Hold the spray a few inches away from
            your face and apply it evenly.
        </p>


        <h2>Final Beauty Tip</h2>

        <p>
            Remember that makeup doesn't have to be complicated. The best
            makeup look is the one that makes you feel comfortable and
            confident. Start with a few essential products, practice your
            techniques, and don't be afraid to experiment with different
            styles.
        </p>

        <p>
            <strong>
                Enhance your natural beauty, have fun with makeup, and
                create a look that's uniquely yours.
            </strong>
        </p>

    </div>

    <a href="index.php" class="back-button">
        ← BACK TO HOME
    </a>

</section>

</body>
</html>

<?php
include "includes/footer.php";
?>