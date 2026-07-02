// Stores cart items and temporary input buffers for cart/payment numpads.
const cart = new Map();
let selectedProductId = null;
let qtyBuffer = "";
let paidBuffer = "";
let activeCategory = "all";

// Main DOM references used by the cashier screen.
const cartList = document.getElementById("cart-list");
const cartEmpty = document.getElementById("cart-empty");
const cartTotal = document.getElementById("cart-total");
const cashierAlert = document.getElementById("cashier-alert");
const cartMode = document.getElementById("cart-mode");
const paymentMode = document.getElementById("payment-mode");
const paymentTotal = document.getElementById("payment-total");
const paidDisplay = document.getElementById("paid-display");
const changeDisplay = document.getElementById("change-display");

// Formats numeric values as Indonesian Rupiah for display.
function formatRupiah(value) {
    return "Rp " + Number(value || 0).toLocaleString("id-ID");
}

// Shows a visible validation or transaction alert.
function showAlert(message) {
    cashierAlert.textContent = message;
    cashierAlert.classList.remove("d-none");
}

// Hides and clears the current cashier alert.
function clearAlert() {
    cashierAlert.textContent = "";
    cashierAlert.classList.add("d-none");
}

// Calculates the current cart total from item price and quantity.
function getTotal() {
    let total = 0;

    cart.forEach(function (item) {
        total += item.price * item.qty;
    });

    return total;
}

// Selects a cart item and resets the quantity typing buffer.
function selectCartItem(productId) {
    selectedProductId = productId;
    qtyBuffer = "";
    renderCart();
}

// Rebuilds the cart list UI and refreshes total/payment displays.
function renderCart() {
    // Clear the current cart item elements before rendering the latest cart state.
    cartList.querySelectorAll(".cart-item").forEach(function (item) {
        item.remove();
    });

    // Show the empty cart message only when the cart has no items.
    cartEmpty.classList.toggle("d-none", cart.size > 0);

    // Render each cart item from the cart Map into the cart list panel.
    cart.forEach(function (item) {
        const row = document.createElement("button");
        row.type = "button";
        row.className = "cart-item w-100 p-2 mb-2 text-start bg-white";

        // Highlight the currently selected cart item.
        if (String(item.id) === String(selectedProductId)) {
            row.classList.add("active");
        }

        // Select this item when the cashier clicks the cart row.
        row.addEventListener("click", function () {
            selectCartItem(String(item.id));
        });

        // Build the top row containing product name and subtotal.
        const topLine = document.createElement("div");
        topLine.className = "d-flex justify-content-between gap-2";

        const name = document.createElement("div");
        name.className = "fw-semibold";
        name.textContent = item.name;

        const subtotal = document.createElement("div");
        subtotal.className = "fw-bold text-primary";
        subtotal.textContent = formatRupiah(item.price * item.qty);

        // Build the detail text containing product code, quantity, and unit price.
        const details = document.createElement("div");
        details.className = "small text-muted";
        details.textContent =
            item.code + " - " + item.qty + " x " + formatRupiah(item.price);

        // Attach the generated elements into the cart row and cart list.
        topLine.appendChild(name);
        topLine.appendChild(subtotal);
        row.appendChild(topLine);
        row.appendChild(details);
        cartList.appendChild(row);
    });

    // Recalculate and update total displays after cart rendering is complete.
    const total = getTotal();
    cartTotal.textContent = formatRupiah(total);
    paymentTotal.textContent = formatRupiah(total);

    // Keep the payment summary in sync with the latest cart total.
    updatePaymentSummary();
}

// Adds a product card to the cart or increases its quantity if it exists.
function addProduct(card) {
    clearAlert();

    const product = {
        id: String(card.dataset.id),
        name: card.dataset.name,
        code: card.dataset.code,
        price: Number(card.dataset.price || 0),
        stock: Number(card.dataset.stock || 0),
    };

    if (product.stock <= 0) {
        showAlert("Produk ini sedang habis.");
        return;
    }

    const existing = cart.get(product.id);

    if (existing) {
        if (existing.qty >= existing.stock) {
            showAlert(
                'Qty produk "' + existing.name + '" sudah mencapai stok tersedia.',
            );
            selectCartItem(product.id);
            return;
        }

        existing.qty += 1;
    } else {
        product.qty = 1;
        cart.set(product.id, product);
    }

    selectCartItem(product.id);
}

