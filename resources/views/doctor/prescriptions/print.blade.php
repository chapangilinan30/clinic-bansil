@if(auth()->check() && auth()->user()->id === 6)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription #{{ $prescription->id }} - Dr. Paul Anthony E. Bansil</title>

    <style>
        /* CSS Page Setup for Half Short Bond Paper (5.5" x 8.5") */
        @page {
            size: 5.5in 8.5in;
            margin: 0;
        }

        /* Base Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 20px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            width: 5.5in;
            min-height: 8.5in;
            margin: 0 auto;
            padding: 20px 24px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
            position: relative;
            overflow: hidden;
        }

        .content-wrap {
            position: relative;
            z-index: 1;
        }

        /* Editable visual cues on web preview */
        [contenteditable="true"] {
            outline: 1px dashed transparent;
            transition: all 0.2s ease;
            border-radius: 3px;
            padding: 1px 2px;
        }

        [contenteditable="true"]:hover {
            outline: 1px dashed #cbd5e1;
            background-color: rgba(241, 245, 249, 0.5);
        }

        [contenteditable="true"]:focus {
            outline: 2px solid #3b82f6;
            background-color: #ffffff;
        }

        /* Top-Left Logo Header Layout */
        .clinic-header-wrap {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 16px;
            margin-bottom: 8px;
            position: relative;
        }

        .header-logo-img {
            max-height: 55px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        .clinic-header-text {
            text-align: left;
        }

        .main-clinic-title {
            font-size: 18px;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .doctor-title-name {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }

        .doctor-specialty {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* 3-Column Clinic Locations Layout */
        .branches-grid {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 6px;
        }

        .branch-col {
            flex: 1;
            font-size: 8px;
            line-height: 1.25;
            color: #334155;
        }

        .branch-name {
            font-weight: 700;
            color: #0f172a;
        }

        /* Facebook Section - Styled as in image */
        .fb-page-bar {
            text-align: center;
            font-size: 8.5px;
            color: #1e40af;
            background: #eff6ff;
            padding: 4px 6px;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 1px solid #dbeafe;
            line-height: 1.35;
        }

        .fb-page-bar strong {
            font-weight: 900;
            font-size: 9px;
            color: #1e3a8a;
            letter-spacing: 0.3px;
        }

        /* Hospital Affiliations Banner */
        .affiliations-box {
            text-align: center;
            border-top: 1px solid #0f172a;
            border-bottom: 1.5px solid #0f172a;
            padding: 4px 0;
            margin: 6px 0 12px 0;
        }

        .affiliations-title {
            font-size: 7.5px;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e293b;
            letter-spacing: 0.3px;
        }

        .affiliations-list {
            font-size: 7px;
            color: #475569;
            margin-top: 1px;
            line-height: 1.2;
        }

        /* Patient Meta Information Layout */
        .patient-meta-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px;
            font-size: 11px;
        }

        .patient-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 12px;
            width: 100%;
        }

        .meta-field {
            display: flex;
            align-items: flex-end;
            gap: 6px;
        }

        .meta-field.patient-name {
            flex: 1;
        }

        .meta-field.patient-date {
            white-space: nowrap;
            flex-shrink: 0;
        }

        .meta-field.patient-address {
            width: 100%;
        }

        .meta-line {
            border-bottom: 1px solid #94a3b8;
            flex: 1;
            font-weight: 600;
            color: #0f172a;
            padding-left: 4px;
            padding-bottom: 1px;
            display: inline-block;
        }

        /* Rx Symbol */
        .rx-badge {
            font-size: 28px;
            font-family: "Times New Roman", Times, serif;
            font-weight: bold;
            font-style: italic;
            color: #0f172a;
            line-height: 1;
            margin: 4px 0 8px 0;
        }

        /* Table Layout for Prescription Items */
        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 16px;
        }

        .medicine-table th {
            background: #f8fafc;
            color: #0f172a;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1.5px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }

        .medicine-table td {
            border-bottom: 1px solid #f1f5f9;
            padding: 7px 8px;
            font-size: 10.5px;
            color: #334155;
            font-weight: 500;
        }

        .med-name {
            font-weight: 700;
            color: #1e293b;
        }

        .med-instructions {
            font-style: italic;
            color: #64748b;
            font-size: 9.5px;
        }

        /* Clinical Diagnosis Section */
        .diagnosis-box {
            margin-bottom: 10px;
            font-size: 10.5px;
            line-height: 1.4;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        /* Footer Info & Signature Section */
        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
        }

        .footer-left {
            font-size: 9.5px;
            color: #334155;
        }

        .footer-right {
            text-align: right;
        }

        .doc-signature-block {
            display: inline-block;
            text-align: left;
            width: 180px;
        }

        .doc-name-signature {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .doc-credential-line {
            font-size: 9.5px;
            color: #334155;
            margin-top: 2px;
        }

        .line-fill {
            border-bottom: 1px solid #64748b;
            display: inline-block;
            width: 90px;
        }

        /* Printable Header Action Bar */
        .print-btn-bar {
            width: 5.5in;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-hint {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .btn-premium {
            cursor: pointer;
            padding: 8px 16px;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }

        .btn-premium:hover {
            background: #334155;
        }

        /* Print Override */
        @media print {
            @page {
                size: 5.5in 8.5in;
                margin: 0 !important;
            }

            html, body {
                width: 5.5in !important;
                height: 8.5in !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }

            .print-btn-bar {
                display: none !important;
            }

            .container {
                width: 5.5in !important;
                height: 8.5in !important;
                max-width: 5.5in !important;
                max-height: 8.5in !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0.25in !important;
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            [contenteditable="true"] {
                outline: none !important;
                background-color: transparent !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>

    <!-- Top Action Bar -->
    <div class="print-btn-bar">
        <span class="edit-hint">✏️ Tip: Click text to edit before printing.</span>
        <button onclick="window.print()" class="btn-premium">
            Print Prescription
        </button>
    </div>

    <div class="container">
        <div class="content-wrap">
            
            <!-- Clinic Header with Logo explicitly positioned on Top Left -->
            <div class="clinic-header-wrap">
                <img src="{{ asset('Image/image-removebg-preview.png') }}" alt="Clinica Bansil Logo" class="header-logo-img">
                <div class="clinic-header-text" contenteditable="true">
                    <div class="main-clinic-title">CLINICA BANSIL</div>
                    <div class="doctor-title-name">PAUL ANTHONY E. BANSIL, M.D.</div>
                    <div class="doctor-specialty">PEDIATRICIAN</div>
                </div>
            </div>

            <!-- Clinic Locations Grid -->
            <div class="branches-grid" contenteditable="true">
                <div class="branch-col">
                    <span class="branch-name">Clinica Bansil Mabalacat</span><br>
                    Mabiga, Mabalacat City<br>
                    Mon. to Sat. 10am-12 nn.; Mon. 5pm<br>
                    Sec. Jay: 0933-548-7142
                </div>
                <div class="branch-col">
                    <span class="branch-name">Clinica Bansil Angeles City Branch</span><br>
                    Pandan, Angeles City<br>
                    Mon. to Sat.: 2pm - 4pm<br>
                    Sec. Chris: 0932-290-2029
                </div>
                <div class="branch-col">
                    <span class="branch-name">Angeles City Mother and Child Hospital</span> - Room 101<br>
                    Diamond Subdivision, Balibago<br>
                    Tues, Wed and Fri: 5PM (Per Appointment only)<br>
                    Sec Sarah: 0948-188-9309
                </div>
            </div>

            <!-- Exact Facebook Message from Prescription -->
            <div class="fb-page-bar" contenteditable="true">
                Please like us on Facebook!<br>
                <strong>CLINICA BANSIL</strong><br>
                Message us for inquiries
            </div>

            <!-- Hospital Affiliations Banner -->
            <div class="affiliations-box" contenteditable="true">
                <div class="affiliations-title">HOSPITAL AFFILIATIONS:</div>
                <div class="affiliations-list">
                    • Angeles City Mother and Child Hospital • St. Raphael Foundation and Medical Center<br>
                    • St. Catherine Of Alexandria Foundation and Medical Center • AC Sacred Heart Medical Center • Angeles Medical Center<br>
                    • Dr. Amando Garcia Medical Center • Our Lady of Mt. Carmel Medical Center • Clark • Tiglao Medical Center Foundation
                </div>
            </div>

            <!-- Patient Metadata -->
            <div class="patient-meta-container">
                <div class="patient-meta-row">
                    <div class="meta-field patient-name">
                        <strong>Patient:</strong> 
                        <span class="meta-line" contenteditable="true">
                            {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}
                        </span>
                    </div>
                    <div class="meta-field patient-date">
                        <strong>Date:</strong> 
                        <span class="meta-line" style="min-width: 100px; text-align: center;" contenteditable="true">
                            {{ $prescription->created_at->format('F d, Y') }}
                        </span>
                    </div>
                </div>
                <div class="patient-meta-row">
                    <div class="meta-field patient-address">
                        <strong>Address:</strong> 
                        <span class="meta-line" contenteditable="true">
                            {{ $prescription->patient->address ?? '' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Rx Symbol -->
            <div class="rx-badge">Rx</div>

            <!-- Optional Diagnosis Section -->
            @if(!empty($prescription->diagnosis))
            <div class="diagnosis-box" contenteditable="true">
                <strong>Clinical Diagnosis:</strong> {{ $prescription->diagnosis }}
            </div>
            @endif

            <!-- Prescribed Medications Table -->
            <table class="medicine-table">
                <thead>
                    <tr>
                        <th style="width: 30%">Medicine</th>
                        <th style="width: 15%">Dosage</th>
                        <th style="width: 20%">Frequency</th>
                        <th style="width: 15%">Duration</th>
                        <th style="width: 20%">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescription->items as $item)
                        <tr>
                            <td class="med-name" contenteditable="true">{{ $item->medicine->name }}</td>
                            <td contenteditable="true">{{ $item->dosage }}</td>
                            <td contenteditable="true">{{ $item->frequency }}</td>
                            <td contenteditable="true">{{ $item->duration }}</td>
                            <td class="med-instructions" contenteditable="true">
                                {{ $item->instructions ?: 'Take as directed' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Footer & Signature Block -->
            <div class="footer-grid">
                <div class="footer-left">
                    <div contenteditable="true">
                        <strong>Your next appointment is on:</strong><br>
                        <span class="line-fill" style="text-align: center; margin-top: 4px;">
                            @if(!empty($prescription->next_appointment_date))
                                {{ \Carbon\Carbon::parse($prescription->next_appointment_date)->format('m/d/Y') }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="footer-right">
                    <div class="doc-signature-block">
                        <div class="doc-name-signature" contenteditable="true">PAUL ANTHONY BANSIL, M.D.</div>
                        <div class="doc-credential-line" contenteditable="true">
                            Lic. No. <span class="line-fill">__________________</span>
                        </div>
                        <div class="doc-credential-line" contenteditable="true">
                            PTR No. <span class="line-fill">__________________</span>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.content-wrap -->
    </div>

</body>
</html>

@else

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription #{{ $prescription->id }}</title>

    <style>
        /* Base Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 20px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            width: 750px;
            margin: 0 auto;
            padding: 40px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        /* Editable visual cues on web preview */
        [contenteditable="true"] {
            outline: 1px dashed transparent;
            transition: all 0.2s ease;
            border-radius: 4px;
            padding: 1px 4px;
        }

        [contenteditable="true"]:hover {
            outline: 1px dashed #cbd5e1;
            background-color: rgba(241, 245, 249, 0.5);
        }

        [contenteditable="true"]:focus {
            outline: 2px solid #3b82f6;
            background-color: #ffffff;
        }

        /* Clinic Header Layout with Left Logo */
        .header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 20px;
            margin-bottom: 24px;
        }

        .logo-box {
            flex-shrink: 0;
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clinic-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .clinic-details {
            text-align: left;
        }

        .clinic-name-container {
            display: inline-block;
            text-transform: uppercase;
        }

        .clinic-name-top {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 6.5px;
            color: #0b6b88;
            line-height: 1;
            padding-left: 2px;
        }

        .clinic-name-divider {
            height: 1.5px;
            background: linear-gradient(to right, #94a3b8, #cbd5e1, #94a3b8);
            margin: 3px 0 2px 0;
            width: 100%;
        }

        .clinic-name-bottom {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #06485d;
            line-height: 0.95;
        }

        .clinic-info {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
            line-height: 1.5;
        }

        .divider {
            border-bottom: 2px solid #e2e8f0;
            margin: 20px 0 24px 0;
        }

        /* Rx Symbol */
        .rx-badge {
            font-size: 32px;
            font-family: "Times New Roman", Times, serif;
            font-weight: bold;
            font-style: italic;
            color: #06485d;
            line-height: 1;
            margin-bottom: 15px;
        }

        /* Meta Grid Layout */
        .meta-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .meta-col {
            flex: 1;
        }

        .row {
            margin-bottom: 8px;
            font-size: 13px;
            color: #334155;
        }

        .label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            display: inline-block;
            width: 120px;
        }

        .value {
            font-weight: 600;
            color: #1e293b;
        }

        /* Sections */
        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #0b6b88;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .diagnosis-text {
            font-size: 14px;
            color: #334155;
            line-height: 1.6;
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        /* Table Layout */
        .medicine-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .medicine-table th {
            background: #f8fafc;
            color: #06485d;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
            text-align: left;
        }

        .medicine-table td {
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 16px;
            font-size: 13px;
            color: #334155;
            font-weight: 500;
        }

        .med-name {
            font-weight: 700;
            color: #1e293b;
        }

        .med-instructions {
            font-style: italic;
            color: #64748b;
            font-size: 12px;
        }

        /* Follow-up Section */
        .follow-up-section {
            margin-top: 40px;
            font-size: 13px;
            color: #334155;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 10px;
            border-left: 4px solid #0b6b88;
            display: inline-block;
        }

        .next-appt-line {
            font-weight: 600;
        }

        .next-appt-date {
            color: #06485d;
            font-weight: 700;
        }

        /* Signature Section */
        .signature-container {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            text-align: center;
        }

        .signature-box {
            width: 250px;
        }

        .signature-line {
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 8px;
            height: 40px;
        }

        .doctor-name {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .doctor-sub {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            color: #94a3b8;
            letter-spacing: 0.3px;
        }

        /* Action Toolbar */
        .print-btn-bar {
            max-width: 830px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-hint {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .btn-premium {
            cursor: pointer;
            padding: 10px 20px;
            background: #06485d;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-premium:hover {
            background: #0b6b88;
            transform: translateY(-1px);
        }

        /* Print Media Settings */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .print-btn-bar {
                display: none !important;
            }

            .container {
                width: 100%;
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            [contenteditable="true"] {
                outline: none !important;
                background-color: transparent !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>

    <!-- Top Toolbar -->
    <div class="print-btn-bar">
        <span class="edit-hint">✏️ Tip: Click on any text inside the prescription to edit before printing.</span>
        <button onclick="window.print()" class="btn-premium">
            Print Prescription
        </button>
    </div>

    <div class="container">

        <!-- Header Block with Left Logo -->
        <div class="header">
            <div class="logo-box">
                <img src="{{ asset('Image/image-removebg-preview.png') }}" alt="Clinica Bansil Logo" class="clinic-logo-img">
            </div>
            <div class="clinic-details">
                <div class="clinic-name-container" contenteditable="true">
                    <div class="clinic-name-top">CLINICA</div>
                    <div class="clinic-name-divider"></div>
                    <div class="clinic-name-bottom">BANSIL</div>
                </div>
                <div class="clinic-info" contenteditable="true">
                    Pampang, Angeles City, Philippines <br>
                    
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Metadata Section -->
        <div class="meta-grid">
            <div class="meta-col">
                <div class="row">
                    <span class="label">Prescription No:</span>
                    <span class="value font-mono" contenteditable="true">#{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="row">
                    <span class="label">Date Issued:</span>
                    <span class="value" contenteditable="true">{{ $prescription->created_at->format('F d, Y') }}</span>
                </div>
            </div>
            <div class="meta-col" style="padding-left: 40px;">
                <div class="row">
                    <span class="label">Patient Name:</span>
                    <span class="value" contenteditable="true">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</span>
                </div>
                <div class="row">
                    <span class="label">Patient ID:</span>
                    <span class="value font-mono" contenteditable="true">#{{ $prescription->patient->id }}</span>
                </div>
            </div>
        </div>

        <!-- Diagnosis Section -->
        <div class="section">
            <div class="section-title">Clinical Diagnosis</div>
            <div class="diagnosis-text" contenteditable="true" title="Click to edit diagnosis details">
                {{ $prescription->diagnosis }}
            </div>
        </div>

        <!-- Medications Section -->
        <div class="section">
            <div class="rx-badge">Rx</div>
            <div class="section-title">Prescribed Medications</div>

            <table class="medicine-table">
                <thead>
                    <tr>
                        <th style="width: 30%">Medicine</th>
                        <th style="width: 15%">Dosage</th>
                        <th style="width: 20%">Frequency</th>
                        <th style="width: 15%">Duration</th>
                        <th style="width: 20%">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescription->items as $item)
                        <tr>
                            <td class="med-name" contenteditable="true">{{ $item->medicine->name }}</td>
                            <td contenteditable="true">{{ $item->dosage }}</td>
                            <td contenteditable="true">{{ $item->frequency }}</td>
                            <td contenteditable="true">{{ $item->duration }}</td>
                            <td class="med-instructions" contenteditable="true">
                                {{ $item->instructions ?: 'Take as directed' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Follow-up Section -->
        <div class="follow-up-section">
            <div class="next-appt-line" contenteditable="true">
                <strong>Your Next Appointment is on:</strong> 
                <span class="next-appt-date">
                    @if(!empty($prescription->next_appointment_date))
                        {{ \Carbon\Carbon::parse($prescription->next_appointment_date)->format('F d, Y') }}
                    @else
                        ________________________
                    @endif
                </span>
            </div>
        </div>

        <!-- Doctor Signature Area -->
        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="doctor-name" contenteditable="true" title="Click to edit Doctor's name">
                    @if(\Illuminate\Support\Str::startsWith(auth()->user()->name ?? '', 'Dr.'))
                        {{ auth()->user()->name }}
                    @else
                        Dr. {{ auth()->user()->name ?? 'Doctor Name' }}
                    @endif
                </div>
                <div class="doctor-sub" contenteditable="true" title="Click to edit designation">
                    {{ auth()->user()->specialization ?? auth()->user()->role ?? 'Licensed Physician' }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer" contenteditable="true">
            This document is an official medical prescription. It is valid only when stamped and signed by a licensed medical practitioner.
        </div>

    </div>

</body>
</html>
@endif