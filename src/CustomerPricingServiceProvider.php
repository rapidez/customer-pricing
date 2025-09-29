<?php

namespace Rapidez\CustomerPricing;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Rapidez\Core\Models\Product;
use Rapidez\CustomerPricing\Http\ViewComposers\ConfigComposer;
use Rapidez\CustomerPricing\Models\CustomerPricing;

class CustomerPricingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/rapidez/customerpricing.php', 'rapidez.customerpricing');
    }

    public function boot()
    {
        $this
            ->bootComposers()
            ->bootPublishables()
            ->bootRelations()
            ->bootRoutes();
    }

    protected function bootComposers(): static
    {
        View::composer('rapidez::layouts.app', ConfigComposer::class);

        return $this;
    }

    protected function bootPublishables(): static
    {
        $this->publishes([
            __DIR__.'/../config/rapidez/customerpricing.php' => config_path('rapidez/customerpricing.php'),
        ], 'customer-pricing-config');

        return $this;
    }

    protected function bootRelations(): static
    {
        Product::resolveRelationUsing('customerPricing', function(Product $productModel) {
            return $productModel->hasMany(CustomerPricing::class, 'product_id');
        });

        Product::macro('customerPrice', function (int $customerId, int $quantity = 1) {
            $customerPrice = $this->customerPricing()
                ->where('customer_id', $customerId)
                ->where('quantity', '<=', $quantity)
                ->orderBy('quantity', 'desc')
                ->first();

            return $customerPrice->price ?? null;
        });

        Product::macro('customerTierPrices', function (int $customerId) {
            return $this->customerPricing()
                ->where('customer_id', $customerId)
                ->orderBy('quantity')
                ->get();
        });

        return $this;
    }

    protected function bootRoutes(): static
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        return $this;
    }
}