// Updates selected item quantity while enforcing stock limits.
function setSelectedQty(qty) {
    if (!selectedProductId || !cart.has(selectedProductId)) {
        showAlert("Pilih item cart terlebih dahulu.");
        return;
    }

    const item = cart.get(selectedProductId);

    if (qty <= 0) {
        cart.delete(selectedProductId);
        selectedProductId = cart.size ? String(cart.keys().next().value) : null;
        qtyBuffer = "";
        renderCart();
        return;
    }

    if (qty > item.stock) {
        showAlert("Qty melebihi stok tersedia.");
        qty = item.stock;
        qtyBuffer = String(qty);
    }

    item.qty = qty;
    renderCart();
}

// Handles cart numpad input for quantity editing and item actions.
function handleCartNumpad(target) {
    const key = target.dataset.key;
    const action = target.dataset.action;

    clearAlert();

    if (key !== undefined) {
        if (!selectedProductId) {
            showAlert("Pilih item cart terlebih dahulu.");
            return;
        }

        qtyBuffer += key;
        setSelectedQty(Number(qtyBuffer));
        return;
    }

    if (action === "plus" && selectedProductId && cart.has(selectedProductId)) {
        const item = cart.get(selectedProductId);
        setSelectedQty(item.qty + 1);
    }

    if (action === "minus" && selectedProductId && cart.has(selectedProductId)) {
        const item = cart.get(selectedProductId);
        setSelectedQty(item.qty - 1);
    }

    if (action === "remove" && selectedProductId) {
        cart.delete(selectedProductId);
        selectedProductId = cart.size ? String(cart.keys().next().value) : null;
        qtyBuffer = "";
        renderCart();
    }

    if (action === "clear") {
        qtyBuffer = "";
    }

    if (action === "backspace") {
        qtyBuffer = qtyBuffer.slice(0, -1);

        if (qtyBuffer !== "") {
            setSelectedQty(Number(qtyBuffer));
        }
    }
}

// Refreshes paid and change values in payment mode.
function updatePaymentSummary() {
    const total = getTotal();
    const paidAmount = Number(paidBuffer || 0);
    const changeAmount = paidAmount - total;

    paidDisplay.textContent = formatRupiah(paidAmount);
    changeDisplay.textContent = formatRupiah(changeAmount);
}

// Handles payment numpad input for paid amount editing.
function handlePaymentNumpad(target) {
    const key = target.dataset.key;
    const action = target.dataset.action;

    clearAlert();

    if (key !== undefined) {
        paidBuffer += key;
    }

    if (action === "clear") {
        paidBuffer = "";
    }

    if (action === "backspace") {
        paidBuffer = paidBuffer.slice(0, -1);
    }

    updatePaymentSummary();
}

// Switches the left panel from cart mode to payment mode.
function showPaymentMode() {
    clearAlert();

    if (cart.size === 0) {
        showAlert("Cart masih kosong.");
        return;
    }

    cartMode.classList.add("d-none");
    paymentMode.classList.remove("d-none");
    updatePaymentSummary();
}

// Returns from payment mode back to cart editing mode.
function showCartMode() {
    paymentMode.classList.add("d-none");
    cartMode.classList.remove("d-none");
}

// Populates the hidden form and submits it to the existing sales store route.
function submitTransaction() {
    clearAlert();

    const total = getTotal();
    const paidAmount = Number(paidBuffer || 0);

    if (cart.size === 0) {
        showAlert("Cart masih kosong.");
        return;
    }

    if (paidAmount < total) {
        showAlert("Nominal pembayaran kurang dari total transaksi.");
        return;
    }

    const formItems = document.getElementById("form-items");
    formItems.innerHTML = "";

    document.getElementById("form-customer-name").value =
        document.getElementById("customer-name").value || "Umum";
    document.getElementById("form-paid-amount").value = paidAmount;

    cart.forEach(function (item) {
        const productInput = document.createElement("input");
        productInput.type = "hidden";
        productInput.name = "product_id[]";
        productInput.value = item.id;

        const qtyInput = document.createElement("input");
        qtyInput.type = "hidden";
        qtyInput.name = "qty[]";
        qtyInput.value = item.qty;

        formItems.appendChild(productInput);
        formItems.appendChild(qtyInput);
    });

    document.getElementById("cashier-form").submit();
}

// Filters product cards by active category and search keyword.
function filterProducts() {
    const keyword = document
        .getElementById("product-search")
        .value.trim()
        .toLowerCase();

    document.querySelectorAll(".product-card").forEach(function (card) {
        const matchesCategory =
            activeCategory === "all" || card.dataset.categoryId === activeCategory;
        const matchesSearch =
            keyword === "" || card.dataset.search.includes(keyword);

        card.classList.toggle("d-none", !(matchesCategory && matchesSearch));
    });
}

