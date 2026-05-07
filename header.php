<?php
global $skip_header;
if (!empty($skip_header)) {
    if (isset($extraStyles)) echo $extraStyles;
    return;
}
require_once('functions.php');
$currentPage = basename($_SERVER['PHP_SELF']);
if (!is_page_active($currentPage)) {
    $first_active = get_first_active_page();
    $target_clean = str_replace('.php', '', $first_active);
    $current_clean = str_replace('.php', '', $currentPage);
    if ($target_clean !== $current_clean && $first_active !== './') {
        header('Location: ' . $first_active);
        exit;
    }
}
$cust = $p_content['customization'];
$contact = $p_content['contact'];
$hero = $p_content['hero'];
$about = $p_content['about'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $metaDescription ?? $cust['meta_description']; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.svg">
    <title><?php echo $pageTitle ?? $cust['site_title']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap min css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.css">
    <link rel="stylesheet" href="assets/css/plugins/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/odometer.css">
    <link rel="stylesheet" href="assets/css/vendor/animate.min.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
    <style>
        :root {
            --color-primary: <?php echo $cust['primary_color']; ?> !important;
            --font-primary: <?php echo $cust['font_family'] ?? "'Plus Jakarta Sans', sans-serif"; ?> !important;
        }
        
        body {
            background-color: <?php echo $cust['background_color'] ?? '#ffffff'; ?> !important;
        }

        .swal2-toast {
            font-size: 1.6rem !important;
        }

        .tmp-banner-one-area {
            background-image: none !important;
            background-color: transparent !important;
        }

        body, p, h1, h2, h3, h4, h5, h6, a, span, div, li, ul, button, input, textarea {
            font-family: var(--font-primary) !important;
        }
        /* Global Header Spacing */
        .tmp-header-area-start {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Large Desktop Adjustments (XXL) */
        @media only screen and (min-width: 1400px) {
            .container { max-width: 1320px; }
        }

        /* Desktop & Laptop (LG to XL) */
        @media only screen and (max-width: 1199px) {
            .tmp-banner-one-area h2.title {
                font-size: 38px !important;
            }
        }

        /* Tablet & Small Laptop (MD to LG) */
        @media only screen and (max-width: 991px) {
            .tmp-header-area-start {
                padding-top: 15px !important;
                padding-bottom: 10px !important;
            }
            
            .logo h4.title {
                font-size: 18px !important;
                letter-spacing: 0 !important;
            }
            .logo i {
                font-size: 24px !important;
            }
            
            .tmp-banner-one-area h2.title {
                font-size: 32px !important;
            }
            .banner-big-text-1 {
                font-size: 60px !important;
            }
            
            .tmp-section-gapTop { padding-top: 80px !important; }
            .tmp-section-gap { padding-top: 60px !important; padding-bottom: 60px !important; }
            .tmp-section-gapBottom { padding-bottom: 60px !important; }
        }
        
        /* Mobile Landscape & Large Phones (SM to MD) */
        @media only screen and (max-width: 767px) {
            .tmp-banner-one-area h2.title {
                font-size: 28px !important;
            }
            .banner-big-text-1 {
                font-size: 40px !important;
            }
            .inner p.disc {
                font-size: 14px !important;
            }
        }
        
        /* Small Mobile Screens (XS) */
        @media only screen and (max-width: 480px) {
            .logo h4.title {
                font-size: 14px !important;
            }
            .logo i {
                font-size: 20px !important;
            }
            .logo {
                padding-left: 10px !important;
            }
            .tmp-header-right {
                padding-right: 10px !important;
            }
            
            h2.title, .section-head h2 {
                font-size: 26px !important;
            }
            .banner-big-text-1 {
                font-size: 30px !important;
                top: 15% !important;
            }
        }

        /* Active Page State with Box */
        .tmp-mainmenu li.active > a {
            background-color: rgba(255, 1, 79, 0.08) !important;
            color: var(--color-primary) !important;
            padding: 10px 18px !important;
            border-radius: 8px;
            font-weight: 700 !important;
        }

        /* Red Hover Effect for Navigation */
        .tmp-mainmenu li a:hover {
            color: var(--color-primary) !important;
        }

        /* Hero Animation Colors (Matches Requested Second Image) */
        .cd-words-wrapper b {
            color: var(--color-primary) !important;
            font-weight: 800 !important;
        }

        /* Headline Animation (Clip Effect) */
        .cd-headline.clip .cd-words-wrapper {
            overflow: hidden;
            vertical-align: top;
            padding-right: 25px !important;
        }
        .cd-headline.clip .cd-words-wrapper::after {
            content: '';
            position: absolute;
            top: 10%;
            right: 0;
            width: 3px;
            height: 80%;
            background-color: var(--color-primary);
        }
        .cd-words-wrapper b {
            display: none;
        }
        .cd-words-wrapper b.is-visible {
            display: inline-block;
        }
        
        /* Vibrant Red Button Styling */
        .tmp-btn.radius-round {
            background: var(--color-primary) !important;
            color: white !important;
            padding: 18px 40px !important;
            border-radius: 500px !important;
            border: none !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            text-transform: capitalize;
            box-shadow: 0 10px 25px rgba(255, 1, 79, 0.3);
            transition: all 0.3s ease;
        }
        .tmp-btn.radius-round:hover {
            background: #d90043 !important;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 1, 79, 0.4);
            color: white !important;
        }

        /* Robust CSS Dropdown Arrows (Matches Second Image) */
        .dropdown-toggle-btn {
            position: relative;
            padding-right: 30px !important;
        }

        .dropdown-toggle-btn::after {
            content: "";
            position: absolute;
            right: 15px;
            top: 50%;
            width: 8px;
            height: 8px;
            border-right: 2px solid #333;
            border-bottom: 2px solid #333;
            transform: translateY(-70%) rotate(45deg);
            transition: all 0.3s ease;
        }

        .dropdown-toggle-btn.open {
            color: var(--color-primary) !important;
        }

        .dropdown-toggle-btn.open::after {
            border-color: var(--color-primary);
            transform: translateY(-30%) rotate(-135deg);
        }

        /* Header & Section Spacing Fixes */
        .tmp-header-area-start {
            background-color: var(--color-white) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        @media only screen and (max-width: 991px) {
            .tmp-section-gapTop { padding-top: 100px !important; }
            .about-me-area.tmp-section-gap { padding-top: 50px !important; }
            .banner-one-main-wrapper .row { min-height: auto !important; padding-bottom: 30px; }
            
            .tmp-section-gap { padding-top: 60px !important; padding-bottom: 40px !important; }
            .tmp-section-gapBottom { padding-bottom: 40px !important; }
            
            h2.title, .section-head h2 { font-size: 30px !important; }
            h3.title { font-size: 24px !important; }
        }

        @media only screen and (max-width: 767px) {
            .about-me-image img {
                max-width: 280px !important;
                margin: 0 auto 30px !important;
                display: block;
            }
            
            .tmp-popup-mobile-menu .header-top .logo h4.title {
                font-size: 16px !important;
            }
            .tmp-popup-mobile-menu .header-top .logo i {
                font-size: 22px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .about-me-image img {
                max-width: 220px !important;
            }
            
            .tmp-popup-mobile-menu .header-top .logo h4.title {
                display: block !important;
                font-size: 14px !important;
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .tmp-popup-mobile-menu .header-top .logo i {
                font-size: 18px !important;
            }
            
            .tmp-section-gapTop { padding-top: 80px !important; }
            .about-me-area.tmp-section-gap { padding-top: 20px !important; margin-top: 0 !important; }
            .banner-one-main-wrapper .row { min-height: auto !important; padding-bottom: 10px; }
            .tmp-section-gap { padding-top: 25px !important; padding-bottom: 25px !important; }
            .tmp-section-gapBottom { padding-bottom: 25px !important; }

            /* Home Hero Image Mobile Size */
            .banner-right-content img {
                max-width: 300px !important;
                margin-top: 30px;
            }
        }

        /* Publication Card Fixes */
        .education-experience-card {
            overflow: hidden;
            word-wrap: break-word;
            padding: 25px !important;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: var(--transition);
        }

        .education-experience-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--color-primary);
        }

        .row.g-5 {
            --bs-gutter-x: 2rem;
            --bs-gutter-y: 2rem;
        }

        @media only screen and (max-width: 767px) {
            .row.g-5 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1.5rem;
            }
        @media only screen and (max-width: 480px) {
            .personal-details-table td {
                display: block !important;
                width: 100% !important;
                padding: 5px 0 !important;
                border-bottom: none !important;
            }
            .personal-details-table td:first-child {
                padding-top: 15px !important;
                font-size: 14px !important;
            }
            .personal-details-table td:last-child {
                padding-bottom: 10px !important;
                border-bottom: 1px solid rgba(0,0,0,0.05) !important;
                color: var(--color-body);
            }
        }

        .text-align-left {
            text-align: left !important;
        }
        .text-align-left * {
            text-align: left !important;
        }
    </style>
</head>

<body class="tmp-white-version">

    <!-- tpm-header-area start -->
    <header class="tmp-header-area-start header-one header--sticky header--transparent">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-content">
                        <div class="logo" style="display: flex; align-items: center; padding-left: 20px;">
                            <a href="./" style="display: flex; align-items: center; gap: 12px; white-space: nowrap;">
                                <i class="fa-solid fa-graduation-cap" style="font-size: 28px; color: var(--color-primary);"></i>
                                <h4 class="title" style="margin: 0; font-weight: 800; font-size: 22px; color: var(--color-heading); line-height: 1;"><?php echo $cust['logo_text']; ?></h4>
                            </a>
                        </div>
                        <nav class="tmp-mainmenu-nav d-none d-lg-block">
                            <ul class="tmp-mainmenu">
                                <?php if (is_page_active('index.php')): ?>
                                <li class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">
                                    <a href="./">Home</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('about.php')): ?>
                                <li class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">
                                    <a href="about">About</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('research.php')): ?>
                                <li class="<?php echo ($currentPage == 'research.php') ? 'active' : ''; ?>">
                                    <a href="research">Research</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('publications.php')): ?>
                                <li class="<?php echo ($currentPage == 'publications.php') ? 'active' : ''; ?>">
                                    <a href="publications">Publications</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('experience.php')): ?>
                                <li class="<?php echo ($currentPage == 'experience.php') ? 'active' : ''; ?>">
                                    <a href="experience">Experience</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('teaching.php')): ?>
                                 <li class="<?php echo ($currentPage == 'teaching.php') ? 'active' : ''; ?>">
                                    <a href="teaching">Teaching</a>
                                </li>
                                <?php endif; ?>
                                <?php if (is_page_active('talks.php')): ?>
                                 <li class="<?php echo ($currentPage == 'talks.php') ? 'active' : ''; ?>"><a href="talks">Talks</a></li>
                                <?php endif; ?>
                                <?php if (is_page_active('skills.php')): ?>
                                <li class="<?php echo ($currentPage == 'skills.php') ? 'active' : ''; ?>"><a href="skills">Skills</a></li>
                                <?php endif; ?>
                                <?php if (is_page_active('contact.php')): ?>
                                <li class="<?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>">
                                    <a href="contact">Contact</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <div class="tmp-header-right" style="padding-right: 20px;">

                            <div class="actions-area">
                                <div class="tmp-side-collups-area d-none d-lg-block">
                                    <button class="tmp-menu-bars tmp_button_active"><i class="fa-solid fa-bars-staggered"></i></button>
                                </div>
                                <div class="tmp-side-collups-area d-block d-lg-none">
                                    <button class="tmp-menu-bars humberger_menu_active"><i class="fa-solid fa-bars-staggered"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- tpm-header-area end -->

    <!-- Sidebar & Mobile Menu -->
    <div class="d-none d-lg-block">
        <div class="tmp-sidebar-area tmp_side_bar">
            <div class="inner">
                <div class="top-area">
                    <a href="./" class="logo">
                        <h4 class="title" style="margin: 0; font-weight: 800; color: var(--color-primary); letter-spacing: 1px;"><?php echo $cust['logo_text']; ?></h4>
                    </a>
                    <div class="close-icon-area">
                        <button class="tmp-round-action-btn close_side_menu_active">
                            <i class=" fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <div class="content-wrapper">
                    <div class="image-area-feature">
                        <a href="./">
                            <img src="<?php echo $cust['sidebar_image'] ?? $hero['image']; ?>" alt="<?php echo $cust['logo_text'] ?? 'Profile'; ?>">
                        </a>
                    </div>
                    <h5 class="title mt--30"><?php echo $cust['sidebar_title'] ?? implode(', ', array_slice($hero['designations'], 0, 3)); ?></h5>
                    <p class="disc"><?php echo $cust['sidebar_description'] ?? strip_tags($hero['description']); ?></p>
                    <div class="short-contact-area">
                        <div class="single-contact">
                            <i class="fa-solid fa-phone"></i>
                            <div class="information tmp-link-animation">
                                <span>Call Now</span>
                                <a href="tel:<?php echo str_replace(' ', '', $cust['sidebar_phone'] ?? $contact['phones'][0] ?? ''); ?>" class="number"><?php echo $cust['sidebar_phone'] ?? $contact['phones'][0] ?? ''; ?></a>
                            </div>
                        </div>
                        <div class="single-contact">
                            <i class="fa-solid fa-envelope"></i>
                            <div class="information tmp-link-animation">
                                <span>Mail Us</span>
                                <a href="mailto:<?php echo $cust['sidebar_email'] ?? $contact['email']; ?>" class="number"><?php echo $cust['sidebar_email'] ?? $contact['email']; ?></a>
                            </div>
                        </div>
                        <div class="single-contact">
                            <i class="fa-solid fa-gear"></i>
                            <div class="information tmp-link-animation">
                                <span>Expertise</span>
                                <a href="skills.php" class="number">Technical Skills</a>
                            </div>
                        </div>
                        <div class="single-contact">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            <div class="information tmp-link-animation">
                                <span>Address</span>
                                <span class="number"><?php echo $cust['sidebar_address'] ?? $contact['mailing_address'] ?? ''; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="social-wrapper mt--20">
                        <span class="subtitle">find with me</span>
                        <div class="social-link">
                            <?php foreach($contact['social'] as $soc): ?>
                                <a href="<?php echo $soc['link']; ?>"><i class="<?php echo $soc['icon']; ?>"></i></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="overlay_close_side_menu close_side_menu_active" href="javascript:void(0);"></a>
    </div>

    <div class="d-block d-lg-none">
        <div class="tmp-popup-mobile-menu">
            <div class="inner">
                <div class="header-top">
                    <div class="logo">
                        <a href="./" style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-graduation-cap" style="font-size: 28px; color: var(--color-primary);"></i>
                            <h4 class="title" style="margin: 0; font-weight: 800; font-size: 20px; color: var(--color-heading);"><?php echo $cust['logo_text']; ?></h4>
                        </a>
                    </div>
                    <div class="close-menu">
                        <button class="close-button tmp-round-action-btn">
                            <i class=" fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <ul class="tmp-mainmenu">
                    <?php if (is_page_active('index.php')): ?>
                    <li><a href="./">Home</a></li>
                    <?php endif; ?>
                    <?php if (is_page_active('about.php')): ?>
                    <li><a href="./#profile">About</a></li>
                    <?php endif; ?>
                    <?php if (is_page_active('research.php')): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle-btn">Research</a>
                        <ul class="submenu">
                            <li><a href="./#intro">Introduction</a></li>
                            <li><a href="./#areas">Research Areas</a></li>
                            <li><a href="./#thesis">M.Phil & Ph.D</a></li>
                            <li><a href="./#patents">Patents</a></li>
                            <li><a href="./#shreetech">Shreetech / ISRD</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (is_page_active('publications.php')): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle-btn">Publications</a>
                        <ul class="submenu">
                            <li><a href="./#journals">Journals</a></li>
                            <li><a href="./#intl-conferences">Intl Conferences</a></li>
                            <li><a href="./#nat-conferences">National Conferences</a></li>
                            <li><a href="./#patents">Patents</a></li>
                            <li><a href="./#books">Books</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (is_page_active('experience.php')): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle-btn">Experience</a>
                        <ul class="submenu">
                            <li><a href="./#journey">Professional Journey</a></li>
                            <li><a href="./#examinership">Examinership</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (is_page_active('teaching.php')): ?>
                    <li class="has-dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle-btn">Teaching</a>
                        <ul class="submenu">
                            <li><a href="./#subjects">Subjects</a></li>
                            <li><a href="./#courses">Courses</a></li>
                            <li><a href="./#mentoring">Mentoring</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (is_page_active('talks.php')): ?>
                    <li><a href="./#resource-person">Talks</a></li>
                    <?php endif; ?>
                    <?php if (is_page_active('skills.php')): ?>
                    <li><a href="./#skills">Skills</a></li>
                    <?php endif; ?>
                    <?php if (is_page_active('contact.php')): ?>
                    <li><a href="./#contact">Contact</a></li>
                    <?php endif; ?>
                </ul>
                <div class="social-wrapper mt--40">
                    <span class="subtitle">find with me</span>
                    <div class="social-link">
                        <?php foreach($contact['social'] as $soc): ?>
                            <a href="<?php echo $soc['link']; ?>"><i class="<?php echo $soc['icon']; ?>"></i></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
