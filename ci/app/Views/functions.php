<?php
if (!function_exists('status_badge')) {
    function status_badge($status) {
        $classes = [
            'success' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed'  => 'bg-red-100 text-red-800',
        ];
        $class = $classes[strtolower($status)] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $class . '">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'M d, Y') {
        if (!$date) return 'N/A';
        return date($format, strtotime($date));
    }
}

if (!function_exists('format_amount')) {
    function format_amount($amount) {
        return '₦' . number_format($amount, 2);
    }
}
?>