<?php

namespace Gloudemans\Shoppingcart\Calculation;

use Gloudemans\Shoppingcart\CartItem;
use Gloudemans\Shoppingcart\Contracts\Calculator;

class DefaultCalculator implements Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem)
    {
        $decimals = config('cart.format.decimals', 2);

        switch ($attribute) {
            case 'discount':
                return $cartItem->price * ($cartItem->getDiscountRate() / 100);
            case 'fees':
                return $cartItem->fees;
            case 'feesTotal':
                return round($cartItem->fees * $cartItem->qty, $decimals);
            case 'tax':
                return round(($cartItem->priceTarget + $cartItem->feesTotal) * ($cartItem->taxRate / 100), $decimals);
            case 'priceTax':
                return round($cartItem->priceTarget + $cartItem->feesTotal + $cartItem->tax, $decimals);
            case 'discountTotal':
                return round($cartItem->discount * $cartItem->qty, $decimals);
            case 'priceTotal':
                return round($cartItem->price * $cartItem->qty, $decimals);
            case 'subtotal':
                return max(round($cartItem->priceTotal - $cartItem->discountTotal, $decimals), 0);
            case 'priceTarget':
                return round(($cartItem->priceTotal - $cartItem->discountTotal) / $cartItem->qty, $decimals);
            case 'taxTotal':
                return round(($cartItem->subtotal + $cartItem->feesTotal) * ($cartItem->taxRate / 100), $decimals);
            case 'total':
                return round($cartItem->subtotal + $cartItem->feesTotal + $cartItem->taxTotal, $decimals);
            default:
                return;
        }
    }
}
