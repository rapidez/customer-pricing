import 'Vendor/rapidez/core/resources/js/vue'
import { clearPrices } from './stores/useCustomerPrices'
import CustomerPrice from './components/CustomerPrice.vue'

document.addEventListener('vue:loaded', function (event) {
    const vue = event.detail.vue
    vue.component('customer-price', CustomerPrice)
})

Vue.mixin({
    mounted() {
        window.$on('logged-in', clearPrices)
        window.$on('logged-out', clearPrices)
    },
})
