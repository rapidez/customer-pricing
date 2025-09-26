<?php

namespace Rapidez\CustomerPricing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CustomerPricingController
{
    /** @return Collection<string, array<string, mixed>> */
    public function getCustomerPrices(Request $request): Collection
    {
        $validated = $request->validate([
            'products' => 'array|required',
        ]);
        $productIds = $validated['products'];
        $productModel = config('rapidez.models.product');

        /** @var int $customerId */
        $customerId = auth()->id();

        $prices = $productModel::withoutGlobalScopes()
            ->with('customerPricing')
            ->find($productIds)
            ->mapWithKeys(fn ($product) => [$product->entity_id => $product->customerTierPrices($customerId)])
            ->whereNotNull();

        return collect($productIds)->mapWithKeys(fn ($id) => [$id => $prices[$id] ?? null]);
    }
}