<?php

namespace Rapidez\CustomerPricing\Http\ViewComposers;

use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class ConfigComposer
{
    public function compose(View $view)
    {
        Config::set('frontend.customerpricing', [
            'max_products' => config('rapidez.customerpricing.max_products'),
        ]);
    }
}
