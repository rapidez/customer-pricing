import { useSessionStorage } from '@vueuse/core'

const sessionStorageCustomerPrices = useSessionStorage('customerPrices', {})

const getPricesRequest = async function (products) {
    if (!window.app.loggedIn) {
        return []
    }
    
    try {
        let missing = products.filter(product => !(product in sessionStorageCustomerPrices.value))

        // Only do a request if there are any missing products
        if (missing.length) {
            let currentPrices = {}
            for (let i = 0; i < missing.length; i += window.config.customerpricing.max_products) {
                let current = missing.slice(i, i + window.config.customerpricing.max_products)
                currentPrices = {
                    ...currentPrices,
                    ...await rapidezAPI('POST', 'customerprices', { products: current }),
                }
            }

            if (typeof currentPrices !== 'object') {
                return
            }

            // Add prices to total set of prices
            sessionStorageCustomerPrices.value = {
                ...sessionStorageCustomerPrices.value,
                ...currentPrices,
            }
        }

        return Object.fromEntries(
            products.map(product => (product in sessionStorageCustomerPrices.value) ? [product, sessionStorageCustomerPrices.value[product]] : null)
                .filter(Boolean)
        )
    } catch (error) {
        Notify(window.config.translations.errors.wrong, 'error')
        console.error(error)
    }
}

let pendingPromise = null;
let pendingProducts = [];

// This function batches multiple requests into one request every 100ms
export const getPrices = async function (products) {
    if (!pendingPromise) {
        // If there is no request waiting to be sent, create one.
        pendingPromise = new Promise((resolve, reject) =>
            window.setTimeout(async () => {
                const products = pendingProducts;
                pendingPromise = null;
                pendingProducts = [];
                getPricesRequest(products).then(resolve).catch(reject);
            }, 100)
        )
    }

    // When we have a request pending, add the products to the list
    pendingProducts = [...pendingProducts, ...products];
    return pendingPromise;
}

export const getPriceForProduct = async function (product) {
    if (sessionStorageCustomerPrices.value[product]) {
        // We already have the price in session storage,
        // we know won't need to wait for the request.
        return sessionStorageCustomerPrices.value[product];
    }

    return (await getPrices([product]))[product] ?? null;
}

export const clearPrices = async function () {
    sessionStorageCustomerPrices.value = {}
}
