<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva confirmada</title>
</head>
<body style="margin:0; padding:0; background-color:#F5F0E8; font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#F5F0E8; padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

                    {{-- Cabecera --}}
                    <tr>
                        <td style="background-color:#1C1410; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center;">
                            <p style="margin:0; color:#C9974A; font-size:11px; font-weight:600; letter-spacing:3px; text-transform:uppercase;">
                                Biblioteca de Alejandría
                            </p>
                            <h1 style="margin:8px 0 0; color:#FAF6EE; font-size:22px; font-weight:700; letter-spacing:-0.3px;">
                                Reserva confirmada
                            </h1>
                        </td>
                    </tr>

                    {{-- Cuerpo principal --}}
                    <tr>
                        <td style="background-color:#FEFDF9; padding:32px;">

                            {{-- Saludo --}}
                            <p style="margin:0 0 20px; color:#3C2E22; font-size:15px; line-height:1.5;">
                                Hola, <strong>{{ $loan->user->first_name }}</strong>:
                            </p>
                            <p style="margin:0 0 24px; color:#5C4A38; font-size:14px; line-height:1.6;">
                                Tu reserva ha sido registrada exitosamente. Presenta el código QR de abajo
                                en el mostrador de la biblioteca para retirar tu libro.
                            </p>

                            {{-- Tarjeta del libro --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background-color:#FAF6EE; border:1px solid #E4D9C8; border-radius:10px; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#8B7355; letter-spacing:1.5px; text-transform:uppercase;">
                                            Libro reservado
                                        </p>
                                        <p style="margin:0 0 16px; font-size:17px; font-weight:700; color:#1C1410; line-height:1.3;">
                                            {{ $loan->book->title }}
                                        </p>

                                        @if($loan->book->authors->isNotEmpty())
                                        <p style="margin:0 0 16px; font-size:13px; color:#5C4A38;">
                                            por {{ $loan->book->authors->pluck('full_name')->join(', ') }}
                                        </p>
                                        @endif

                                        {{-- Detalles de la reserva --}}
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-right:32px; padding-bottom:8px;">
                                                    <p style="margin:0; font-size:11px; color:#8B7355; text-transform:uppercase; letter-spacing:1px;">
                                                        Fecha de reserva
                                                    </p>
                                                    <p style="margin:4px 0 0; font-size:14px; font-weight:600; color:#3C2E22;">
                                                        {{ $loan->loan_date->format('d/m/Y') }}
                                                    </p>
                                                </td>
                                                <td style="padding-bottom:8px;">
                                                    <p style="margin:0; font-size:11px; color:#8B7355; text-transform:uppercase; letter-spacing:1px;">
                                                        Estado
                                                    </p>
                                                    <p style="margin:4px 0 0; font-size:14px; font-weight:600; color:#B45309;">
                                                        Pendiente de retiro
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- QR --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background-color:#FFFFFF; border:1px solid #E4D9C8; border-radius:10px; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:24px; text-align:center;">
                                        <p style="margin:0 0 16px; font-size:11px; font-weight:600; color:#8B7355; letter-spacing:1.5px; text-transform:uppercase;">
                                            Tu código QR de retiro
                                        </p>
                                        {{-- QR generado via API externa gratuita (PNG, sin extensión imagick) --}}
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=8&data={{ urlencode(route('admin.loans.show', $loan)) }}"
                                             alt="Código QR — Préstamo #{{ $loan->id }}"
                                             width="180" height="180"
                                             style="display:block; margin:0 auto; border-radius:6px;">
                                        <p style="margin:14px 0 0; font-size:12px; color:#8B7355;">
                                            Préstamo #{{ $loan->id }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Instrucciones --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background-color:#FFF8E7; border:1px solid #E8C97A; border-left:3px solid #C9974A; border-radius:0 8px 8px 0; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p style="margin:0; font-size:13px; color:#7C5C1A; line-height:1.5;">
                                            <strong>¿Cómo retirar tu libro?</strong><br>
                                            Preséntate en la biblioteca y muestra este QR al personal.
                                            El código es único e intransferible para este préstamo.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('profile') }}"
                                           style="display:inline-block; background-color:#C9974A; color:#1C1410; font-size:14px; font-weight:700;
                                                  text-decoration:none; padding:12px 28px; border-radius:8px; letter-spacing:0.3px;">
                                            Ver mis reservas en el portal
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Pie de página --}}
                    <tr>
                        <td style="background-color:#EDE5D5; border-radius:0 0 12px 12px; padding:20px 32px; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#8B7355; line-height:1.5;">
                                Este correo fue enviado automáticamente por el sistema de
                                <strong>Biblioteca de Alejandría</strong>.<br>
                                Universidad UPED — Programación Aplicada 1
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
