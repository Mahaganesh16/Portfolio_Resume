<?php
require_once('functions.php');

$validation = validate_profile($p_content);
if ($validation !== true) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'bottom',
                icon: 'error',
                title: '" . addslashes($validation) . "',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'index.php';
            });
        });
    </script>";
    exit;
}

// Map $p_content to $siteData for compatibility with the existing CV template
$siteData = [
    'profile' => [
        'name' => $p_content['hero']['name'],
        'title' => $p_content['customization']['sidebar_title'],
        'email' => $p_content['contact']['email'],
        'phone' => $p_content['contact']['phones'][0] ?? $p_content['customization']['sidebar_phone'],
        'address' => $p_content['contact']['mailing_address'] ?? $p_content['customization']['sidebar_address'],
        'image' => $p_content['hero']['image'],
        'summary' => $p_content['hero']['description']
    ],
    'about' => [
        'summary' => implode("\n\n", $p_content['about']['bio']),
        'education' => array_map(function ($edu) {
            $parts = explode(' — ', $edu['year']);
            return [
                'degree' => $parts[1] ?? '',
                'title' => $edu['title'],
                'year' => $parts[0] ?? $edu['year'],
                'university' => $edu['desc']
            ];
        }, $p_content['about']['education'])
    ],
    'experience' => array_map(function ($exp) {
        return [
            'title' => $exp['role'],
            'duration' => $exp['period'],
            'location' => $exp['location'],
            'desc' => $exp['desc']
        ];
    }, $p_content['experience']['journey']),
    'research' => [
        'intro' => $p_content['research']['intro'],
        'patents' => [
            'national' => $p_content['research']['patents_summary']['national_count'],
            'international' => $p_content['research']['patents_summary']['intl_count'],
            'utility' => $p_content['research']['patents_summary']['utility_design_count']
        ]
    ],
    'publications' => [
        'journals' => array_map(function ($j) {
            return [
                'title' => $j['title'],
                'subtitle' => $j['source'] . ' — ' . ($j['authors'] ?? '')
            ];
        }, $p_content['publications']['journals'])
    ],
    'technical_skills' => array_map(function ($s) {
        return [
            'category' => $s['category'],
            'items' => str_replace("\n", ", ", $s['items'])
        ];
    }, $p_content['skills'])
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curriculum Vitae - <?php echo $siteData['profile']['name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF014F;
            --secondary: #333;
            --text: #444;
            --light: #f4f4f4;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 12mm 12mm 12mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                width: 100% !important;
            }

            .cv-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            section {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .item {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            a {
                text-decoration: none;
                color: inherit;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f0f2f5;
            color: var(--text);
            line-height: 1.5;
            margin: 0;
            padding: 40px 0;
        }

        .swal2-toast {
            font-size: 1.6rem !important;
        }

        .cv-container {
            width: 800px;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #eee;
        }

        header {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }

        .header-info {
            display: table-cell;
            width: 75%;
            vertical-align: top;
        }

        .header-info h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
            color: var(--secondary);
            text-transform: uppercase;
        }

        .header-info h4 {
            margin: 5px 0 15px;
            color: var(--primary);
            font-weight: 600;
            font-size: 16px;
        }

        .contact-grid {
            width: 100%;
            display: table;
        }

        .contact-row {
            display: table-row;
        }

        .contact-item {
            display: table-cell;
            width: 50%;
            padding-bottom: 8px;
            font-size: 13px;
            vertical-align: middle;
        }

        .contact-item i {
            margin-right: 6px;
            color: var(--primary);
        }

        .profile-img-wrap {
            display: table-cell;
            width: 25%;
            vertical-align: top;
            text-align: right;
        }

        .profile-img {
            width: 110px;
            height: 110px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid var(--light);
        }

        section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            color: var(--secondary);
            text-transform: uppercase;
            border-left: 4px solid var(--primary);
            padding: 5px 15px;
            margin-bottom: 15px;
            background: #fff9fa;
            font-weight: 700;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-header {
            font-weight: 700;
            color: #222;
            font-size: 14px;
            display: table;
            width: 100%;
        }

        .item-header .title-text {
            display: table-cell;
            width: 75%;
        }

        .item-header .year-text {
            display: table-cell;
            width: 25%;
            text-align: right;
            color: var(--primary);
            white-space: nowrap;
        }

        .item-sub {
            color: var(--primary);
            font-weight: 500;
            font-size: 13px;
            margin-top: 2px;
        }

        .item-desc {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
            text-align: justify;
        }

        .skills-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
        }

        .skills-row {
            display: table-row;
        }

        .skill-cat {
            display: table-cell;
            background: var(--light);
            padding: 12px;
            border-radius: 5px;
            width: 50%;
            vertical-align: top;
        }

        .skill-cat strong {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: var(--secondary);
        }

        .skill-cat span {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 1, 79, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            text-decoration: none;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            color: var(--secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            z-index: 1000;
        }
    </style>
</head>

<body>

    <a href="index" class="back-btn no-print"><i class="fa-solid fa-arrow-left"></i> Back to Site</a>

    <button class="print-btn no-print" onclick="downloadPDF()">
        <i class="fa-solid fa-file-pdf"></i> Download PDF
    </button>

    <div class="cv-container">
        <header>
            <div class="header-info">
                <h1><?php echo $siteData['profile']['name']; ?></h1>
                <h4><?php echo $siteData['profile']['title']; ?></h4>
                <div class="contact-grid">
                    <div class="contact-row">
                        <div class="contact-item"><i class="fa-solid fa-envelope"></i>
                            <?php echo $siteData['profile']['email']; ?></div>
                        <div class="contact-item"><i class="fa-solid fa-phone"></i>
                            <?php echo $siteData['profile']['phone']; ?></div>
                    </div>
                    <div class="contact-row">
                        <div class="contact-item"><i class="fa-solid fa-location-dot"></i>
                            <?php echo $siteData['profile']['address']; ?></div>
                        <div class="contact-item"><i class="fa-solid fa-globe"></i> Professional Portfolio</div>
                    </div>
                </div>
            </div>
            <div class="profile-img-wrap">
                <img src="<?php echo $siteData['profile']['image'] ?? 'assets/images/logo/image.png'; ?>" alt="Profile"
                    class="profile-img">
            </div>
        </header>

        <section>
            <div class="section-title">Professional Summary</div>
            <p style="font-size: 15px;"><?php echo $siteData['about']['summary'] ?? $siteData['profile']['summary']; ?>
            </p>
        </section>

        <section>
            <div class="section-title">Education</div>
            <?php foreach ($siteData['about']['education'] as $edu): ?>
                <?php if (isset($edu['status']) && $edu['status'] === 'inactive')
                    continue; ?>
                <div class="item">
                    <div class="item-header">
                        <span class="title-text"><?php echo $edu['degree']; ?> - <?php echo $edu['title']; ?></span>
                        <span class="year-text"><?php echo $edu['year']; ?></span>
                    </div>
                    <div class="item-sub"><?php echo $edu['university']; ?></div>
                </div>
            <?php endforeach; ?>
        </section>

        <section>
            <div class="section-title">Professional Experience</div>
            <?php foreach ($siteData['experience'] as $exp): ?>
                <?php if (isset($exp['status']) && $exp['status'] === 'inactive')
                    continue; ?>
                <div class="item">
                    <div class="item-header">
                        <span class="title-text"><?php echo $exp['title']; ?></span>
                        <span class="year-text"><?php echo $exp['duration']; ?></span>
                    </div>
                    <div class="item-sub"><?php echo $exp['location']; ?></div>
                    <div class="item-desc"><?php echo $exp['desc']; ?></div>
                </div>
            <?php endforeach; ?>
        </section>

        <section>
            <div class="section-title">Research & Innovation</div>
            <div class="item-desc" style="margin-bottom: 15px;"><?php echo $siteData['research']['intro']; ?></div>
            <div class="item-header" style="font-size: 14px;">Key Patents:</div>
            <ul class="list" style="font-size: 14px;">
                <li>National Patents: <?php echo $siteData['research']['patents']['national']; ?></li>
                <li>International Patents: <?php echo $siteData['research']['patents']['international']; ?></li>
                <li>Utility Models: <?php echo $siteData['research']['patents']['utility']; ?></li>
            </ul>
        </section>

        <section>
            <div class="section-title">Publications (Recent Journals)</div>
            <ul class="list" style="font-size: 13px;">
                <?php foreach (array_slice($siteData['publications']['journals'], 0, 8) as $j): ?>
                    <?php if (isset($j['status']) && $j['status'] === 'inactive')
                        continue; ?>
                    <li><strong><?php echo $j['title']; ?></strong> - <?php echo $j['subtitle']; ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section>
            <div class="section-title">Technical Skills</div>
            <div class="skills-grid">
                <?php
                $skills = array_filter($siteData['technical_skills'], function ($s) {
                    return !(isset($s['status']) && $s['status'] === 'inactive');
                });
                $skills = array_values($skills);
                for ($i = 0; $i < count($skills); $i += 2):
                    ?>
                    <div class="skills-row">
                        <div class="skill-cat">
                            <strong><?php echo $skills[$i]['category']; ?></strong>
                            <span><?php echo $skills[$i]['items']; ?></span>
                        </div>
                        <?php if (isset($skills[$i + 1])): ?>
                            <div class="skill-cat">
                                <strong><?php echo $skills[$i + 1]['category']; ?></strong>
                                <span><?php echo $skills[$i + 1]['items']; ?></span>
                            </div>
                        <?php else: ?>
                            <div class="skill-cat" style="background:transparent;"></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <footer
            style="margin-top: 50px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; padding-top: 20px;">
            This CV is dynamically generated from <?php echo $siteData['profile']['name']; ?>'s Professional Portfolio.
        </footer>
    </div>

    <script>
        function downloadPDF() {
            window.print();
        }
    </script>
</body>

</html>