<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
            size: a4 landscape;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9ff;
            color: #0d1c2e;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #f8f9ff;
            overflow: hidden;
        }

        /* Subtle Background Accents */
        .accent-top {
            position: absolute;
            top: -40mm;
            right: -40mm;
            width: 120mm;
            height: 120mm;
            background: #e6eeff;
            border-radius: 50%;
            z-index: 1;
        }

        .accent-bottom {
            position: absolute;
            bottom: -60mm;
            left: -60mm;
            width: 160mm;
            height: 160mm;
            background: #dce9ff;
            border-radius: 50%;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 30mm 20mm;
            height: 150mm;
        }

        .header-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 6px;
            color: #757684;
            margin-bottom: 6mm;
            font-weight: 500;
        }

        .main-title {
            font-size: 50px;
            font-weight: 900;
            color: #00288e;
            margin-bottom: 12mm;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        .certify-text {
            font-size: 18px;
            color: #444653;
            margin-bottom: 8mm;
        }

        .student-name {
            font-size: 42px;
            font-weight: bold;
            color: #0d1c2e;
            margin-bottom: 10mm;
            display: block;
        }

        .course-label {
            font-size: 16px;
            color: #444653;
            margin-bottom: 4mm;
        }

        .course-name {
            font-size: 26px;
            font-weight: 800;
            color: #006c49;
            margin-bottom: 20mm;
            padding: 0 25mm;
            line-height: 1.2;
        }

        .footer {
            position: absolute;
            bottom: 30mm;
            width: 100%;
            z-index: 10;
            height: 30mm;
        }

        .footer-block {
            position: absolute;
            top: 0;
            width: 90mm;
        }

        .left-block {
            left: 30mm;
            text-align: left;
        }

        .right-block {
            right: 30mm;
            text-align: right;
        }

        .signature-line {
            width: 65mm;
            height: 1px;
            background: #c4c5d5;
            margin-bottom: 5mm;
        }

        .name-area {
            height: 10mm;
            margin-bottom: 1mm;
            vertical-align: bottom;
            display: block;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #0d1c2e;
        }

        .signature-title {
            font-size: 11px;
            color: #757684;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .certificate-id {
            position: absolute;
            bottom: 12mm;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #c4c5d5;
            letter-spacing: 2px;
            font-family: monospace;
            z-index: 10;
        }

        .watermark {
            position: absolute;
            top: 20mm;
            left: 20mm;
            opacity: 0.1;
            font-weight: 900;
            font-size: 20px;
            color: #00288e;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="accent-top"></div>
        <div class="accent-bottom"></div>
        
        <div class="watermark">MINI-LMS</div>

        <div class="content">
            <div class="header-label">Official Accreditation</div>
            <div class="main-title">Certificate of Completion</div>
            
            <div class="certify-text">This prestigious recognition is awarded to</div>
            <div class="student-name">{{ $enrolement->user->name }}</div>
            
            <div class="course-label">for achieving exceptional proficiency in</div>
            <div class="course-name">{{ $enrolement->course->title }}</div>
        </div>

        <div class="footer">
            <div class="footer-block left-block">
                <div class="signature-line"></div>
                <div class="name-area">
                    <div class="signature-name">{{ $enrolement->course->instructor->name }}</div>
                </div>
                <div class="signature-title">Lead Instructor</div>
            </div>

            <div class="footer-block right-block">
                <div class="signature-line" style="margin-left: auto;"></div>
                <div class="name-area">
                    <div class="signature-name">{{ $enrolement->completed_at ? $enrolement->completed_at->format('F d, Y') : now()->format('F d, Y') }}</div>
                </div>
                <div class="signature-title">Date of Completion</div>
            </div>
        </div>

        <div class="certificate-id">
            VERIFICATION ID: {{ strtoupper(substr(md5($enrolement->id), 0, 16)) }}
        </div>
    </div>
</body>
</html>
