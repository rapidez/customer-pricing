<script>
import { getPriceForProduct } from '../stores/useCustomerPrices'

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
            customerPrices: null,
        }
    },

    watch: {
        productId: {
            async handler(productId) {
                this.customerPrices = await getPriceForProduct(productId)
            },
            immediate: true,
        },
    },

    computed: {
        customerPrice() {
            return this.customerPrices
                ?.filter(tier => tier.quantity <= this.quantity)
                .toSorted((a, b) => b.quantity - a.quantity)
                .at(0)?.price ?? null
        },
    },
}
</script>
