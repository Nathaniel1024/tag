<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $payload['subject'] ?? 'Barangay Request Update' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
  <div style="text-align:center;margin:0 0 18px;">
    <img src="{{ $message->embed(public_path('img/logo_zed.png')) }}" alt="DIGIBARANGAY logo" style="width:72px;height:72px;object-fit:contain;display:block;margin:0 auto 10px;" />
    <div style="font-size:18px;font-weight:700;letter-spacing:.08em;">DIGIBARANGAY</div>
    <div style="font-size:12px;color:#6b7280;">Smart Clearance System</div>
  </div>

  <p>Hello {{ $payload['name'] ?? 'Resident' }},</p>

  <p>{{ $payload['message'] ?? 'Your certificate is ready. Please claim it at the barangay office.' }}</p>

  <p>
    <strong>Reference:</strong> {{ $payload['ref'] ?? '' }}<br />
    <strong>Purpose:</strong> {{ $payload['purpose'] ?? '' }}<br />
    <strong>Reason:</strong> {{ $payload['reason'] ?? '' }}<br />
    <strong>Date:</strong> {{ $payload['date'] ?? '' }}
  </p>

  <p>Thank you.<br />Barangay Admin</p>
</body>
</html>
