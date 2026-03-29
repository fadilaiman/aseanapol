<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #1a1a2e; line-height: 1.6; }
        .header { background: #1a1a2e; color: white; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #a0aec0; }
        .content { padding: 32px; }
        .field { margin-bottom: 20px; }
        .label { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #718096; letter-spacing: 0.05em; margin-bottom: 4px; }
        .value { font-size: 15px; color: #1a202c; }
        .message-box { background: #f7fafc; border-left: 4px solid #c9a84c; padding: 16px 20px; border-radius: 0 8px 8px 0; white-space: pre-wrap; font-size: 15px; color: #1a202c; }
        .footer { border-top: 1px solid #e2e8f0; padding: 20px 32px; font-size: 12px; color: #a0aec0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ASEANAPOL — New Inquiry</h1>
        <p>Submitted via aseanapol.org contact form</p>
    </div>
    <div class="content">
        <div class="field">
            <div class="label">From</div>
            <div class="value">{{ $senderName }} &lt;{{ $senderEmail }}&gt;</div>
        </div>
        <div class="field">
            <div class="label">Subject</div>
            <div class="value">{{ $inquirySubject }}</div>
        </div>
        <div class="field">
            <div class="label">Message</div>
            <div class="message-box">{{ $messageBody }}</div>
        </div>
    </div>
    <div class="footer">
        This email was sent from the contact form on the ASEANAPOL website. Reply directly to respond to {{ $senderName }}.
    </div>
</body>
</html>
