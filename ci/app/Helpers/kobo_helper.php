<?php

namespace App\Helpers;

if (!function_exists('format_amount')) {
    function format_amount(float $amount): string
    {
        return '₦' . number_format($amount, 2);
    }
}

if (!function_exists('format_date')) {
    function format_date($date, string $format = 'M d, Y h:i A'): string
    {
        if (empty($date)) return 'N/A';
        $dt = new \DateTime($date);
        return $dt->format($format);
    }
}

if (!function_exists('truncate_text')) {
    function truncate_text(string $text, int $length = 50): string
    {
        if (strlen($text) <= $length) return $text;
        return substr($text, 0, $length) . '...';
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'success' => 'bg-green-100 text-green-800',
            'failed'  => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            'active'  => 'bg-green-100 text-green-800',
            'inactive'=> 'bg-red-100 text-red-800',
        ];
        $color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $color . '">' . ucfirst($status) . '</span>';
    }
}
