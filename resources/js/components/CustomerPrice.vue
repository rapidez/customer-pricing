<script>
import { asyncComputed } from '@vueuse/core';
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
        return this.$scopedSlots.default(this)
    },

    data() {
        return {
            customerPrices: asyncComputed(async () => await getPriceForProduct(this.productId)),
            customerPrice: null,
        }
    },

    watch: {
        customerPrices() {
            if (!this.customerPrices) {
                return
            }
            
            this.customerPrice = this.customerPrices
                .filter(tier => tier.quantity <= this.quantity)
                .toSorted((a, b) => a.price - b.price)
                .at(0)?.price ?? null
        }
    }
}
</script>