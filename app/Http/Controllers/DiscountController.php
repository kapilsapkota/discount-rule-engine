<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyDiscountRequest;
use App\Http\Requests\StoreDiscountRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
    {
        $categories = Category::select('id','name')->get();
        return view('discounts.create', compact('categories'));
    }

    public function store(StoreDiscountRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $discount = Discount::create($validated);
        if ($request->has('categories')) {
            $discount->categories()->sync($validated['categories']);
        }
        return redirect()->route('discounts.index')->with('success', 'Discount created!');
    }

    public function edit(Discount $discount): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $categories = Category::select('id','name')->get();
        return view('discounts.create', compact('categories', 'discount'));
    }

    public function update(StoreDiscountRequest $request, Discount $discount): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $discount->update($validated);

        if ($request->has('categories')) {
            $discount->categories()->sync($validated['categories']);
        }
        return redirect()->route('discounts.index')->with('success', 'Discount updated!');
    }

    public function destroy(Discount $discount): \Illuminate\Http\RedirectResponse
    {
        if($discount->categories){
            $discount->categories()->sync([]);
        }
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted!');
    }

    public function applyDiscounts(ApplyDiscountRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $service = new DiscountService();
        $discountedCart = $service->applyDiscounts($validated);

        return response()->json($discountedCart);
    }
}
