<?php

use App\Models\Discount;
use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->electronicsCategory = Category::create(['name' => 'Electronics']);
    $this->clothingCategory = Category::create(['name' => 'Clothing']);

    $electronicsDiscount = Discount::create([
        'type' => 'percentage',
        'amount' => 10,
        'min_cart_total' => 50,
    ]);
    $electronicsDiscount->categories()->attach($this->electronicsCategory->id);

    $fixedDiscount = Discount::create([
        'type' => 'fixed',
        'amount' => 5,
        'categories' => [],
    ]);
});

it('applies discounts correctly...', function () {

    $cart = [
        'subtotal' => 100,
        'items' => [
            ['category_id' => $this->electronicsCategory->id,  'price' => 50],
            ['price' => 50],
        ],
    ];

    $response = $this->postJson(route('apply.discounts'), $cart);

    $response->assertStatus(Response::HTTP_OK);

    $response->assertJsonStructure([
        'original_subtotal',
        'adjusted_total',
        'total_discount',
        'items' => [
            '*' => [
                'price',
                'discount',
                'final_price',
                'applied_discounts',
            ],
        ],
        'cart_level_discounts' => [
            '*' => [
                'discount_id',
                'type',
                'amount',
                'discount_amount'
            ]
        ],
    ]);
    $response->assertJson([
        'adjusted_total' => 90,
        'total_discount' => 10,
    ]);

});
