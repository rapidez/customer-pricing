<script>
import { computedAsync } from '@vueuse/core';
import { getPriceForProduct } from '../stores/useCustomerPrices';

export default {
    props: {
        productId: Number,
        quantity: {
            type: Number,
            default: 1,
        }
    },

    render() {
        return this?.$slots?.default?.(this)
    },

    data() {
        return {
            customerPrices: computedAsync(async () => await getPriceForProduct(this.productId)),
            customerPrice: null,
        }
    },

    watch: {
        customerPrices() {
            if (!this.customerPrices?.value) {
                return
            }

            this.customerPrice = this.customerPrices.value
                .filter(tier => tier.quantity <= this.quantity)
                .toSorted((a, b) => a.price - b.price)
                .at(0)?.price ?? null
        }
    }
}
</script>
