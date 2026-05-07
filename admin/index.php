<?php
session_set_cookie_params(0, '/');
session_start();
if(!isset($_SESSION['admin_logged_in']) && (!isset($_COOKIE['admin_auth']) || $_COOKIE['admin_auth'] !== md5('logged_in_success'))) {
    header('Location: login');
    exit;
}

require_once('../functions.php');

$static_file = '../content.json';
$update_file = '../update_content.json';

if (file_exists($update_file)) {
    $content = json_decode(file_get_contents($update_file), true);
} else {
    if (!file_exists($static_file)) {
        die("Error: content.json not found in root directory.");
    }
    $content = json_decode(file_get_contents($static_file), true);
}
$p_content = $content;
$cust = $content['customization'];
$message = '';

// Handle Save Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_section_status'])) {
        $sec_key = $_POST['section_key'];
        if ($sec_key) {
            $current_status = $content['customization']['section_status'][$sec_key] ?? 'active';
            $new_status = ($current_status === 'disabled') ? 'active' : 'disabled';
            $content['customization']['section_status'][$sec_key] = $new_status;
            
            // Keep page status in sync for single-section pages if needed, but for index.php/home page it must always be active.
            // We only toggle the section status itself.
            
            $content['customization']['is_profile_setup'] = true;
            file_put_contents($update_file, json_encode($content, JSON_PRETTY_PRINT));
            $message = "Section status updated to: " . ucfirst($new_status);
            $cust = $content['customization'];
            $p_content = $content;
        }
    }

    $section = $_POST['section_key'] ?? '';
    
    // --- SAVE SINGLE SECTION ---
    if (isset($_POST['save_section'])) {
        // Special case: designations (array of strings)
        if ($section === 'hero' && isset($_POST['designations'])) {
            $content['hero']['designations'] = array_filter(array_map('trim', explode("\n", $_POST['designations'])));
            unset($_POST['data']['designations']);
        }
        // Special case: bio (array of strings)
        if ($section === 'about' && isset($_POST['bio'])) {
            $content['about']['bio'] = array_filter(array_map('trim', explode("\n", $_POST['bio'])));
            unset($_POST['data']['bio']);
        }

        // Fix array indices for contact section to prevent JSON objects instead of arrays when deleting items
        if ($section === 'contact') {
            if (isset($_POST['data']['phones'])) $_POST['data']['phones'] = array_values($_POST['data']['phones']);
            if (isset($_POST['data']['offices'])) $_POST['data']['offices'] = array_values($_POST['data']['offices']);
            if (isset($_POST['data']['social'])) $_POST['data']['social'] = array_values($_POST['data']['social']);
        }

        $path = explode('.', $section);
        foreach ($_POST['data'] as $key => $value) {
            if (count($path) == 2) {
                $content[$path[0]][$path[1]][$key] = $value;
            } else {
                $content[$section][$key] = $value;
            }
        }

        // Synchronize email fields across customization and contact sections
        if ($section === 'customization' && isset($_POST['data']['sidebar_email'])) {
            $new_email = $_POST['data']['sidebar_email'];
            $content['contact']['email'] = $new_email;
        }
        if ($section === 'contact' && isset($_POST['data']['email'])) {
            $new_email = $_POST['data']['email'];
            $content['customization']['sidebar_email'] = $new_email;
        }
        
        $content['customization']['is_profile_setup'] = true;
        file_put_contents($update_file, json_encode($content, JSON_PRETTY_PRINT));
        $message = "Section updated successfully!";
    }

    // --- ADD LIST ITEM ---
    if (isset($_POST['add_item'])) {
        $path = explode('.', $section);
        if (count($path) == 2) {
            $content[$path[0]][$path[1]][] = $_POST['new_data'];
        } else {
            $content[$section][] = $_POST['new_data'];
        }
        $content['customization']['is_profile_setup'] = true;
        file_put_contents($update_file, json_encode($content, JSON_PRETTY_PRINT));
        $message = "Item added successfully!";
    }

    // --- EDIT LIST ITEM ---
    if (isset($_POST['edit_item'])) {
        $index = $_POST['item_index'];
        $path = explode('.', $section);
        if (count($path) == 2) {
            $content[$path[0]][$path[1]][$index] = $_POST['data'];
        } else {
            $content[$section][$index] = $_POST['data'];
        }



        $content['customization']['is_profile_setup'] = true;
        file_put_contents($update_file, json_encode($content, JSON_PRETTY_PRINT));
        $message = "Item updated successfully!";
    }

    // --- DELETE LIST ITEM ---
    if (isset($_POST['delete_item'])) {
        $index = $_POST['item_index'];
        $path = explode('.', $section);
        if (count($path) == 2) {
            array_splice($content[$path[0]][$path[1]], $index, 1);
        } else {
            array_splice($content[$section], $index, 1);
        }
        $content['customization']['is_profile_setup'] = true;
        file_put_contents($update_file, json_encode($content, JSON_PRETTY_PRINT));
        $message = "Item deleted successfully!";
    }

    // --- RESET TO STATIC CONTENT ---
    if (isset($_POST['reset_default'])) {
        if (file_exists($update_file)) {
            unlink($update_file);
            $content = json_decode(file_get_contents($static_file), true);
            $message = "Reset complete! Reverted to original data from content.json.";
        } else {
            $message = "Site is already using original content.json data.";
        }
    }
}

