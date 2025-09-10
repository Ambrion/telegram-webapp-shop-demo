let tg = window.Telegram.WebApp;

tg.expand();

tg.MainButton.textColor = '#FFFFFF';
tg.MainButton.color = '#2cab37';

let cart = [];

function saveCartToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCartFromLocalStorage() {
    let savedCart = localStorage.getItem('cart');
    if (savedCart) {
        try {
            cart = JSON.parse(savedCart);
        } catch (e) {
            console.error("Failed to parse cart from localStorage", e);
        }
    }
}

function clearCart() {
    cart = [];
    localStorage.removeItem('cart');
    updateCartUI();
}

function toggleClearCartButton() {
    let clearCartBtn = document.getElementById('clear-cart-btn');
    if (clearCartBtn) {
        clearCartBtn.style.display = cart.length > 0 ? 'block' : 'none';
    }
}

function getTotalPrice() {
    try {
        let savedCart = localStorage.getItem('cart');
        let cart = savedCart ? JSON.parse(savedCart) : [];
        return cart.reduce((total, item) => total + item.price * item.quantity, 0);
    } catch (e) {
        console.error("Failed to parse cart from localStorage", e);
        return 0;
    }
}

function updateProductButtons() {
    let addButtons = document.querySelectorAll('.add-btn');
    let removeButtons = document.querySelectorAll('.remove-btn');

    addButtons.forEach(button => {
        button.style.display = 'block';
    });

    removeButtons.forEach(button => {
        let productData = button.getAttribute('onclick').match(/{.*}/)[0];
        let product;
        try {
            product = JSON.parse(productData);
        } catch (e) {
            return;
        }

        let inCart = cart.find(item => item.id === product.id);
        if (inCart) {
            button.style.display = 'block';
            button.textContent = `Удалить из корзины (${inCart.quantity})`;
        } else {
            button.style.display = 'none';
            button.textContent = 'Удалить из корзины';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadCartFromLocalStorage();
    updateCartUI();

    let cartForm = document.getElementById('cart-form');

    let clearCartBtn = document.getElementById('clear-cart-btn');
    if (!clearCartBtn) {
        clearCartBtn = document.createElement('button');
        clearCartBtn.id = 'clear-cart-btn';
        clearCartBtn.className = 'btn btn-warning mt-3';
        clearCartBtn.textContent = 'Очистить корзину';
        clearCartBtn.style.display = 'none';

        cartForm.parentNode.insertBefore(clearCartBtn, cartForm.nextSibling);
    }

    clearCartBtn.addEventListener('click', () => {
        if (confirm('Вы уверены, что хотите очистить корзину?')  === true) {
            clearCart();
        }
    });

    toggleClearCartButton();
});

function updateCartUI() {
    let cartForm = document.getElementById('cart-form');
    let btnCartTopMenu = document.getElementById('btnCartTopMenu');

    try {
        let savedCart = localStorage.getItem('cart');
        cart = savedCart ? JSON.parse(savedCart) : [];
    } catch (e) {
        console.error("Failed to parse cart from localStorage", e);
        cart = [];
    }

    if (cart.length === 0) {
        cartForm.innerHTML = '<p>Корзина пуста</p>';
        tg.MainButton.hide();
        btnCartTopMenu.innerHTML = '<i class="bi bi-bag"></i>';
    } else {
        let cartHtml = '<ul>';
        let totalPrice = getTotalPrice();

        cart.forEach(item => {
            let itemTotal = item.price * item.quantity;
            cartHtml += `<li>${item.title} x${item.quantity} — ${itemTotal} RUB</li>`;
        });

        cartHtml += `</ul><h5>Итого: ${totalPrice} RUB</h5>`;
        cartForm.innerHTML = cartHtml;

        btnCartTopMenu.innerHTML = '<i class="bi bi-bag-heart"></i> ' + totalPrice + ' RUB';

        tg.MainButton.setText('Оплатить');
        tg.MainButton.show();
    }

    updateProductButtons();
    toggleClearCartButton();
}

function addToCart(productStr) {
    console.log(productStr);
    let product;
    try {
        product = typeof productStr === 'string' ? JSON.parse(productStr) : productStr;
    } catch (e) {
        console.error("Invalid product data", productStr);
        return;
    }

    let existingItem = cart.find(item => item.id === product.id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.id,
            title: product.title,
            price: product.price,
            quantity: 1
        });
    }

    saveCartToLocalStorage();
    updateCartUI();
}

function removeOneFromCart(productStr) {
    let product;
    try {
        product = typeof productStr === 'string' ? JSON.parse(productStr) : productStr;
    } catch (e) {
        console.error("Invalid product data", productStr);
        return;
    }

    let existingItem = cart.find(item => item.id === product.id);

    if (!existingItem) return;

    if (existingItem.quantity > 1) {
        existingItem.quantity -= 1;
    } else {
        cart = cart.filter(item => item.id !== product.id);
    }

    tg.MainButton.setText(`Оплатить ${getTotalPrice()} RUB`);
    if (cart.length === 0) {
        tg.MainButton.hide();
    }

    saveCartToLocalStorage();
    updateCartUI();
}

tg.onEvent("mainButtonClicked", function(){
    let totalPrice = getTotalPrice();
    sendInvoice(totalPrice);
});

function sendInvoice(amount) {
    if (!tg || !tg.initData) {
        console.error("Telegram.WebApp не инициализирован");
        return;
    }

    if (amount < 0.01) {
        console.error("Amount must be at least 0.01 RUB");
        return;
    }

    let initData = tg.initData;
    let params = new URLSearchParams(initData);
    let userParam = params.get('user');

    let chatId = null;

    if (userParam) {
        try {
            let user = JSON.parse(userParam);
            chatId = user.id;
        } catch (e) {
            console.error("Ошибка парсинга параметра user", e);
        }
    }

    if (!chatId) {
        console.error("Не удалось получить chat_id");
        return;
    }

    fetch('/create-invoice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            cart: cart,
            initData: tg.initData
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Ошибка сети при отправке запроса");
            }
            return response.json();
        })
        .then(data => {
            clearCart();
            tg.close();
        })
        .catch(error => {
            console.error("Ошибка при вызове /create-invoice:", error);
        });
}

// Load cart on page load
document.addEventListener('DOMContentLoaded', () => {
    loadCartFromLocalStorage();
    updateCartUI();
});


