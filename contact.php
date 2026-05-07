<?php
require_once('functions.php');
$contact = $p_content['contact'];
$cust = $p_content['customization'];

$pageTitle = "Contact - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- Contact Area Start -->
    <?php if (is_section_active('contact')): ?>
    <section class="contact-area tmp-section-gap" id="contact" style="padding-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center" style="margin-top: 80px;">
                        <div class="section-sub-title">
                            <span class="subtitle">CONTACT ME</span>
                        </div>
                        <h2 class="title split-collab">Get In Touch</h2>
                    </div>
                </div>
            </div>

            <div class="row g-5 mt--30">
                <!-- Email -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center" style="height: 100%;">
                        <div class="service-card-icon mb-4">
                            <i class="fa-solid fa-envelope" style="font-size: 50px; color: #ff014f;"></i>
                        </div>
                        <h4 class="service-title" style="font-weight: 700;">Email Address</h4>
                        <p class="service-para" style="font-size: 14px;">
                            <a href="mailto:<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></a>
                        </p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center" style="height: 100%;">
                        <div class="service-card-icon mb-4">
                            <i class="fa-solid fa-phone" style="font-size: 50px; color: #ff014f;"></i>
                        </div>
                        <h4 class="service-title" style="font-weight: 700;">Phone Numbers</h4>
                        <p class="service-para" style="font-size: 14px;">
                            <?php foreach($contact['phones'] as $phone): ?>
                                <a href="tel:<?php echo str_replace([' ', '-'], '', $phone); ?>"><?php echo $phone; ?></a><br>
                            <?php endforeach; ?>
                        </p>
                    </div>
                </div>

                <!-- Office -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center" style="height: 100%;">
                        <div class="service-card-icon mb-4">
                            <i class="fa-solid fa-building-columns" style="font-size: 50px; color: #ff014f;"></i>
                        </div>
                        <h4 class="service-title" style="font-weight: 700;">Office / R&D Firm</h4>
                        <p class="service-para" style="font-size: 14px;">
                            <?php foreach($contact['offices'] as $off): ?>
                                <strong><?php echo $off['name']; ?>:</strong> <?php echo $off['location']; ?><br>
                            <?php endforeach; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Mailing Address & Socials -->
            <div class="row mt--80">
                <div class="col-lg-12">
                    <div class="text-center tmp-scroll-trigger tmp-fade-in">
                        <h3 class="title mb--30" style="font-weight: 700;">Mailing Address</h3>
                        <p class="description mb--40" style="font-size: 15px; color: #666; max-width: 800px; margin: 0 auto 40px;">
                            <?php echo $contact['mailing_address']; ?>
                        </p>
                        <div class="social-wrapper mt--30">
                            <div class="social-link justify-content-center gap-3">
                                <?php foreach($contact['social'] as $soc): ?>
                                    <a href="<?php echo $soc['link']; ?>" class="bg-light shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; transition: 0.3s;" title="<?php echo $soc['platform']; ?>">
                                        <i class="<?php echo $soc['icon']; ?>" style="font-size: 18px; color: #333;"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Form Section -->
    <style>
        .tmp-contact-form input, 
        .tmp-contact-form textarea {
            padding: 12px 20px !important;
            font-size: 14px !important;
            height: auto !important;
        }
        .tmp-contact-form textarea {
            min-height: 120px !important;
        }
        .tmp-contact-form .btn-text {
            font-size: 14px !important;
        }
    </style>
    <?php if (is_section_active('contact')): ?>
    <section class="contact-form-area tmp-section-gapBottom" id="form">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="contact-form-wrapper bg-blur-style-one p--40 radius-10 tmp-scroll-trigger tmp-fade-in">
                        <h3 class="title mb--30 text-center">Send a Message</h3>
                        <form class="tmp-contact-form" id="contact-form" action="mailer" method="POST">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <input type="text" name="name" id="contact-name" placeholder="Your Name" required>
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" id="contact-email" placeholder="Email Address" required>
                                </div>
                                <div class="col-lg-6">
                                    <input type="tel" name="phone" id="contact-phone" placeholder="Phone Number (Optional)">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="subject" id="subject" placeholder="Subject" required>
                                </div>
                                <div class="col-lg-12">
                                    <textarea name="message" id="contact-message" rows="5" placeholder="Your Message" required></textarea>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button class="tmp-btn hover-icon-reverse radius-round w-100" type="submit">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">Send Message</span>
                                            <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                        </span>
                                    </button>
                                </div>
                                <div id="form-messages" class="mt-3 text-center"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include('footer.php'); ?>
