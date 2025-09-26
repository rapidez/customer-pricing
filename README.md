# Rapidez Customer Pricing

This package adds Rapidez support for the [justbetter/magento2-customer-pricing magento extension](https://github.com/justbetter/magento2-customer-pricing). **This package will not work without this extension.**

## Installation

```
composer require rapidez/customer-pricing
```

## Usage

This adds to the Product model:
- A `customerPricing` relation that retrieves all customer prices and tiers for the given product
- The `customerPrice(int $customerId, int $quantity = 1)` function which returns a customer price at the given tier, or null when none is found.
- The `customerTierPrices(int $customerId)` function which returns all of the tier prices for a given customer (if any).

This package also adds a simple frontend implementation that can be used by adding the `customer-price` component onto your page:

```blade
<customer-price
    v-bind:product-id="addToCart.simpleProduct.entity_id"
    v-bind:quantity="addToCart.qty"
    v-slot="{ customerPrice }"
>
    <span>@{{ customerPrice || addToCart.price }}</span>
</customer-price>
``

## License

GNU General Public License v3. Please see [License File](LICENSE) for more information.
