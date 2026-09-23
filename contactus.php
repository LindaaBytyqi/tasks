<?php

include "includes/header.php";
include "includes/database.php";

$contactSuccess = $_SESSION['contact_success'] ?? '';
$contactError = $_SESSION['contact_error'] ?? '';

unset($_SESSION['contact_success'], $_SESSION['contact_error']);

$stmt = $conn->prepare("
    SELECT *
    FROM contact_settings
    WHERE id = 1
    LIMIT 1
");

$stmt->execute();
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {

    $contact = [
        'weekday_hours' => '9 AM – 6 PM',
        'saturday_hours' => '11 AM – 4 PM',
        'sunday_hours' => 'Closed',
        'address' => '123 Street, London Eye',
        'postal_code' => '10014',
        'phone' => '+383 49 123 456',
        'email' => 'info@yoursite.com',
        'map_embed' => ''
    ];

}

?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Contact Us</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
        rel="stylesheet"
    >
    <style>

        body {
            color: #2b303a;
            background-color: #f8fafc;
        }
        .contact-section {
            padding-top: 12rem;
            padding-bottom: 12rem;
        }
        .contact-title {
            font-size: 3rem;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: -1px;
            margin-bottom: 6rem !important;
        }
        .contact-card {
            position: relative;
            background: #ffffff;
            padding: 2.1rem 3rem;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.04);
            transition: all 0.3s ease;
            height: 100%;
        }
        .contact-card:hover {
            border-color: #eb259f;
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.09);
        }
        .icon-wrapper {
            width: 78px;
            height: 78px;
            background-color: #eff6ff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.8rem;
        }
        .icon-wrapper i {
            font-size: 2.2rem;
            color: #070707;
        }
        .contact-card h5 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }
        .contact-card p {
            font-size: 1.2rem;
            color: #505863;
            line-height: 1.7;
        }
        .contact-form-section {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding-top: 8rem !important;
            padding-bottom: 8rem !important;
        }

        .contact-form-section .container {
            max-width: 1700px;
            width: 100%;
        }
        .map-container {
            width: 100%;
            min-height: 580px;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
        }

        .map-container iframe {
            width: 100%;
            height: 580px;
            display: block;
            border: 0;
        }
        .subtitle {
            color: #eb259f;
            font-size: 1rem;
            letter-spacing: 2px;
            background-color: #f8f9fc;
            padding: 10px 18px;
            border-radius: 24px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .main-heading {
            font-size: 2.8rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.8px;
            margin-top: 8.2rem;
            margin-bottom: 1.2rem;
        }

        .form-description {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.7;
            max-width: 560px;
            margin-bottom: 2.8rem;
        }

        .custom-input {
            border: none;
            border-bottom: 2px solid #e2e8f0;
            border-radius: 0;
            padding: 15px 0;
            background-color: transparent;
            font-size: 1.3rem;
            color: #0f172a;
            box-shadow: none !important;
            transition: all 0.3s ease;
        }
        .custom-input::placeholder {
            color: #94a3b8;
        }
        .custom-input:focus {
            border-bottom-color: #f36abe;
            background-color: transparent;
        }
        .textarea.custom-input {
            min-height: 150px;
            resize: none;
        }
        .custom-btn {
            background-color: #e242a2;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            padding: 15px 30px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }

        .custom-btn:hover {
            background-color: #e242a2;
            color: #ffffff;
            box-shadow: 0 15px 25px rgba(78, 83, 95, 0.3);
            transform: translateY(-1px);
        }

        @media (max-width: 991px) {
            .contact-section {
                padding-top: 7rem;
                padding-bottom: 7rem;
            }
            .contact-title {
                font-size: 2.5rem;
                margin-bottom: 4rem !important;
            }
            .contact-card {
                padding: 2rem 1.5rem;
            }
            .contact-card h5 {
                font-size: 1.4rem;
            }
            .contact-card p {
                font-size: 1rem;
            }
            .contact-form-section {
                padding-top: 5rem !important;
                padding-bottom: 5rem !important;
            }
            .map-container,
            .map-container iframe {
                min-height: 500px;
                height: 500px;
            }
            .main-heading {
                font-size: 2.4rem;
                margin-top: 2rem;
            }
            .custom-input {
                font-size: 1.1rem;
            }
        }


        @media (max-width: 767px) {

            .contact-section {
                padding-top: 5rem;
                padding-bottom: 5rem;
            }

            .contact-title {
                font-size: 2.2rem;
                margin-bottom: 3rem !important;
            }

            .contact-card {
                padding: 2rem 1.2rem;
            }

            .icon-wrapper {
                width: 65px;
                height: 65px;
                margin-bottom: 1.3rem;
            }

            .icon-wrapper i {
                font-size: 1.8rem;
            }

            .contact-card h5 {
                font-size: 1.3rem;
            }

            .contact-card p {
                font-size: 0.95rem;
            }

            .contact-form-section {
                padding-top: 4rem !important;
                padding-bottom: 4rem !important;
            }

            .map-container,
            .map-container iframe {
                min-height: 350px;
                height: 350px;
            }

            .main-heading {
                font-size: 2rem;
                margin-top: 1.5rem;
            }

            .subtitle {
                font-size: 0.85rem;
                padding: 8px 14px;
            }

            .custom-input {
                font-size: 1rem;
                padding: 12px 0;
            }

            .custom-btn {
                width: 100%;
                padding: 14px 20px;
            }

        }


        @media (max-width: 480px) {

            .contact-title {
                font-size: 1.9rem;
            }

            .contact-card {
                padding: 1.8rem 1rem;
            }

            .main-heading {
                font-size: 1.8rem;
            }

            .map-container,
            .map-container iframe {
                height: 300px;
                min-height: 300px;
            }

            .contact-form-section .container {
                width: 92%;
            }

        }

.contact-popup {
    position: fixed;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
}

.contact-popup-content {
    width: 370px;
    padding: 25px 20px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.18);
    text-align: center;
    animation: contactPopup .3s ease;
}

.contact-popup-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 55px;
    height: 55px;
    margin: 0 auto;
    border-radius: 50%;
    font-size: 28px;
    font-weight: bold;
}

