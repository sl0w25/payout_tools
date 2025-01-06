<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECT Beneficiaries</title>
    <style>
        /* Set the paper size (CR80) */
        @page {
            size: 85.60mm 54.00mm; /* CR80 dimensions */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 85.60mm;
            height: 54.00mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* Set the size of the QR code container */
        .qr-container {
            width: 323.57px;  /* 85.60mm converted to pixels (CR80 width) */
            height: 204.12px; /* 54.00mm converted to pixels (CR80 height) */
            margin-top: 1px;
            position: absolute;
            margin-left: 75;
        }

        /* Ensure content fits within the page */
        h4, h3 {
            margin: 0;
        }
        
        h5 {
            margin-top: -10;

        }

        p {
            font-size: 12px;
            line-height: 1.2;
            margin-bottom: 10px;
            margin-top: 1px;
        }

    </style>
</head>
<body>
    <div>
        <h4>ECT Beneficiaries</h4>
        <p>{{ $individual->first_name }} @if($individual->middle_name)
            {{ Str::substr($individual->middle_name, 0, 1,) }}.
            @endif{{ $individual->last_name }}</p>

        <h5>QR Code</h5>

        <div class="qr-container">
            {!! DNS2D::getBarcodeHTML("$individual->qr_number", 'QRCODE', 6, 5) !!} <!-- You can adjust the width/height here -->
        </div>
    </div>
</body>
</html>
