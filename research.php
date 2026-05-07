<?php
require_once('functions.php');
$research = $p_content['research'];
$cust = $p_content['customization'];

$pageTitle = "Research & Innovation - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- Research Intro Section Start -->
    <?php if (is_section_active('research')): ?>
    <section class="research-intro-area tmp-section-gapTop" id="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <div class="section-sub-title">
                            <span class="subtitle">RESEARCH</span>
                        </div>
                        <h2 class="title split-collab">Research & Innovation</h2>
                        <p class="description mt--30">
                            <?php echo nl2br($research['intro']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Research Intro Section End -->

    <!-- Research Areas Section Start -->
    <?php if (is_section_active('research.areas')): ?>
    <section class="research-areas-area tmp-section-gapBottom" id="areas">
        <div class="container">
            <div class="row g-5">
                <?php foreach($research['areas'] as $area): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover">
                            <div class="service-card-icon">
                                <i class="<?php echo $area['icon']; ?>"></i>
                            </div>
                            <h4 class="service-title"><?php echo $area['title']; ?></h4>
                            <p class="service-para"><?php echo nl2br($area['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Research Areas Section End -->

    <!-- Thesis Section Start -->
    <?php if (is_section_active('research.thesis')): ?>
    <section class="thesis-area tmp-section-gapBottom" id="thesis">
        <div class="container">
            <div class="row mb--40">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title">Scholars Guided</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($research['thesis'] as $th): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $th['type']; ?></h4>
                            <h2 class="edu-title"><?php echo $th['title']; ?></h2>
                            <p class="edu-para"><strong>University:</strong> <?php echo $th['uni']; ?></p>
                            <?php if(isset($th['year'])): ?><p class="edu-para"><strong>Year Awarded:</strong> <?php echo $th['year']; ?></p><?php endif; ?>
                            <?php if(isset($th['guide'])): ?><p class="edu-para"><strong>Guide:</strong> <?php echo $th['guide']; ?></p><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Thesis Section End -->

    <!-- Patent Stats Section Start -->
    <?php if (is_section_active('research.patents_summary')): ?>
    <section class="counter-area tmp-section-gapBottom" id="patents">
        <div class="container text-center">
            <div class="section-head mb--50">
                <h3 class="title">Patents Summary</h3>
            </div>
            <?php $ps = $p_content['research']['patents_summary']; ?>
            <div class="row g-5 justify-content-center">
                <!-- National -->
                <div class="col-lg-4 col-md-6">
                    <div class="counter-card tmponhover tmp-scroll-trigger tmp-fade-in">
                        <h3 class="counter-title">
                            <span class="odometer" data-count="<?php echo $ps['national_count']; ?>">00</span>
                        </h3>
                        <p class="counter-para"><?php echo $ps['national_label']; ?></p>
                    </div>
                </div>
                <!-- International -->
                <div class="col-lg-4 col-md-6">
                    <div class="counter-card tmponhover tmp-scroll-trigger tmp-fade-in">
                        <h3 class="counter-title">
                            <span class="odometer" data-count="<?php echo $ps['intl_count']; ?>">00</span>
                        </h3>
                        <p class="counter-para"><?php echo $ps['intl_label']; ?></p>
                    </div>
                </div>
                <!-- Total -->
                <div class="col-lg-4 col-md-6">
                    <div class="counter-card tmponhover tmp-scroll-trigger tmp-fade-in">
                        <h3 class="counter-title">
                            <span class="odometer" data-count="<?php echo $ps['total_count']; ?>">00</span>
                        </h3>
                        <p class="counter-para"><?php echo $ps['total_label']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row mt--40 tmp-scroll-trigger tmp-fade-in">
                <div class="col-lg-12">
                    <p class="description">
                        <strong>Utility & Design:</strong> <?php echo $ps['utility_design_count']; ?> <?php echo $ps['utility_design_label']; ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Patent Stats Section End -->

    <!-- Shreetech / ISRD Details Section Start -->
    <?php if (is_section_active('research.projects')): ?>
    <section class="shreetech-details-area tmp-section-gapBottom" id="shreetech">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="personal-details-wrap bg-blur-style-one p--40 radius-10 tmp-scroll-trigger tmp-fade-in">
                        <?php $s = $research['projects']; ?>
                        <h3 class="title mb--20"><?php echo $s['title']; ?></h3>
                        <p class="description">
                            <strong>Founder:</strong> <?php echo $s['founder']; ?><br>
                            <strong>CEO:</strong> <?php echo $s['ceo']; ?>
                        </p>
                        <p class="description mt--20">
                            <?php echo nl2br($s['desc']); ?>
                        </p>
                        <p class="description mt--20">
                            <strong>Direct clients from:</strong> <?php echo $s['clients']; ?><br>
                            <?php echo nl2br($s['projects']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Shreetech / ISRD Details Section End -->

<?php include('footer.php'); ?>
