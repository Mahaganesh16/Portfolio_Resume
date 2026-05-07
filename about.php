<?php
require_once('functions.php');
$about = $p_content['about'];
$cust = $p_content['customization'];

$pageTitle = "About Me - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
$extraStyles = '
    <style>
        .personal-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .personal-details-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .personal-details-table td:first-child {
            font-weight: 700;
            width: 30%;
            color: var(--color-primary);
        }
        .timeline-area {
            position: relative;
            padding-left: 30px;
            border-left: 2px solid var(--color-primary);
            margin-left: 20px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 40px;
        }
        .timeline-item::before {
            content: "";
            position: absolute;
            left: -41px;
            top: 5px;
            width: 20px;
            height: 20px;
            background: var(--color-white);
            border: 4px solid var(--color-primary);
            border-radius: 50%;
        }
        .qualification-year {
            font-weight: 700;
            color: var(--color-primary);
            font-size: 1.2rem;
            margin-bottom: 5px;
            display: block;
        }
        .qualification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .qualification-desc {
            font-size: 1.35rem !important;
            color: #666;
            font-weight: 500;
        }
    </style>';
include('header.php');
?>

    <!-- About Me Section Start -->
    <?php if (is_section_active('about')): ?>
    <section class="about-me-area tmp-section-gap" id="profile">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="about-me-image tmp-scroll-trigger tmp-zoom-in">
                        <img src="<?php echo $about['image']; ?>" alt="Profile" class="w-100 radius-round">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="about-me-content ml--30 ml_md--0 ml_sm--0">
                        <div class="section-head text-align-left">
                            <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in"><?php echo $about['title'] ?? 'Who I Am'; ?></h2>
                        </div>
                        <?php foreach($about['bio'] as $paragraph): ?>
                            <p class="description tmp-scroll-trigger tmp-fade-in">
                                <?php echo nl2br($paragraph); ?>
                            </p>
                        <?php endforeach; ?>
                        
                        <div class="button-group mt--30 tmp-scroll-trigger tmp-fade-in">
                            <?php $validation = validate_profile($p_content); if($validation === true): ?>
                                <a class="tmp-btn hover-icon-reverse radius-round" href="cv.php" target="_blank">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Download Full CV</span>
                                        <span class="btn-icon"><i class="fa-solid fa-download"></i></span>
                                        <span class="btn-icon"><i class="fa-solid fa-download"></i></span>
                                    </span>
                                </a>
                            <?php else: ?>
                                <a class="tmp-btn hover-icon-reverse radius-round" href="javascript:void(0)" onclick="Swal.fire({toast: true, position: 'bottom', icon: 'error', title: '<?php echo addslashes($validation); ?>', showConfirmButton: false, timer: 4000, timerProgressBar: true})">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Download Full CV</span>
                                        <span class="btn-icon"><i class="fa-solid fa-download"></i></span>
                                        <span class="btn-icon"><i class="fa-solid fa-download"></i></span>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- About Me Section End -->

    <!-- Personal Details Section Start -->
    <?php if (is_section_active('about.details')): ?>
    <section class="personal-details-area tmp-section-gapBottom" id="details">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Personal Details</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="personal-details-wrap bg-blur-style-one p--40 radius-10 tmp-scroll-trigger tmp-fade-in">
                        <table class="personal-details-table">
                            <?php foreach($about['details'] as $detail): ?>
                                <tr>
                                    <td><?php echo $detail['key']; ?></td>
                                    <td><?php echo $detail['value']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Personal Details Section End -->

    <!-- Academic Qualifications Section Start -->
    <?php if (is_section_active('about.education')): ?>
    <section class="academic-qualifications-area tmp-section-gapBottom" id="education">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Academic Qualifications</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timeline-area mt--50 tmp-scroll-trigger tmp-fade-in">
                        <?php foreach($about['education'] as $edu): ?>
                            <div class="timeline-item">
                                <span class="qualification-year"><?php echo $edu['year']; ?></span>
                                <h4 class="qualification-title"><?php echo $edu['title']; ?></h4>
                                <p class="qualification-desc"><?php echo $edu['desc']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Academic Qualifications Section End -->

<?php include('footer.php'); ?>
