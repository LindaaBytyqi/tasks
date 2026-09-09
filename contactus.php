<?php
include "includes/header.php";
?>

<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us Section</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #2b303a;
      background-color: #f8fafc;
    }
    .contact-section {
      padding-top: 10rem;
      padding-bottom: 17rem;
    }
    .contact-title {
      font-size: 3rem;
      font-weight: 700;
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
      font-size: 1.1rem;
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
      height: 750px;
      min-height: 750px;
      border-radius: 5px;
      overflow: hidden;
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
    }
    .map-container iframe {
      width: 100%;
      height: 750px !important;
      display: block;
      border: 0;
    }
    .form-container { 
      padding: 1.5rem 2rem 1.5rem 2rem; 
      width: 100%; 
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
                color: #0f172a; box-shadow: none !important; 
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
                  min-height: 150px; resize: none;
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
                  .contact-form-section {
                    background-color: #ffffff;
                    border-top: 1px solid #e2e8f0; 
                    padding-top: 8rem !important; 
                    padding-bottom: 8rem !important; 
                  }
                   @media (max-width: 991px) {
                     .form-container {
                       padding: 2rem 0; 
                      } 
                      .main-heading { 
                        font-size: 2.4rem;
                       } 
                       .map-container, .map-container iframe {
                         min-height: 450px; 
                         height: 450px; 
                        } 
                      } 
                    @media (max-width: 767px) {
                       .form-container { 
                        padding: 1rem 0;
                       } 
                       .main-heading {
                         font-size: 2rem; 
                        } 
                        .form-description {
                          font-size: 0.95rem; 
                          margin-bottom: 2rem; 
                        }
                         .map-container, .map-container iframe {
                           min-height: 350px;
                            height: 350px; 
                          } 
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
                        .map-container {
                          height: 500px;
                          min-height: 500px;
                         } 
                         .map-container iframe {
                          height: 500px !important;
                          } 
                        .main-heading { 
                          font-size: 2.4rem; 
                          margin-top: 2rem; 
                        } 
                        .form-container {
                           padding: 2rem 0;
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
                          .map-container {
                            height: 350px;
                            min-height: 350px;
                            border-radius: 15px; 
                          } 
                          .map-container iframe { 
                            height: 350px !important;
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
                        .map-container {
                          height: 300px;
                          min-height: 300px;
                        } 
                        .map-container iframe {
                          height: 300px !important;
                        } 
                        .contact-form-section .container {
                          width: 92%; 
                        } 
                      }

  </style>
</head>

<body>
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
              Mon–Fri: 9 AM – 6 PM
            </p>

            <p class="mb-1">
              Saturday: 11 AM – 4 PM
            </p>

            <p class="mb-0">
              Sunday: Closed
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
              176 Street name, New York,
            </p>

            <p class="mb-0">
              NY 10014
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
              Phone: +383 49 123 456
            </p>

            <p class="mb-0">
              Email: info@yoursite.com
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
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9933.279883582453!2d-0.12462541818257091!3d51.50332402137025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487604b900d26973%3A0x4291f3172409ea92!2slondon%20eye!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="ps-lg-3">
            <span class="subtitle text-uppercase fw-bold mb-3">Contact Us</span>
            <h2 class="main-heading my-3">Any Questions?</h2>
            
            <form class="mt-4">
              <div class="row g-4 mb-4">
                <div class="col-md-6">
                  <input type="text" class="form-control custom-input" placeholder="Name">
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control custom-input" placeholder="Last Name">
                </div>
              </div>

              <div class="row g-4 mb-4">
                <div class="col-md-6">
                  <input type="email" class="form-control custom-input" placeholder="Email">
                </div>
                <div class="col-md-6">
                  <input type="tel" class="form-control custom-input" placeholder="Phone">
                </div>
              </div>

              <div class="mb-5">
                <textarea class="form-control custom-input" rows="3" placeholder="Message"></textarea>
              </div>

              <button type="submit" class="btn custom-btn">
                <i class="bi bi-send-fill me-2"></i> Get In Touch
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>

<?php 
include  "includes/footer.php"
?>