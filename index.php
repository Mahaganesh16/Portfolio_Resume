<?php
require_once('functions.php');
$hero = $p_content['hero'];
$cust = $p_content['customization'];

$pageTitle = $cust['site_title'];
$metaDescription = $cust['meta_description'];
include('header.php');
?>

    <!-- tmp banner area start -->
    <?php if (is_section_active('hero')): ?>
    <div class="tmp-banner-one-area">
        <div class="container">
            <div class="banner-one-main-wrapper">
                <div class="row align-items-center justify-content-center" style="min-height: 80vh; gap: 20px;">
                    <div class="col-lg-5 order-2 order-lg-2 mt-5 mt-lg-0">
                        <div class="banner-right-content text-center position-relative">
                            <img class="img-fluid" src="<?php echo $hero['image']; ?>" alt="<?php echo $hero['name']; ?>" style="border-radius: 50% 50% 0 0; border: 5px solid rgba(255,255,255,0.1); box-shadow: 0 15px 40px rgba(0,0,0,0.15); object-fit: cover; width: 100%; max-width: 420px; height: auto; aspect-ratio: 4/5;">
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-1">
                        <div class="inner">
                            <span class="sub-title"><?php echo $hero['sub_title']; ?></span>
                            <h4 class="title mt--5" style="font-size: clamp(28px, 4vw, 38px); line-height: 1.2;">I'm
                               <?php echo $hero['name']; ?> <br>
                                <span class="header-caption">
                                    <span class="cd-headline clip is-full-width">
                                        <span class="cd-words-wrapper">
                                            <?php foreach($hero['designations'] as $index => $designation): ?>
                                                <b class="<?php echo $index === 0 ? 'is-visible' : 'is-hidden'; ?>">  <?php echo $designation; ?></b>
                                            <?php endforeach; ?>
                                        </span>
                                </span>
                                </span>
                            </h4>
                            <p class="disc"><?php echo ($hero['degrees'] ?? '') . '<br>' . nl2br(trim($hero['description'])); ?></p>
                            <div class="button-area-banner-one">
                                <?php $validation = validate_profile($p_content); if($validation === true): ?>
                                    <a class="tmp-btn hover-icon-reverse radius-round" href="cv.php" target="_blank" style="background: var(--color-primary); border: 2px solid var(--color-primary); color: #fff;">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text" style="color: #fff;">Download CV</span>
                                            <span class="btn-icon"><i class="fa-solid fa-download" style="color: #fff;"></i></span>
                                            <span class="btn-icon"><i class="fa-solid fa-download" style="color: #fff;"></i></span>
                                        </span>
                                    </a>
                                <?php else: ?>
                                    <a class="tmp-btn hover-icon-reverse radius-round" href="javascript:void(0)" onclick="Swal.fire({toast: true, position: 'bottom', icon: 'error', title: '<?php echo addslashes($validation); ?>', showConfirmButton: false, timer: 4000, timerProgressBar: true})" style="background: var(--color-primary); border: 2px solid var(--color-primary); color: #fff;">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text" style="color: #fff;">Download CV</span>
                                            <span class="btn-icon"><i class="fa-solid fa-download" style="color: #fff;"></i></span>
                                            <span class="btn-icon"><i class="fa-solid fa-download" style="color: #fff;"></i></span>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- tmp banner area end -->

<?php include('footer.php'); ?>
