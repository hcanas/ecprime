import {onMounted, ref} from "vue";
import {find, forEach, remove} from "lodash";
import {reduce} from "lodash/collection.js";

export function useCart() {
    const items = ref([]);

    function loadItems() {
        items.value = Array.isArray(JSON.parse(localStorage.getItem('cart')))
            ? JSON.parse(localStorage.getItem('cart'))
            : [];
    }

    function addItem(id) {
        if (itemExists(id)) return;

        updateCart([
            ...items.value,
            {
                id,
                quantity: 1,
                brand: null,
            },
        ]);
    }

    function updateItem(id, newData) {
        forEach(items.value, (item, key) => {
            if (item.id === id) {
                items.value[key].quantity = parseInt(newData.quantity ?? item.quantity);
                items.value[key].brand = newData.brand ?? item.brand;
                return false;
            }
        });

        updateCart(items.value);
    }

    function removeItem(id) {
        remove(items.value, item => item.id === id);
        updateCart(items.value);
    }

    function clearItems() {
        updateCart([]);
    }

    function itemExists(id) {
        return Boolean(find(items.value, x => x.id === id));
    }

    function getIds() {
        return reduce(items.value, (arr, item) => {
            arr.push(item.id);
            return arr;
        }, []).join();
    }

    function updateCart(items) {
        localStorage.setItem('cart', JSON.stringify(items));

        dispatchEvent(new StorageEvent('storage', {
            key: 'cart',
            newValue: JSON.stringify(items),
        }));
    }

    onMounted(() => {
        loadItems();

        window.addEventListener('storage', event => {
            if (event.key === 'cart') loadItems();
        });
    });

    return {
        items,
        addItem,
        updateItem,
        removeItem,
        clearItems,
        itemExists,
        getIds,
    };
}
