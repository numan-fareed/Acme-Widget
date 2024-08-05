<?php

// Calculate the offer for red widgets (product code 'R01')
// If more than one 'R01' is in the cart, apply a 50% discount on one 'R01'
function redWidgetOffer($item, $products)
{
    if ($item['quantity'] > 1) {
        return ($products['R01']['price'] / 2); // Return half the price of 'R01'
    }
    return 0; // No discount if quantity is 1 or less
}

// Format the price according to the specified currency
function formatPrice($amount, $curr = 'usd')
{
    $ret = '';
    $curr = strtolower($curr); // Convert currency code to lowercase for consistency
    switch ($curr) {
        case 'usd':
            $ret = '$' . number_format($amount, 2); // Format price in USD
            break;

        case 'gbp':
            $ret = '£' . number_format($amount, 2); // Format price in GBP
            break;
        case 'eur':
            $ret = '€' . number_format($amount, 2); // Format price in EUR
            break;

        default:
            $ret = '$' . number_format($amount, 2); // Default to USD if currency is not recognized
            break;
    }

    return $ret; // Return the formatted price
}
