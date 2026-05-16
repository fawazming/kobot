<?php

namespace App\Libraries;

class PaymentGateway
{
    public static function generateTransactionId(): string
    {
        $prefix = 'TXN_';
        $random = strtoupper(bin2hex(random_bytes(6)));
        return $prefix . $random;
    }

    public static function generateRegistrationId(): string
    {
        $prefix = 'REG_';
        $random = strtoupper(bin2hex(random_bytes(6)));
        return $prefix . $random;
    }

    public static function generateBusinessId(): string
    {
        $prefix = 'BUS_';
        $random = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    public static function generateApiKey(string $prefix = 'pk'): string
    {
        $random = bin2hex(random_bytes(24));
        return "{$prefix}_live_" . $random;
    }

    public static function generateSecretKey(): string
    {
        return self::generateApiKey('sk');
    }

    public static function generateWebhookSecret(): string
    {
        return 'whsec_' . bin2hex(random_bytes(24));
    }

    public static function calculatePayableAmount(float $amount): array
    {
        $nairaDeduction = 100;
        $remainingKobo = $amount - $nairaDeduction;

        if ($remainingKobo <= 0) {
            return [
                'original_amount' => $amount,
                'payable_amount' => 0,
                'kobo_value' => 0,
            ];
        }

        $koboValue = random_int(1, 99);
        $payableAmount = $remainingKobo + ($koboValue / 100);

        return [
            'original_amount' => $amount,
            'payable_amount' => round($payableAmount, 2),
            'kobo_value' => $koboValue,
        ];
    }

    public static function verifyWebhookSignature(string $payload, string $signature, string $webhookSecret): bool
    {
        $computedSignature = hash_hmac('sha256', $payload, $webhookSecret);
        return hash_equals($computedSignature, $signature);
    }
}
