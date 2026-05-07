<?php
require_once('functions.php');
$skills = $p_content['skills'];
$cust = $p_content['customization'];

$pageTitle = "Skills & Expertise - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- Skills Section Start -->
    <?php if (is_section_active('skills')): ?>
    <section class="skills-area tmp-section-gapTop" id="skills">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Technical Skills</h2>
                    </div>
                </div>
            </div>
            
            <div class="row g-5 mt--30">
                <?php foreach($skills as $skill): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card-v1 tmp-scroll-trigger tmp-fade-in tmponhover text-center" style="height: 100%;">
                            <div class="service-card-icon mb-4" style="font-size: 50px; color: #ff014f;">
                                <i class="<?php echo $skill['icon'] ?? 'fa-solid fa-gear'; ?>"></i>
                            </div>
                            <h3 class="service-title" style="font-weight: 700; margin-bottom: 20px;"><?php echo $skill['category']; ?></h3>
                            <p class="service-para" style="font-size: 14px; line-height: 2; color: #666;">
                                <?php echo nl2br($skill['items']); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Skills Section End -->

<?php include('footer.php'); ?>
