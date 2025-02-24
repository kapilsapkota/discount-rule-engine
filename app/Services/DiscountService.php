<?php

namespace App\Services;

use App\Models\Discount;
use Carbon\Carbon;

class DiscountService
{
    public function applyDiscounts(array $cart): array
    {
        $originalSubtotal = $cart['subtotal'];
        $now = Carbon::now();

        $discounts = Discount::with('categories')
            ->active()
            ->get();

        $totalDiscount = 0;
        $appliedDiscounts = [];
        $discountedItems = [];

        foreach ($cart['items'] as &$item) {
            $itemDiscount = 0;
            $itemAppliedDiscounts = [];

            foreach ($discounts as $discount) {
                if (!$this->isCartEligible($discount, $originalSubtotal)) {
                    continue;
                }

                if ($discount->categories->isNotEmpty()) {
                    $categoryMatch = isset($item['category_id']) &&
                        $discount->categories->contains('id', $item['category_id']);

                    if ($categoryMatch) {
                        $discountAmount = $this->calculateItemDiscount($discount, $item['price']);

                        $itemDiscount += $discountAmount;
                        $totalDiscount += $discountAmount;

                        $itemAppliedDiscounts[] = [
                            'discount_id' => $discount->id,
                            'type' => $discount->type,
                            'amount' => $discount->amount,
                            'discount_amount' => $discountAmount,
                        ];
                    }
                }
            }

            $item['discount'] = $itemDiscount;
            $item['final_price'] = max($item['price'] - $itemDiscount, 0);
            $item['applied_discounts'] = $itemAppliedDiscounts;
            $discountedItems[] = $item;
        }

        $cartAppliedDiscounts = [];
        foreach ($discounts as $discount) {
            if ($discount->categories->isNotEmpty() || !$this->isCartEligible($discount, $originalSubtotal)) {
                continue;
            }

            $discountAmount = $this->calculateCartDiscount($discount, $originalSubtotal);

            $totalDiscount += $discountAmount;
            $cartAppliedDiscounts[] = [
                'discount_id' => $discount->id,
                'type' => $discount->type,
                'amount' => $discount->amount,
                'discount_amount' => $discountAmount,
            ];
        }

        return [
            'original_subtotal' => $originalSubtotal,
            'adjusted_total' => max($originalSubtotal - $totalDiscount, 0),
            'total_discount' => $totalDiscount,
            'items' => $discountedItems,
            'cart_level_discounts' => $cartAppliedDiscounts,
        ];
    }

    private function isCartEligible(Discount $discount, float $cartSubtotal): bool
    {
        return is_null($discount->min_cart_total) || $cartSubtotal >= $discount->min_cart_total;
    }

    private function calculateItemDiscount(Discount $discount, float $itemPrice): float
    {
        return match ($discount->type) {
            'percentage' => $itemPrice * ($discount->amount / 100),
            'fixed' => min($discount->amount, $itemPrice),
            default => 0,
        };
    }

    private function calculateCartDiscount(Discount $discount, float $cartSubtotal): float
    {
        return match ($discount->type) {
            'percentage' => $cartSubtotal * ($discount->amount / 100),
            'fixed' => min($discount->amount, $cartSubtotal),
            default => 0,
        };
    }
}
