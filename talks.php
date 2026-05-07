<?php
$pageTitle = "Talks & Guest Lectures - Dr. V. Subburaj";
$metaDescription = "Dr. V. Subburaj as a Resource Person and Guest Lecturer. FDPs, workshops on WAMP, DotNet, and Web Designing across various engineering colleges.";
include('header.php');
?>

    <!-- Section Label & Heading -->
    <section class="talks-header-area tmp-section-gapTop">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Resource Person & Guest Lectures</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resource Person Section -->
    <?php if (is_section_active('talks')): ?>
    <section class="resource-person-area tmp-section-gap" id="resource-person">
        <div class="container">
            <div class="row g-5">
                <?php foreach($p_content['talks'] as $talk): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $talk['location']; ?></h4>
                            <h2 class="edu-title"><?php echo $talk['title']; ?></h2>
                            <p class="edu-para"><?php echo nl2br($talk['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include('footer.php'); ?>
