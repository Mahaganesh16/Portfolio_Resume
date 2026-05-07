<?php
require_once('functions.php');
$pubs = $p_content['publications'];
$cust = $p_content['customization'];

$pageTitle = "Publications - " . $cust['site_title'];
$metaDescription = $cust['meta_description'];
$extraStyles = '
<style>
    .edu-sub-title {
        font-size: 11px !important;
        color: #888 !important;
        font-weight: 500 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .edu-title {
        font-size: 17px !important;
        color: var(--color-secondary) !important;
        line-height: 1.4 !important;
        margin: 10px 0 !important;
        font-weight: 700 !important;
    }
    .edu-para {
        font-size: 13px !important;
        color: #666 !important;
        font-weight: 400 !important;
        line-height: 1.6 !important;
    }
    .education-experience-card {
        padding: 20px !important;
    }
    .counter-card .counter-title {
        font-size: 30px !important;
    }
    .counter-card .counter-para {
        font-size: 12px !important;
    }

    /* Media Queries for Publications */
    @media only screen and (max-width: 768px) {
        .edu-title {
            font-size: 20px !important;
        }
        .edu-para {
            font-size: 16px !important;
        }
        .section-head h3.title {
            font-size: 22px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .edu-title {
            font-size: 18px !important;
            margin: 10px 0 !important;
        }
        .edu-para {
            font-size: 14px !important;
        }
        .section-head h3.title {
            font-size: 18px !important;
        }
    }
</style>';
include('header.php');
?>

    <!-- Section Label & Heading -->
    <section class="publications-header-area tmp-section-gapTop">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head text-center">
                        <h2 class="title split-collab tmp-scroll-trigger tmp-fade-in">Publications</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- International Journal Papers -->
    <?php if (is_section_active('publications.journals')): ?>
    <section class="journal-papers-area tmp-section-gap" id="journals">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head mb--50 text-center">
                        <h3 class="title">International Journal Papers (Total: <?php echo count($pubs['journals']); ?>)</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($pubs['journals'] as $j): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $j['id']; ?> <?php echo $j['source']; ?></h4>
                            <h2 class="edu-title"><?php echo $j['title']; ?></h2>
                            <p class="edu-para"><?php echo nl2br($j['authors']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- International Conference Papers -->
    <?php if (is_section_active('publications.conferences')): ?>
    <section class="conference-papers-area tmp-section-gapBottom" id="intl-conferences">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head mb--50 text-center">
                        <h3 class="title">International Conference Papers</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($pubs['conferences'] as $c): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $c['id']; ?> <?php echo $c['source']; ?></h4>
                            <h2 class="edu-title"><?php echo $c['title']; ?></h2>
                            <p class="edu-para"><?php echo nl2br($c['details']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- National Conference Papers -->
    <?php if(isset($pubs['national_conferences']) && is_section_active('publications.national_conferences')): ?>
    <section class="conference-papers-area tmp-section-gapBottom" id="national-conferences">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head mb--50 text-center">
                        <h3 class="title">National Conference Papers</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($pubs['national_conferences'] as $nc): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $nc['id']; ?> <?php echo $nc['source']; ?></h4>
                            <h2 class="edu-title"><?php echo $nc['title']; ?></h2>
                            <p class="edu-para"><?php echo nl2br($nc['details']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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

    <!-- Books Published -->
    <?php if (is_section_active('publications.books')): ?>
    <section class="books-area tmp-section-gapBottom" id="books">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-head mb--50 text-center">
                        <h3 class="title">Books Published</h3>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <?php foreach($pubs['books'] as $b): ?>
                    <div class="col-lg-6">
                        <div class="education-experience-card tmponhover tmp-scroll-trigger tmp-fade-in">
                            <h4 class="edu-sub-title"><?php echo $b['id']; ?> <?php echo $b['source']; ?></h4>
                            <h2 class="edu-title"><?php echo $b['title']; ?></h2>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include('footer.php'); ?>