.success-popup .contact-popup-icon {
    background: #dcfce7;
    color: #16a34a;
}

.error-popup .contact-popup-icon {
    background: #fee2e2;
    color: #dc2626;
}

.contact-popup-content p {
    margin: 15px 0 0;
    font-size: 17px;
    font-weight: 600;
    color: #111827;
}

@keyframes contactPopup {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@media (max-width: 600px) {
       .contact-popup {
        top: 90px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        width: calc(100% - 40px);
    }

    .contact-popup-content {
        width: 100%;
    }
}
    </style>

</head>
<body>

<?php if ($contactSuccess !== ''): ?>

<div class="contact-popup success-popup">
    <div class="contact-popup-content">

        <div class="contact-popup-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <p><?= htmlspecialchars($contactSuccess, ENT_QUOTES, 'UTF-8'); ?></p>

    </div>
</div>

<?php elseif ($contactError !== ''): ?>

<div class="contact-popup error-popup">
    <div class="contact-popup-content">

        <div class="contact-popup-icon">
            <i class="bi bi-x-lg"></i>
        </div>

        <p><?= htmlspecialchars($contactError, ENT_QUOTES, 'UTF-8'); ?></p>

    </div>
</div>

<?php endif; ?>


<section class="contact-section">

    <div class="container text-center">

        <h2 class="contact-title">
            Contacts
        </h2>


        <div class="row g-4">

            <div class="col-lg-4 col-md-6">

                <div class="contact-card">

                    <div class="icon-wrapper">
                        <i class="bi bi-clock"></i>
                    </div>


                    <h5>
                        Open hours
                    </h5>


                    <p class="mb-1">
                        <?= htmlspecialchars(
                            $contact['weekday_hours'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>


                    <p class="mb-1">
                        <?= htmlspecialchars(
                            $contact['saturday_hours'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>


                    <p class="mb-0">
                        <?= htmlspecialchars(
                            $contact['sunday_hours'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>

                </div>

            </div>


            <div class="col-lg-4 col-md-6">

                <div class="contact-card">

                    <div class="icon-wrapper">
                        <i class="bi bi-geo-alt"></i>
                    </div>


                    <h5>
                        Address
                    </h5>


                    <p class="mb-1">

                        <?= htmlspecialchars(
                            $contact['address'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>


                    <p class="mb-0">

                        <?= htmlspecialchars(
                            $contact['postal_code'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>

                </div>

            </div>


            <div class="col-lg-4 col-md-6">

                <div class="contact-card">

                    <div class="icon-wrapper">
                        <i class="bi bi-headset"></i>
                    </div>


                    <h5>
                        Get in touch
                    </h5>


                    <p class="mb-1">

                        Phone:
                        <?= htmlspecialchars(
                            $contact['phone'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>


                    <p class="mb-0">

                        Email:
                        <?= htmlspecialchars(
                            $contact['email'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



<section class="contact-form-section py-5">

    <div class="container my-4">

        <div class="row g-5 align-items-center">

            <div class="col-lg-6">
                <div class="map-container">

                    <?php if (!empty($contact['map_embed'])): ?>

                        <iframe
                            src="<?= htmlspecialchars(
                                $contact['map_embed'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>

                    <?php else: ?>

                        <div
                            class="d-flex align-items-center justify-content-center h-100 text-muted"
                        >
                            Map location not available.
                        </div>

                    <?php endif; ?>

                </div>
            </div>

            <div class="col-lg-6">

                <div class="ps-lg-3">


                    <span class="subtitle text-uppercase fw-bold mb-3">
                        Contact Us
                    </span>


                    <h2 class="main-heading my-3">
                        Any Questions?
                    </h2>


                    <form class="mt-4" action="sendcontact.php" method="POST">

                        <div class="row g-4 mb-4">

                            <div class="col-md-6">

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control custom-input"
                                    placeholder="Name"
                                    pattern="[A-Za-zÀ-ÿ\s]+"
                                    title="Name can contain only letters and spaces."
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <input
                                    type="text"
                                    name="lastName"
                                    class="form-control custom-input"
                                    placeholder="Last Name"
                                    pattern="[A-Za-zÀ-ÿ\s]+"
                                    title="Name can contain only letters and spaces."
                                    required
                                >

                            </div>


                        </div>


                        <div class="row g-4 mb-4">


                            <div class="col-md-6">

                                <input
                                    type="email"
                                     name="email"
                                    class="form-control custom-input"
                                    placeholder="Email"
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <input
                                    type="tel"
                                    name="phone"
                                    class="form-control custom-input"
                                    placeholder="Phone"
                                    pattern="[0-9+\-\s()]+"
                                    title="Please enter a valid phone number."
                                    required
                                >

                            </div>


                        </div>


                        <div class="mb-5">
                            <textarea
                                class="form-control custom-input"
                                rows="3"
                                name="message"
                                placeholder="Message"
                                required
                            ></textarea>
                        </div>


                        <button
                            type="submit"
                            class="btn custom-btn"
                        >
                            <i class="bi bi-send-fill me-2"></i>
                            Get In Touch
                        </button>

                    </form>

                </div>


            </div>

        </div>

    </div>

</section>
</body>
</html>


<script>
const contactPopup = document.querySelector('.contact-popup');

if (contactPopup) {
    setTimeout(() => {
        contactPopup.style.opacity = '0';

        setTimeout(() => {
            contactPopup.remove();
        }, 300);

    }, 3000);
}



</script>


<?php include "includes/footer.php"; ?>