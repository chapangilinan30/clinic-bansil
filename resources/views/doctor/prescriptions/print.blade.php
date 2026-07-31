<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription #{{ $prescription->id }}</title>

    <style>
        /* Base Reset */
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b; /* Slate-800 */
            margin: 0;
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
            background-color: #f1f5f9/50;
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

        /* Custom Two-Tier Logo Text Styling & Color Matched */
        .clinic-name-container {
            display: inline-block;
            text-transform: uppercase;
        }

        .clinic-name-top {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 6.5px;
            color: #0b6b88; /* Exact teal color from CLINICA */
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
            color: #06485d; /* Exact deep teal color from BANSIL */
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
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .meta-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
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

    <!-- Top Toolbar for Edit Notice & Print Button -->
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
                    Contact: 09XX-XXX-XXXX &bull; License No. PR-XXXXX
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
                                {{ $item->instructions ? $item->instructions : 'Take as directed' }}
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