<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #15803d; padding: 32px; text-align: center; }
        .header-logo { font-size: 48px; margin-bottom: 8px; }
        .header-title { color: white; font-size: 24px; font-weight: bold; margin: 0; }
        .header-sub { color: #bbf7d0; font-size: 14px; margin-top: 4px; }
        .content { padding: 32px 40px; color: #374151; font-size: 15px; line-height: 1.8; }
        .content p { margin: 0 0 16px 0; }
        .url-label { font-size: 13px; color: #6b7280; margin-top: 24px; }
        .url-box { background: #f3f4f6; border-radius: 8px; padding: 12px 16px; font-size: 11px; color: #6b7280; word-break: break-all; margin-top: 8px; }
        .footer { background: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer-text { font-size: 12px; color: #9ca3af; margin: 2px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">⚽</div>
            <p class="header-title">MatchDay</p>
            <p class="header-sub">Sistema de Gestión de Torneos</p>
        </div>

        <div class="content">
            {{ Illuminate\Mail\Markdown::parse($slot) }}

            @isset($actionText)
                <p class="url-label">Si el botón no funciona, copia este enlace:</p>
                <div class="url-box">{{ $actionUrl }}</div>
            @endisset

            @isset($salutation)
                <p style="margin-top: 24px; color: #6b7280;">{{ $salutation }}</p>
            @endisset
        </div>

        <div class="footer">
            <p class="footer-text">MatchDay · Sistema de Gestión de Torneos</p>
            <p class="footer-text">Copa MatchDay 2026 · Desarrollo Web Avanzado · UASLP</p>
        </div>
    </div>
</body>
</html>