$active_tab = $_GET['tab'] ?? 'customization';

// Helper to get nested data
function get_nested($content, $tab) {
    $parts = explode('.', $tab);
    $data = $content;
    foreach ($parts as $p) {
        if (!isset($data[$p])) return null;
        $data = $data[$p];
    }
    return $data;
}

$tab_data = get_nested($content, $active_tab);
$is_list = is_array($tab_data) && (isset($tab_data[0]) || empty($tab_data));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Shreetech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <style>
        :root { 
            --primary: <?= $cust['primary_color'] ?? '#FD0155' ?>; 
            --dark: #0f172a; 
            --sidebar: #1e293b; 
            --bg: <?= $cust['background_color'] ?? '#f8fafc' ?>; 
            --font-primary: <?= $cust['font_family'] ?? "'Plus Jakarta Sans', sans-serif" ?>;
        }
        body { background: var(--bg); font-family: var(--font-primary) !important; display: flex; height: 100vh; overflow: hidden; margin:0; }
        
        .sidebar { width: 260px; background: var(--sidebar); color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 30px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-header h4 { color: var(--primary); font-weight: 800; margin:0; font-size: 18px; }
        
        .nav-links { flex: 1; overflow-y: auto; padding: 20px 10px; }
        .nav-group-title { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px; }
        .nav-item { padding: 12px 15px; display: flex; align-items: center; gap: 12px; color: #94a3b8; text-decoration: none; border-radius: 10px; margin-bottom: 4px; font-size: 14px; transition: 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(253, 1, 85, 0.2); }
        .nav-item i { width: 18px; font-size: 16px; }

        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .top-bar { height: 70px; background: white; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; flex-shrink: 0; }
        .content { padding: 40px; overflow-y: auto; flex: 1; }
        
        .card-cms { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px; border: 1px solid #e2e8f0; }
        .section-title { font-weight: 800; font-size: 22px; color: var(--dark); margin-bottom: 25px; }
        
        .form-label { font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 8px; }
        .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 14px; margin-bottom: 20px; background: #f8fafc; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(253, 1, 85, 0.1); background: white; }
        
        .btn-primary-cms { background: var(--primary); color: white; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 700; transition: 0.2s; }
        .btn-primary-cms:hover { background: #d00146; transform: translateY(-1px); }
        .btn-add { background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; margin-bottom: 20px; }
        
        .table-cms { width: 100%; border-collapse: collapse; }
        .table-cms th { text-align: left; padding: 12px; font-size: 11px; color: #64748b; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-cms td { padding: 12px; font-size: 12.5px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: top; line-height: 1.5; }
        .table-cms tr:hover { background: #f8fafc; }
        .badge-tab { font-size: 10px; padding: 2px 6px; border-radius: 4px; background: rgba(255,255,255,0.1); margin-left: auto; }
        .upload-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px dashed #cbd5e1; margin-top: 10px; display: none; }
        .upload-preview.active { display: block; }
        .nav-links::-webkit-scrollbar { width: 0; display: none; }

        .nav-group { margin-bottom: 5px; }
        .nav-group-header { padding: 12px 15px; display: flex; align-items: center; gap: 12px; color: #94a3b8; text-decoration: none; border-radius: 10px; font-size: 14px; transition: 0.2s; cursor: pointer; user-select: none; }
        .nav-group-header:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-group-header i.chevron { margin-left: auto; font-size: 10px; transition: 0.3s; }
        .nav-group.active .nav-group-header { color: white; background: rgba(255,255,255,0.05); }
        .nav-group.active .nav-group-header i.chevron { transform: rotate(180deg); }
        .nav-group-content { display: none; padding-left: 20px; margin-top: 5px; }
        .nav-group.active .nav-group-content { display: block; }
        .nav-sub-item { padding: 10px 15px; display: flex; align-items: center; gap: 12px; color: #64748b; text-decoration: none; border-radius: 8px; font-size: 13px; margin-bottom: 2px; transition: 0.2s; }
        .nav-sub-item:hover { color: white; background: rgba(255,255,255,0.03); }
        .nav-sub-item.active { color: var(--primary); font-weight: 600; background: rgba(253, 1, 85, 0.05); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header" style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-graduation-cap" style="color: var(--primary); font-size: 20px;"></i>
            <h4 style="font-size: 14px;"><?= $cust['logo_text'] ?></h4>
        </div>
        <div class="nav-links">
            <a href="?tab=customization" class="nav-item <?= $active_tab=='customization'?'active':'' ?>"><i class="fa-solid fa-sliders"></i> General Customization</a>
            
            <!-- Home -->
            <div class="nav-group <?= strpos($active_tab, 'hero') !== false ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-house"></i> Home
                    <?php if (!is_page_active('index.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('index.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=hero" class="nav-sub-item <?= $active_tab=='hero'?'active':'' ?>">
                        Hero Section
                        <?php if (!is_section_active('hero')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- About Me -->
            <div class="nav-group <?= strpos($active_tab, 'about') !== false ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-user"></i> About Me
                    <?php if (!is_page_active('about.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('about.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=about" class="nav-sub-item <?= $active_tab=='about'?'active':'' ?>">
                        Bio & Image
                        <?php if (!is_section_active('about')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=about.details" class="nav-sub-item <?= $active_tab=='about.details'?'active':'' ?>">
                        Personal Details
                        <?php if (!is_section_active('about.details')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=about.education" class="nav-sub-item <?= $active_tab=='about.education'?'active':'' ?>">
                        Education
                        <?php if (!is_section_active('about.education')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Research -->
            <div class="nav-group <?= strpos($active_tab, 'research') !== false ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-flask"></i> Research & Projects
                    <?php if (!is_page_active('research.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('research.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=research" class="nav-sub-item <?= $active_tab=='research'?'active':'' ?>">
                        Research Info
                        <?php if (!is_section_active('research')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=research.patents_summary" class="nav-sub-item <?= $active_tab=='research.patents_summary'?'active':'' ?>">
                        Patents Summary
                        <?php if (!is_section_active('research.patents_summary')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=research.areas" class="nav-sub-item <?= $active_tab=='research.areas'?'active':'' ?>">
                        Research Areas
                        <?php if (!is_section_active('research.areas')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=research.thesis" class="nav-sub-item <?= $active_tab=='research.thesis'?'active':'' ?>">
                        Scholars Guided
                        <?php if (!is_section_active('research.thesis')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=research.projects" class="nav-sub-item <?= $active_tab=='research.projects'?'active':'' ?>">
                        Industrial Projects
                        <?php if (!is_section_active('research.projects')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Publications -->
            <div class="nav-group <?= strpos($active_tab, 'publications') !== false || $active_tab == 'research.patents_summary' ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-book"></i> Publications
                    <?php if (!is_page_active('publications.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('publications.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=publications.journals" class="nav-sub-item <?= $active_tab=='publications.journals'?'active':'' ?>">
                        Journals
                        <?php if (!is_section_active('publications.journals')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=publications.conferences" class="nav-sub-item <?= $active_tab=='publications.conferences'?'active':'' ?>">
                        Intl. Conferences
                        <?php if (!is_section_active('publications.conferences')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=publications.national_conferences" class="nav-sub-item <?= $active_tab=='publications.national_conferences'?'active':'' ?>">
                        National Conferences
                        <?php if (!is_section_active('publications.national_conferences')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=publications.books" class="nav-sub-item <?= $active_tab=='publications.books'?'active':'' ?>">
                        Books Published
                        <?php if (!is_section_active('publications.books')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Experience -->
            <div class="nav-group <?= strpos($active_tab, 'experience') !== false ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-briefcase"></i> Experience
                    <?php if (!is_page_active('experience.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('experience.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=experience.journey" class="nav-sub-item <?= $active_tab=='experience.journey'?'active':'' ?>">
                        Work Experience
                        <?php if (!is_section_active('experience.journey')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=experience.examinership" class="nav-sub-item <?= $active_tab=='experience.examinership'?'active':'' ?>">
                        Examinership
                        <?php if (!is_section_active('experience.examinership')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Teaching -->
            <div class="nav-group <?= strpos($active_tab, 'teaching') !== false ? 'active' : '' ?>">
                <div class="nav-group-header" onclick="this.parentElement.classList.toggle('active')">
                    <i class="fa-solid fa-chalkboard-user"></i> Teaching
                    <?php if (!is_page_active('teaching.php')): ?>
                        <span class="badge bg-danger ms-auto" style="font-size: 10px; margin-right: 5px;">Off</span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down chevron <?= (!is_page_active('teaching.php')) ? '' : 'ms-auto' ?>"></i>
                </div>
                <div class="nav-group-content">
                    <a href="?tab=teaching.subjects" class="nav-sub-item <?= $active_tab=='teaching.subjects'?'active':'' ?>">
                        Subjects Taught
                        <?php if (!is_section_active('teaching.subjects')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=teaching.courses" class="nav-sub-item <?= $active_tab=='teaching.courses'?'active':'' ?>">
                        Courses Handled
                        <?php if (!is_section_active('teaching.courses')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=teaching.mentoring" class="nav-sub-item <?= $active_tab=='teaching.mentoring'?'active':'' ?>">
                        Academic Mentoring
                        <?php if (!is_section_active('teaching.mentoring')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=teaching.certifications" class="nav-sub-item <?= $active_tab=='teaching.certifications'?'active':'' ?>">
                        Professional Certifications
                        <?php if (!is_section_active('teaching.certifications')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=teaching.workshops_organized" class="nav-sub-item <?= $active_tab=='teaching.workshops_organized'?'active':'' ?>">
                        Workshops Organized
                        <?php if (!is_section_active('teaching.workshops_organized')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=teaching.workshops_attended" class="nav-sub-item <?= $active_tab=='teaching.workshops_attended'?'active':'' ?>">
                        Workshops Attended
                        <?php if (!is_section_active('teaching.workshops_attended')): ?>
                            <span class="badge bg-danger ms-auto" style="font-size: 9px; padding: 2px 4px;">Off</span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Skills -->
            <a href="?tab=skills" class="nav-item <?= $active_tab=='skills'?'active':'' ?>">
                <i class="fa-solid fa-layer-group"></i> Skills & Categories
                <?php if (!is_page_active('skills.php')): ?>
                    <span class="badge bg-danger ms-auto text-white" style="font-size: 10px;">Off</span>
                <?php endif; ?>
            </a>

            <!-- Contact -->
            <a href="?tab=contact" class="nav-item <?= $active_tab=='contact'?'active':'' ?>">
                <i class="fa-solid fa-address-book"></i> Contact & Social
                <?php if (!is_page_active('contact.php')): ?>
                    <span class="badge bg-danger ms-auto text-white" style="font-size: 10px;">Off</span>
                <?php endif; ?>
            </a>

            <!-- Expert Talks -->
            <a href="?tab=talks" class="nav-item <?= $active_tab=='talks'?'active':'' ?>">
                <i class="fa-solid fa-microphone"></i> Expert Talks
                <?php if (!is_page_active('talks.php')): ?>
                    <span class="badge bg-danger ms-auto text-white" style="font-size: 10px;">Off</span>
                <?php endif; ?>
            </a>
        </div>
        <div class="p-3 mt-auto">
            <form action="logout.php" method="POST" id="logout-form" style="display: none;"></form>
            <a href="javascript:void(0)" onclick="if(confirm('Logout?')) document.getElementById('logout-form').submit();" class="nav-item logout-link" style="color: #fb7185; border: 1px solid rgba(251, 113, 133, 0.2);"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main">
        <div class="top-bar">
            <?php
            $friendly_names = [
                'research.thesis' => 'Scholars Guided',
                'about.details' => 'Personal Details',
                'about.education' => 'Education',
                'research.patents_summary' => 'Patents Summary',
                'research.areas' => 'Research Areas',
                'research.projects' => 'Industrial Projects',
                'publications.journals' => 'Journals',
                'publications.conferences' => 'International Conferences',
                'publications.national_conferences' => 'National Conferences',
                'publications.books' => 'Books Published',
                'experience.journey' => 'Work Experience',
                'experience.examinership' => 'Examinership',
                'teaching.subjects' => 'Subjects Taught',
                'teaching.courses' => 'Courses Handled',
                'teaching.mentoring' => 'Academic Mentoring',
                'teaching.certifications' => 'Professional Certifications',
                'teaching.workshops_organized' => 'Workshops Organized',
                'teaching.workshops_attended' => 'Workshops Attended',
            ];
            
            $parts = explode('.', $active_tab);
            $formatted_parts = array_map(function($p) { return ucfirst(str_replace('_', ' ', $p)); }, $parts);
            
            if (isset($friendly_names[$active_tab])) {
                $breadcrumb = ucfirst($parts[0]) . ' / ' . $friendly_names[$active_tab];
                $list_title = $friendly_names[$active_tab];
            } else {
                $breadcrumb = implode(' / ', $formatted_parts);
                $list_title = end($formatted_parts);
            }
            ?>
            <h5 class="m-0">Dashboard / <?= $breadcrumb ?></h5>
            <div class="d-flex gap-2">

                <?php if ($active_tab === 'customization'): ?>
                    <form method="POST" onsubmit="return confirm('Discard all custom updates and revert to the original data in content.json?');">
                        <button type="submit" name="reset_default" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset All
                        </button>
                    </form>
                <?php endif; ?>
                <a href="../index" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">View Site <i class="fa-solid fa-external-link ms-1"></i></a>
            </div>
        </div>

        <div class="content">
            <?php if($message): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="fa-solid fa-check-circle me-2"></i> <?= $message ?></div>
            <?php endif; ?>

            <?php if ($active_tab !== 'customization'): ?>
                <?php $is_active = is_section_active($active_tab); ?>
                <div class="card-cms mb-4 p-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="status-icon-circle" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa-solid <?= $is_active ? 'fa-eye text-success' : 'fa-eye-slash text-danger' ?>"></i>
                        </div>
                        <div>
                            <h4 class="m-0 font-weight-bold" style="color: white; font-size: 18px; line-height: 1.2;">Section Visibility Status</h4>
                            <p class="m-0 text-muted" style="font-size: 13px; margin-top: 4px;">This section is currently <?= $is_active ? '<strong class="text-success">Active (Visible on Live Site)</strong>' : '<strong class="text-danger">Disabled (Hidden on Live Site)</strong>' ?>.</p>
                        </div>
                    </div>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to <?= $is_active ? 'disable' : 'activate' ?> this section?');">
                        <input type="hidden" name="section_key" value="<?= $active_tab ?>">
                        <button type="submit" name="toggle_section_status" class="btn btn-lg <?= $is_active ? 'btn-danger' : 'btn-success' ?> rounded-pill px-4" style="font-weight: 700; font-size: 14px; letter-spacing: 0.5px;">
                            <i class="fa-solid <?= $is_active ? 'fa-circle-xmark' : 'fa-circle-check' ?> me-2"></i>
                            <?= $is_active ? 'Disable' : 'Active' ?>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($active_tab === 'contact'): ?>
                <!-- CUSTOM CONTACT VIEW -->
                <div class="card-cms">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0">Contact & Social Info</h2>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="section_key" value="contact">
                        
                        <h5 class="mb-3 text-primary" style="font-weight: 700;">Basic Info</h5>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="data[email]" class="form-control" value="<?= htmlspecialchars($tab_data['email'] ?? '') ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Mailing Address</label>
                            <textarea name="data[mailing_address]" class="form-control" rows="3"><?= htmlspecialchars($tab_data['mailing_address'] ?? '') ?></textarea>
                        </div>

                        <hr class="my-4 border-light">
                        <h5 class="mb-3 text-primary" style="font-weight: 700;">Phone Numbers</h5>
                        <div id="phones-container">
                            <?php foreach($tab_data['phones'] ?? [] as $idx => $phone): ?>
                                <div class="d-flex gap-2 mb-2 phone-row">
                                    <input type="text" name="data[phones][]" class="form-control mb-0" value="<?= htmlspecialchars($phone) ?>" placeholder="Phone number">
                                    <button type="button" class="btn btn-danger btn-sm px-3 rounded-3" onclick="this.parentElement.remove()"><i class="fa-solid fa-times"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-4 rounded-3" onclick="addPhone()"><i class="fa-solid fa-plus me-1"></i> Add Phone</button>

                        <hr class="my-4 border-light">
                        <h5 class="mb-3 text-primary" style="font-weight: 700;">Offices / Firms</h5>
                        <div id="offices-container">
                            <?php foreach($tab_data['offices'] ?? [] as $idx => $office): ?>
                                <div class="row mb-2 office-row">
                                    <div class="col-md-5">
                                        <input type="text" name="data[offices][<?= $idx ?>][name]" class="form-control mb-0" value="<?= htmlspecialchars($office['name'] ?? '') ?>" placeholder="Office Name">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="data[offices][<?= $idx ?>][location]" class="form-control mb-0" value="<?= htmlspecialchars($office['location'] ?? '') ?>" placeholder="Location">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100 rounded-3 h-100" onclick="this.parentElement.parentElement.remove()"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-4 rounded-3" onclick="addOffice()"><i class="fa-solid fa-plus me-1"></i> Add Office</button>

                        <hr class="my-4 border-light">
                        <h5 class="mb-3 text-primary" style="font-weight: 700;">Social Links</h5>
                        <div id="social-container">
                            <?php foreach($tab_data['social'] ?? [] as $idx => $social): ?>
                                <div class="row mb-2 social-row">
                                    <div class="col-md-3">
                                        <input type="text" name="data[social][<?= $idx ?>][platform]" class="form-control mb-0" value="<?= htmlspecialchars($social['platform'] ?? '') ?>" placeholder="Platform">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="data[social][<?= $idx ?>][link]" class="form-control mb-0" value="<?= htmlspecialchars($social['link'] ?? '') ?>" placeholder="URL">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="data[social][<?= $idx ?>][icon]" class="form-control mb-0" value="<?= htmlspecialchars($social['icon'] ?? '') ?>" placeholder="Icon Class (e.g. fa-brands fa-twitter)">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100 rounded-3 h-100" onclick="this.parentElement.parentElement.remove()"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-4 rounded-3" onclick="addSocial()"><i class="fa-solid fa-plus me-1"></i> Add Social Link</button>

                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" name="save_section" class="btn-primary-cms w-100 py-3 text-uppercase" style="font-size: 15px; letter-spacing: 1px;"><i class="fa-solid fa-save me-2"></i> Save All Contact Info</button>
                        </div>
                    </form>
                </div>
                
                <script>
                    let officeCount = <?= !empty($tab_data['offices']) ? max(array_keys($tab_data['offices'])) + 1 : 0 ?>;
                    let socialCount = <?= !empty($tab_data['social']) ? max(array_keys($tab_data['social'])) + 1 : 0 ?>;
                    
                    function addPhone() {
                        const div = document.createElement('div');
                        div.className = 'd-flex gap-2 mb-2 phone-row';
                        div.innerHTML = '<input type="text" name="data[phones][]" class="form-control mb-0" placeholder="Phone number"><button type="button" class="btn btn-danger btn-sm px-3 rounded-3" onclick="this.parentElement.remove()"><i class="fa-solid fa-times"></i></button>';
                        document.getElementById('phones-container').appendChild(div);
                    }
                    
                    function addOffice() {
                        const div = document.createElement('div');
                        div.className = 'row mb-2 office-row';
                        div.innerHTML = '<div class="col-md-5"><input type="text" name="data[offices]['+officeCount+'][name]" class="form-control mb-0" placeholder="Office Name"></div><div class="col-md-6"><input type="text" name="data[offices]['+officeCount+'][location]" class="form-control mb-0" placeholder="Location"></div><div class="col-md-1"><button type="button" class="btn btn-danger w-100 rounded-3 h-100" onclick="this.parentElement.parentElement.remove()"><i class="fa-solid fa-times"></i></button></div>';
                        document.getElementById('offices-container').appendChild(div);
                        officeCount++;
                    }
                    
                    function addSocial() {
                        const div = document.createElement('div');
                        div.className = 'row mb-2 social-row';
                        div.innerHTML = '<div class="col-md-3"><input type="text" name="data[social]['+socialCount+'][platform]" class="form-control mb-0" placeholder="Platform"></div><div class="col-md-5"><input type="text" name="data[social]['+socialCount+'][link]" class="form-control mb-0" placeholder="URL"></div><div class="col-md-3"><input type="text" name="data[social]['+socialCount+'][icon]" class="form-control mb-0" placeholder="Icon Class"></div><div class="col-md-1"><button type="button" class="btn btn-danger w-100 rounded-3 h-100" onclick="this.parentElement.parentElement.remove()"><i class="fa-solid fa-times"></i></button></div>';
                        document.getElementById('social-container').appendChild(div);
                        socialCount++;
                    }
                </script>
            <?php elseif ($is_list): ?>
                <!-- LIST VIEW -->
                <div class="card-cms">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0"><?= $list_title ?> List</h2>
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus me-1"></i> Add New</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-cms">
                            <thead>
                                <tr>
                                    <?php 
                                    $sample = $tab_data[0] ?? [];
                                    if(is_array($sample)):
                                        foreach(array_keys($sample) as $k): ?>
                                            <th><?= ucfirst($k) ?></th>
                                        <?php endforeach; 
                                    else: ?>
                                        <th>Content</th>
                                    <?php endif; ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tab_data as $idx => $item): ?>
                                    <tr>
                                        <?php if(is_array($item)): ?>
                                            <?php foreach($item as $val): ?>
                                                <td><?= (strlen($val) > 60) ? substr(strip_tags($val), 0, 60).'...' : $val ?></td>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <td><?= (strlen($item) > 100) ? substr(strip_tags($item), 0, 100).'...' : $item ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editModal<?= $idx ?>"><i class="fa-solid fa-pencil text-primary"></i></button>
                                                <form method="POST" onsubmit="return confirm('Delete this item?');">
                                                    <input type="hidden" name="section_key" value="<?= $active_tab ?>">
                                                    <input type="hidden" name="item_index" value="<?= $idx ?>">
                                                    <button type="submit" name="delete_item" class="btn btn-sm btn-light"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?= $idx ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-4 border-0">
                                                <form method="POST">
                                                    <div class="modal-header border-0 pb-0"><h5 class="modal-title font-weight-bold">Edit Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                    <div class="modal-body p-4">
                                                        <input type="hidden" name="section_key" value="<?= $active_tab ?>">
                                                        <input type="hidden" name="item_index" value="<?= $idx ?>">
                                                        <?php if(is_array($item)): ?>
                                                            <?php foreach($item as $key => $val): ?>
                                                                <label class="form-label"><?= ucfirst($key) ?></label>
                                                                <?php if($key === 'icon'): ?>
                                                                    <input type="text" name="data[<?= $key ?>]" class="form-control" value="<?= htmlspecialchars($val) ?>" placeholder="e.g. fa-solid fa-code">
                                                                <?php elseif((strpos($key, 'image') !== false || strpos($key, 'logo') !== false || strpos($key, 'photo') !== false) && $key !== 'logo_text'): ?>
                                                                    <div class="upload-container mb-3">
                                                                        <input type="hidden" name="data[<?= $key ?>]" class="image-path-input" value="<?= htmlspecialchars($val) ?>">
                                                                        <input type="file" class="d-none image-upload-input" accept="image/*">
                                                                        <button class="btn btn-outline-primary w-100 btn-upload-trigger py-2" type="button">
                                                                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload from Computer
                                                                        </button>
                                                                        <div class="text-center">
                                                                            <img src="../<?= $val ?>" class="upload-preview active mt-2 mx-auto" style="width:100px; height:100px; border-radius:12px; border:2px solid #eee;">
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <textarea name="data[<?= $key ?>]" class="form-control mb-3" rows="3"><?= htmlspecialchars($val) ?></textarea>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <label class="form-label">Content</label>
                                                            <textarea name="data" class="form-control mb-3" rows="5"><?= $item ?></textarea>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0"><button type="submit" name="edit_item" class="btn-primary-cms w-100">Save Changes</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Modal -->
                <div class="modal fade" id="addModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4 border-0">
                            <form method="POST">
                                <div class="modal-header border-0 pb-0"><h5 class="modal-title font-weight-bold">Add New Entry</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body p-4">
                                    <input type="hidden" name="section_key" value="<?= $active_tab ?>">
                                    <?php if(is_array($sample)): ?>
                                        <?php foreach(array_keys($sample) as $key): ?>
                                            <label class="form-label"><?= ucfirst($key) ?></label>
                                            <?php if($key === 'icon'): ?>
                                                <input type="text" name="new_data[<?= $key ?>]" class="form-control mb-3" placeholder="e.g. fa-solid fa-code">
                                            <?php elseif((strpos($key, 'image') !== false || strpos($key, 'logo') !== false || strpos($key, 'photo') !== false) && $key !== 'logo_text'): ?>
                                                <div class="upload-container mb-3">
                                                    <input type="hidden" name="new_data[<?= $key ?>]" class="image-path-input">
                                                    <input type="file" class="d-none image-upload-input" accept="image/*">
                                                    <button class="btn btn-outline-primary w-100 btn-upload-trigger py-3" type="button">
                                                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload from Computer
                                                    </button>
                                                    <div class="text-center">
                                                        <img src="" class="upload-preview mt-3 mx-auto" style="width:120px; height:120px; border-radius:15px; border:3px solid #eee;">
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <textarea name="new_data[<?= $key ?>]" class="form-control mb-3" rows="3" placeholder="Enter <?= $key ?>..."></textarea>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <label class="form-label">Content</label>
                                        <textarea name="new_data" class="form-control mb-3" rows="5" placeholder="Enter content..."></textarea>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer border-0 pt-0"><button type="submit" name="add_item" class="btn-primary-cms w-100">Add Entry</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- SINGLE SECTION VIEW -->
                <div class="card-cms">
                    <form method="POST">
                        <div class="mb-4 pb-3 border-bottom">
                            <h2 class="m-0">Edit <?= ucfirst(str_replace('_', ' ', $active_tab)) ?></h2>
                        </div>
                        <input type="hidden" name="section_key" value="<?= $active_tab ?>">
                        <?php foreach($tab_data as $key => $val): ?>
                            <?php 
                            if($active_tab === 'contact' && is_array($val)) continue; 
                            if($active_tab === 'research' && $key === 'patents_summary') continue; 
                            if($active_tab === 'customization' && in_array($key, ['page_status', 'section_status', 'is_profile_setup'])) continue;
                            
                            // Check if it's a complex list that should be skipped here
                            if(is_array($val)) {
                                $is_flat = true;
                                foreach($val as $item) if(is_array($item)) { $is_flat = false; break; }
                                if(!$is_flat) continue;
                            }
                            ?>
                            <div class="mb-3">
                                <label class="form-label"><?= ucfirst(str_replace('_', ' ', $key)) ?></label>
                                <?php if(is_array($val)): // Flat list like Bio/Designations ?>
                                    <textarea name="<?= $key ?>" class="form-control" rows="6"><?= implode("\n", $val) ?></textarea>
                                    <small class="text-muted d-block mt-n3 mb-3">Enter one item per line.</small>
                                <?php elseif(is_string($val) && strlen($val) > 100): ?>
                                    <textarea name="data[<?= $key ?>]" class="form-control" rows="5"><?= $val ?></textarea>
                                <?php elseif((strpos($key, 'image') !== false || strpos($key, 'logo') !== false || strpos($key, 'photo') !== false) && $key !== 'logo_text'): ?>
                                    <div class="upload-container mb-4">
                                        <input type="hidden" name="data[<?= $key ?>]" class="image-path-input" value="<?= htmlspecialchars($val) ?>">
                                        <input type="file" class="d-none image-upload-input" accept="image/*">
                                        <button class="btn btn-outline-primary w-100 btn-upload-trigger py-3" type="button">
                                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload from Computer
                                        </button>
                                        <div class="text-center">
                                            <img src="../<?= $val ?>" class="upload-preview active mt-3 mx-auto" style="width:150px; height:150px; border-radius:20px; border:4px solid white; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                                        </div>
                                    </div>
                                <?php elseif($key === 'background_color'): ?>
                                    <select name="data[<?= $key ?>]" class="form-control">
                                        <option value="#ffffff" <?= $val === '#ffffff' ? 'selected' : '' ?>>Default White (#ffffff)</option>
                                        <option value="#f8fafc" <?= $val === '#f8fafc' ? 'selected' : '' ?>>Light Slate (#f8fafc)</option>
                                        <option value="#fdfbf7" <?= $val === '#fdfbf7' ? 'selected' : '' ?>>Warm Cream (#fdfbf7)</option>
                                        <option value="#f0f4f8" <?= $val === '#f0f4f8' ? 'selected' : '' ?>>Cool Blue-Gray (#f0f4f8)</option>
                                    </select>
                                <?php elseif($key === 'font_family'): ?>
                                    <select name="data[<?= $key ?>]" class="form-control">
                                        <option value="'Plus Jakarta Sans', sans-serif" <?= strpos($val, 'Jakarta') !== false ? 'selected' : '' ?>>Plus Jakarta Sans</option>
                                        <option value="'Inter', sans-serif" <?= strpos($val, 'Inter') !== false ? 'selected' : '' ?>>Inter</option>
                                        <option value="'Roboto', sans-serif" <?= strpos($val, 'Roboto') !== false ? 'selected' : '' ?>>Roboto</option>
                                        <option value="'Poppins', sans-serif" <?= strpos($val, 'Poppins') !== false ? 'selected' : '' ?>>Poppins</option>
                                    </select>
                                <?php elseif($key === 'primary_color'): ?>
                                    <select name="data[<?= $key ?>]" class="form-control">
                                        <option value="#ff0000" <?= $val === '#ff0000' ? 'selected' : '' ?>>Red (#ff0000)</option>
                                        <option value="#007bff" <?= $val === '#007bff' ? 'selected' : '' ?>>Blue (#007bff)</option>
                                        <option value="#28a745" <?= $val === '#28a745' ? 'selected' : '' ?>>Green (#28a745)</option>
                                        <option value="#6f42c1" <?= $val === '#6f42c1' ? 'selected' : '' ?>>Purple (#6f42c1)</option>
                                    </select>
                                <?php elseif(!is_array($val)): ?>
                                    <input type="text" name="data[<?= $key ?>]" class="form-control" value="<?= htmlspecialchars($val) ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="mt-4 pt-3">
                            <button type="submit" name="save_section" class="btn-primary-cms w-100"><i class="fa-solid fa-save me-2"></i> Save Changes</button>
                        </div>
                    </form>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-upload-trigger')) {
            const container = e.target.closest('.mb-3') || e.target.closest('.mb-4') || e.target.closest('.modal-body');
            const fileInput = container.querySelector('.image-upload-input');
            fileInput.click();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-upload-input')) {
            const file = e.target.files[0];
            if (!file) return;

            const container = e.target.closest('.mb-3') || e.target.closest('.mb-4') || e.target.closest('.modal-body');
            const pathInput = container.querySelector('.image-path-input');
            const previewImg = container.querySelector('.upload-preview');

            const formData = new FormData();
            formData.append('image', file);

            fetch('process_upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pathInput.value = data.path;
                    previewImg.src = '../' + data.path;
                    previewImg.classList.add('active');
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during upload.');
            });
        }
    });
    </script>
</body>
</html>
