<?php
/**
 * Currency Helper Functions
 */

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency
     *
     * @param float $amount The amount to format
     * @param bool $withSymbol Whether to include the currency symbol
     * @return string Formatted currency string
     */
    function format_currency($amount, $withSymbol = true) {
        $amount = (float) $amount;
        $formatted = number_format($amount, 2, '.', ',');
        
        if ($withSymbol) {
            return CURRENCY_SYMBOL . $formatted;
        }
        
        return $formatted;
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * Get the currency symbol
     * 
     * @return string Currency symbol
     */
    function get_currency_symbol() {
        return CURRENCY_SYMBOL;
    }
}