// Checks whether the payment panel is currently active.
function isPaymentModeActive() {
    return !paymentMode.classList.contains("d-none");
}

// Prevents global shortcuts while the user is typing in form fields.
function isTypingTarget(target) {
    const tagName = target.tagName ? target.tagName.toLowerCase() : "";

    return (
        target.isContentEditable ||
        ["input", "select", "textarea"].includes(tagName)
    );
}

// Maps physical keyboard shortcuts to the same handlers as the on-screen numpads.
document.addEventListener("keydown", function (event) {
    if (isTypingTarget(event.target)) {
        return;
    }

    const numberKey =
        event.key.length === 1 && event.key >= "0" && event.key <= "9"
            ? event.key
            : null;
    const paymentActive = isPaymentModeActive();

    if (numberKey !== null) {
        event.preventDefault();

        if (paymentActive) {
            handlePaymentNumpad({ dataset: { key: numberKey } });
        } else {
            handleCartNumpad({ dataset: { key: numberKey } });
        }

        return;
    }

    if (event.key === "Backspace") {
        event.preventDefault();

        if (paymentActive) {
            handlePaymentNumpad({ dataset: { action: "backspace" } });
        } else {
            handleCartNumpad({ dataset: { action: "backspace" } });
        }

        return;
    }

    if (event.key === "Delete" || event.key === "Escape") {
        event.preventDefault();

        if (paymentActive) {
            handlePaymentNumpad({ dataset: { action: "clear" } });
        } else {
            handleCartNumpad({ dataset: { action: "clear" } });
        }

        return;
    }

    if (event.key === "Enter") {
        event.preventDefault();

        if (paymentActive) {
            if (cart.size > 0 && Number(paidBuffer || 0) >= getTotal()) {
                submitTransaction();
            }
        } else if (cart.size > 0) {
            showPaymentMode();
        }

        return;
    }

    if (!paymentActive && (event.key === "+" || event.code === "NumpadAdd")) {
        event.preventDefault();
        handleCartNumpad({ dataset: { action: "plus" } });
        return;
    }

    if (
        !paymentActive &&
        (event.key === "-" || event.code === "NumpadSubtract")
    ) {
        event.preventDefault();
        handleCartNumpad({ dataset: { action: "minus" } });
    }
});

// Adds products to the cart from product card clicks.
document
    .getElementById("product-grid")
    .addEventListener("click", function (event) {
        const card = event.target.closest(".product-card");

        if (card) {
            addProduct(card);
        }
    });

// Routes cart numpad button clicks to cart quantity/action handling.
document
    .getElementById("cart-numpad")
    .addEventListener("click", function (event) {
        if (event.target.matches("button")) {
            handleCartNumpad(event.target);
        }
    });

// Routes payment numpad button clicks to paid amount handling.
document
    .getElementById("payment-numpad")
    .addEventListener("click", function (event) {
        if (event.target.matches("button")) {
            handlePaymentNumpad(event.target);
        }
    });

// Handles mode changes and final payment submission.
document
    .getElementById("payment-button")
    .addEventListener("click", showPaymentMode);
document.getElementById("back-to-cart").addEventListener("click", showCartMode);
document
    .getElementById("complete-payment")
    .addEventListener("click", submitTransaction);

// Sets paid amount to exactly match the cart total.
document.getElementById("exact-payment").addEventListener("click", function () {
    paidBuffer = String(getTotal());
    updatePaymentSummary();
});

// Clears the entire cart and resets payment/cart input state.
document.getElementById("reset-cart").addEventListener("click", function () {
    cart.clear();
    selectedProductId = null;
    qtyBuffer = "";
    paidBuffer = "";
    showCartMode();
    clearAlert();
    renderCart();
});

// Applies text search as the product search field changes.
document
    .getElementById("product-search")
    .addEventListener("input", filterProducts);

// Applies category filters and updates the active category button style.
document
    .getElementById("category-filter")
    .addEventListener("click", function (event) {
        const button = event.target.closest("button[data-category]");

        if (!button) {
            return;
        }

        activeCategory = button.dataset.category;

        document
            .querySelectorAll("#category-filter button")
            .forEach(function (item) {
                item.classList.remove("btn-primary");
                item.classList.add("btn-outline-primary");
            });

        button.classList.remove("btn-outline-primary");
        button.classList.add("btn-primary");
        filterProducts();
    });

// Initializes the cart UI on first page load.
renderCart();
