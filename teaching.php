<?php
require_once('functions.php');
$teaching = $p_content['teaching'];
$cust = $p_content['customization'];

$pageTitle = "Teaching & Certifications - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- 1. Subjects Taught -->
    <?php if (is_section_active('teaching.subjects')): ?>
    <section class="subjects-taught-area tmp-section-gapTop" id="subjects">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Subjects Taught</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['subjects'] as $sub): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover">
                            <h4 class="service-title"><?php echo $sub['title']; ?></h4>
                            <p class="service-para"><?php echo nl2br($sub['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 2. Courses Handled -->
    <?php if(isset($teaching['courses']) && is_section_active('teaching.courses')): ?>
    <section class="courses-handled-area tmp-section-gap" id="courses">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title">Courses Handled</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['courses'] as $course): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title" style="font-weight: 700; color: #000;"><?php echo $course['title']; ?></h4>
                            <p class="edu-para" style="font-size: 16px !important; margin-top: 10px;"><?php echo nl2br($course['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 3. Academic Mentoring -->
    <?php if(isset($teaching['mentoring']) && is_section_active('teaching.mentoring')): ?>
    <section class="academic-mentoring-area tmp-section-gapBottom" id="mentoring">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title">Academic Mentoring</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['mentoring'] as $ment): ?>
                    <div class="col-lg-12">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in" style="padding: 25px 40px !important;">
                            <p class="edu-para" style="font-size: 18px !important; color: #444;">
                                <i class="fa-solid fa-caret-right text-primary me-3"></i> <?php echo $ment; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. Professional Certifications -->
    <?php if (is_section_active('teaching.certifications')): ?>
    <section class="certifications-area tmp-section-gapBottom" id="certifications">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h3 class="title">Professional Certifications</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['certifications'] as $cert): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center">
                            <div class="service-card-icon">
                                <i class="<?php echo $cert['icon']; ?>"></i>
                            </div>
                            <h4 class="service-title"><?php echo $cert['title']; ?></h4>
                            <p class="service-para"><?php echo nl2br($cert['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 5. Seminars & Workshops Organized -->
    <?php if(isset($teaching['workshops_organized']) && is_section_active('teaching.workshops_organized')): ?>
    <section class="workshops-organized-area tmp-section-gapBottom" id="organized">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title">Seminars & Workshops Organized</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['workshops_organized'] as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in" style="height: 100%;">
                            <h4 class="edu-sub-title"><?php echo $item['date']; ?></h4>
                            <h2 class="edu-title" style="font-size: 20px;"><?php echo $item['title']; ?></h2>
                            <p class="edu-para"><?php echo $item['location']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 6. Seminars & Workshops Attended -->
    <?php if(isset($teaching['workshops_attended']) && is_section_active('teaching.workshops_attended')): ?>
    <section class="workshops-attended-area tmp-section-gapBottom" id="attended">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title">Seminars & Workshops Attended</h2>
                    </div>
                </div>
            </div>
            <div class="row g-5 mt--20">
                <?php foreach($teaching['workshops_attended'] as $item): ?>
                    <div class="col-lg-12">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in" style="padding: 25px 40px !important;">
                            <p class="edu-para" style="font-size: 16px !important; color: #444;">
                                <i class="fa-solid fa-caret-right text-primary me-3"></i> <?php echo $item; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include('footer.php'); ?>
