import 'Vendor/rapidez/core/resources/js/vue'
import { clearPrices } from './stores/useCustomerPrices'
import CustomerPrice from './components/CustomerPrice.vue'

Vue.component('customer-price', CustomerPrice)

Vue.mixin({
    mounted() {
        this.$root.$on('logged-in', clearPrices)
        this.$root.$on('logged-out', clearPrices)
    },
})