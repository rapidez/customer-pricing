import 'Vendor/rapidez/core/resources/js/vue'
import { clearPrices } from './stores/useCustomerPrices'
import CustomerPrice from './components/CustomerPrice.vue';

Vue.component('customer-price', CustomerPrice)

function init () {
    window.app.$on('logged-in', clearPrices);
    window.app.$on('logged-out', clearPrices);
}

document.addEventListener('vue:loaded', init)
if (window.app && window.app.$on) {
    init()
}