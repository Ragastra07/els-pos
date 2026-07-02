// Format number into Indonesian Rupiah style.
function formatRupiah(number) {
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

// Calculate subtotal for one product row.
function calculateRow(row) {
    const selectedOption = row.querySelector('.product-select').selectedOptions[0];
    const price = selectedOption ? Number(selectedOption.dataset.price || 0) : 0;
    const stock = selectedOption ? Number(selectedOption.dataset.stock || 0) : 0;
    const qty = Number(row.querySelector('.qty-input').value || 0);
    const subtotal = price * qty;

    row.querySelector('.stock-display').value = stock;
    row.querySelector('.price-display').value = formatRupiah(price);
    row.querySelector('.subtotal-display').value = formatRupiah(subtotal);

    return subtotal;
}

// Calculate total transaction and change amount.
function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.sales-item-row').forEach(function(row) {
        total += calculateRow(row);
    });

    const paidAmount = Number(document.getElementById('paid_amount').value || 0);
    const changeAmount = paidAmount - total;

    document.getElementById('total_amount').value = formatRupiah(total);
    document.getElementById('change_amount').value = formatRupiah(changeAmount);
}

// Add new product row by cloning the first row.
document.getElementById('add-row').addEventListener('click', function() {
    const tableBody = document.querySelector('#sales-item-table tbody');
    const firstRow = document.querySelector('.sales-item-row');
    const newRow = firstRow.cloneNode(true);

    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('.stock-display').value = '';
    newRow.querySelector('.price-display').value = '';
    newRow.querySelector('.qty-input').value = 1;
    newRow.querySelector('.subtotal-display').value = '';

    tableBody.appendChild(newRow);
    calculateTotal();
});

// Handle product, quantity, and remove button changes using event delegation.
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('product-select') || event.target.classList.contains('qty-input')) {
        calculateTotal();
    }
});

document.addEventListener('keyup', function(event) {
    if (event.target.id === 'paid_amount' || event.target.classList.contains('qty-input')) {
        calculateTotal();
    }
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.sales-item-row');

        if (rows.length > 1) {
            event.target.closest('tr').remove();
            calculateTotal();
        }
    }
});

calculateTotal();
