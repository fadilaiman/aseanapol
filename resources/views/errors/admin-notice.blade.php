<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $title }}</title>
</head>
<body style="font-family:system-ui,sans-serif;max-width:560px;margin:12vh auto;padding:0 16px;color:#1f2937">
    <div role="alert" data-testid="admin-notice" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:18px;border-radius:8px">
        <strong style="font-size:1.1rem">{{ $heading }}</strong>
        <p style="margin:.6rem 0 0;line-height:1.5">{{ $body }}</p>
    </div>
    <p style="margin-top:20px;display:flex;gap:16px;flex-wrap:wrap">
        <a href="javascript:history.back()" data-testid="notice-back">&larr; Go back to the form</a>
        @if($login)
            <a href="{{ url('/admin/login') }}" data-testid="notice-login" style="font-weight:600">Sign in again</a>
        @else
            <a href="{{ url('/') }}">Home</a>
        @endif
    </p>
</body>
</html>
