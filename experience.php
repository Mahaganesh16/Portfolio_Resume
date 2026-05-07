<?php
require_once('functions.php');
$exp = $p_content['experience'];
$cust = $p_content['customization'];

$pageTitle = "Professional Experience - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- Professional Journey Section -->
    <?php if (is_section_active('experience.journey')): ?>
    <section class="professional-journey-area tmp-section-gapTop" id="journey">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Professional Journey</h2>
                    </div>
                </div>
            </div>
            
            <div class="row g-5 mt--30">
                <?php foreach($exp['journey'] as $j): ?>
                    <div class="col-lg-12">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <div class="row">
                                <div class="col-lg-4">
                                    <h4 class="edu-sub-title"><?php echo $j['period']; ?></h4>
                                    <h3 class="edu-title"><?php echo $j['role']; ?></h3>
                                </div>
                                <div class="col-lg-8">
                                    <h4 class="edu-sub-title"><?php echo $j['location']; ?></h4>
                                    <p class="edu-para"><?php echo nl2br($j['desc']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Examinership Section -->
    <?php if (is_section_active('experience.examinership')): ?>
    <section class="examinership-area tmp-section-gap" id="examinership">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head mb--50 text-center">
                        <h3 class="title">Examiner at Universities & Colleges</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($exp['examinership'] as $ex): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center">
                            <div class="service-card-icon">
                                <i class="<?php echo $ex['icon']; ?>"></i>
                            </div>
                            <h4 class="service-title"><?php echo $ex['uni']; ?></h4>
                            <p class="service-para"><?php echo $ex['location']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include('footer.php'); ?>
