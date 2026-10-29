<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Store Billing — New Order</title>
    <meta name="description" content="Professional store order and inventory management page.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* ── Header ── */
        .site-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: #fff;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
        .site-header h1 {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .site-header h1 .divider { opacity: 0.4; font-weight: 300; }
        .site-header .badge {
            font-size: 0.68rem;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 4px;
            padding: 2px 8px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
        }
        .site-header .catalog-link {
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            padding: 7px 10px;
        }
        .site-header .catalog-link:hover { background: rgba(255,255,255,0.12); }
        .site-header .catalog-link:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
        @media (max-width: 600px) {
            .site-header { padding: 0 1rem; }
            .site-header .badge { display: none; }
        }

        /* ── Page Layout ── */
        .page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem 3rem;
        }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .card-header {
            background: #f8f9fb;
            border-bottom: 1px solid #e8eaed;
            padding: 0.75rem 1.25rem;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #555;
        }
        .card-body { padding: 1.25rem; }

        /* ── Two-column main layout ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 1.25rem;
            align-items: start;
        }
        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
        }
        .left-col { display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── Customer Section ── */
        .customer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 600px) { .customer-grid { grid-template-columns: 1fr; } }

        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            letter-spacing: 0.03em;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #dde1e7;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
            color: #1a1a2e;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        input:focus, select:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15,52,96,0.1);
        }
        input[readonly] {
            background: #f4f6fa;
            color: #666;
        }

        /* ── Products Table ── */
        .products-table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        thead th {
            background: #f0f2f5;
            padding: 9px 12px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #666;
            border-bottom: 2px solid #e0e4ea;
        }
        tbody tr {
            border-bottom: 1px solid #f0f2f5;
            transition: background 0.15s;
        }
        tbody tr:hover { background: #fafbff; }
        tbody td { padding: 8px 10px; vertical-align: middle; }
        tbody td:last-child { text-align: right; }
        tbody td select, tbody td input { margin: 0; }

        .product-select { min-width: 180px; }
        .qty-input { width: 70px; text-align: center; }
        .price-cell { font-weight: 600; color: #1a1a2e; white-space: nowrap; }
        .line-total-cell { font-weight: 700; color: #0f3460; white-space: nowrap; }

        .btn-remove {
            background: none;
            border: none;
            cursor: pointer;
            color: #cc3333;
            font-size: 1.1rem;
            padding: 2px 6px;
            border-radius: 4px;
            transition: background 0.15s;
            line-height: 1;
        }
        .btn-remove:hover { background: #fff0f0; }

        .table-footer-row td {
            padding: 10px 10px 0;
            border-bottom: none;
        }
        .btn-add-product {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #0f3460;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.02em;
        }
        .btn-add-product:hover { background: #16213e; transform: translateY(-1px); }
        .btn-add-product:active { transform: translateY(0); }

        /* ── Payment Summary ── */
        .summary-table { width: 100%; font-size: 0.875rem; }
        .summary-table tr td { padding: 6px 0; }
        .summary-table tr td:last-child { text-align: right; font-weight: 600; }
        .summary-divider { border-top: 1.5px solid #e0e4ea; margin: 8px 0; }
        .grand-total-row td { font-size: 1.05rem; font-weight: 800; color: #0f3460; }

        .btn-place-order {
            width: 100%;
            margin-top: 1rem;
            padding: 13px;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(15,52,96,0.35);
        }
        .btn-place-order:hover { opacity: 0.92; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,52,96,0.4); }
        .btn-place-order:active { transform: translateY(0); }
        .btn-place-order:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* ── Low Stock Alert Panel ── */
        .low-stock-card {
            border: 1.5px solid #f5a623;
            border-radius: 12px;
            background: #fffbf0;
            overflow: hidden;
        }
        .low-stock-header {
            background: #f5a623;
            color: #fff;
            padding: 0.65rem 1rem;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .low-stock-body { padding: 0.9rem 1rem; }
        .low-stock-body ul { list-style: none; }
        .low-stock-body li {
            font-size: 0.82rem;
            padding: 3px 0;
            color: #7a4800;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .low-stock-body li::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #f5a623;
            flex-shrink: 0;
        }
        .low-stock-body .stock-badge {
            margin-left: auto;
            font-size: 0.72rem;
            font-weight: 700;
            background: #f5a623;
            color: #fff;
            border-radius: 20px;
            padding: 1px 7px;
        }
        .low-stock-empty {
            font-size: 0.82rem;
            color: #888;
            text-align: center;
            padding: 8px 0;
        }

        /* ── Alert Messages ── */
        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            display: none;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .alert.show { display: flex; align-items: flex-start; gap: 10px; }
        .alert-success {
            background: #edfdf5;
            border: 1.5px solid #34c77b;
            color: #167a45;
        }
        .alert-error {
            background: #fff0f0;
            border: 1.5px solid #e35555;
            color: #b91c1c;
        }
        .alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        /* Customer lookup status */
        .customer-status {
            font-size: 0.72rem;
            margin-top: 4px;
            min-height: 16px;
        }
        .customer-status.found { color: #167a45; }
        .customer-status.not-found { color: #b91c1c; }
        .customer-status.loading { color: #888; }

        /* Spinner for buttons */
        .spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <header class="site-header">
        <h1>
            🏪 Store Billing
            <span class="divider">—</span>
            New Order
        </h1>
        <div style="display:flex;align-items:center;gap:12px">
            <a class="catalog-link" href="{{ route('products.catalog') }}">Product inventory</a>
            <a class="catalog-link" href="{{ route('customers.index') }}">Customers</a>
            <span class="badge">WIREFRAME — layout &amp; reference only</span>
        </div>
    </header>

    <div class="page-container">

        {{-- Alert Messages --}}
        <div id="alertSuccess" class="alert alert-success">
            <span class="alert-icon">✅</span>
            <span id="alertSuccessMsg">Order placed successfully!</span>
        </div>
        <div id="alertError" class="alert alert-error">
            <span class="alert-icon">❌</span>
            <span id="alertErrorMsg">Something went wrong. Please try again.</span>
        </div>

        <div class="main-grid">

            {{-- ═══ LEFT COLUMN ═══ --}}
            <div class="left-col">

                {{-- Customer Card --}}
                <div class="card">
                    <div class="card-header">Customer</div>
                    <div class="card-body">
                        <div class="customer-grid">
                            <div>
                                <label for="customerEmail">Email</label>
                                <input type="email" id="customerEmail" placeholder="e.g. thomas@example.com" autocomplete="off">
                                <div id="customerStatus" class="customer-status"></div>
                            </div>
                            <div>
                                <label for="customerName">Name</label>
                                <input type="text" id="customerName" placeholder="auto-filled if email exists" readonly>
                            </div>
                        </div>
                        <input type="hidden" id="customerId" value="">
                    </div>
                </div>

                {{-- Products Card --}}
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body" style="padding: 0;">
                        <div class="products-table-wrap">
                            <table id="productsTable">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Product</th>
                                        <th style="width:15%">Qty</th>
                                        <th style="width:20%">Price</th>
                                        <th style="width:20%">Line Total</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="productRows">
                                    {{-- rows added by JS --}}
                                </tbody>
                                <tfoot>
                                    <tr class="table-footer-row">
                                        <td colspan="5" style="padding: 14px 10px;">
                                            <button class="btn-add-product" id="btnAddProduct" type="button">
                                                ＋ Add Product
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Payment Summary Card --}}
                <div class="card">
                    <div class="card-header">Payment</div>
                    <div class="card-body">
                        <table class="summary-table">
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <td id="summarySubtotal">₹0.00</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td id="summaryTax">₹0.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr class="summary-divider">
                        <table class="summary-table">
                            <tbody>
                                <tr class="grand-total-row">
                                    <td>Grand Total</td>
                                    <td id="summaryGrandTotal">₹0.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn-place-order" id="btnPlaceOrder" type="button">
                            Place Order
                        </button>
                    </div>
                </div>

            </div>{{-- /left-col --}}

            {{-- ═══ RIGHT COLUMN ═══ --}}
            <div class="right-col">
                <div class="low-stock-card">
                    <div class="low-stock-header">
                        ⚠️ &nbsp;Low Stock Alert
                    </div>
                    <div class="low-stock-body">
                        <ul id="lowStockList">
                            <li class="low-stock-empty">Loading…</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>{{-- /main-grid --}}

    </div>{{-- /page-container --}}

<script>
// ════════════════════════════════════════════════════════════
//  State
// ════════════════════════════════════════════════════════════
let allProducts = [];   // [{id, name, price, tax_percentage, stock}]
let rowCounter  = 0;

const API_BASE = '/api';

// ════════════════════════════════════════════════════════════
//  Helpers
// ════════════════════════════════════════════════════════════
const fmt = n => '₹' + parseFloat(n || 0).toFixed(2);

function showAlert(type, msg) {
    document.getElementById('alertSuccess').classList.remove('show');
    document.getElementById('alertError').classList.remove('show');
    if (type === 'success') {
        document.getElementById('alertSuccessMsg').textContent = msg;
        document.getElementById('alertSuccess').classList.add('show');
    } else {
        document.getElementById('alertErrorMsg').textContent = msg;
        document.getElementById('alertError').classList.add('show');
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function hideAlerts() {
    document.getElementById('alertSuccess').classList.remove('show');
    document.getElementById('alertError').classList.remove('show');
}

// ════════════════════════════════════════════════════════════
//  Load products from API
// ════════════════════════════════════════════════════════════
async function loadProducts() {
    try {
        const res  = await fetch(`${API_BASE}/products`);
        const json = await res.json();
        if (json.success) {
            allProducts = json.data;
            // Refresh any existing selects
            document.querySelectorAll('.product-select').forEach(sel => {
                const currentVal = sel.value;
                populateProductSelect(sel);
                sel.value = currentVal;
            });
        }
    } catch (e) {
        console.warn('Could not load products:', e);
    }
}

function populateProductSelect(sel) {
    const placeholder = sel.querySelector('option[value=""]');
    sel.innerHTML = '';
    if (placeholder) sel.appendChild(placeholder);
    else {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = '+ dropdown to add product row';
        sel.appendChild(opt);
    }
    allProducts.forEach(p => {
        const opt      = document.createElement('option');
        opt.value      = p.id;
        opt.textContent = `${p.name}${p.stock <= 5 ? ' ⚠️' : ''}`;
        opt.dataset.price = p.price;
        opt.dataset.tax   = p.tax_percentage;
        opt.dataset.stock = p.stock;
        sel.appendChild(opt);
    });
}

// ════════════════════════════════════════════════════════════
//  Low Stock Panel
// ════════════════════════════════════════════════════════════
async function loadLowStock() {
    try {
        const res  = await fetch(`${API_BASE}/products/low-stock?threshold=5`);
        const json = await res.json();
        const list = document.getElementById('lowStockList');
        if (json.success && json.data.length > 0) {
            list.innerHTML = json.data.map(p => `
                <li>
                    <span>${p.name}</span>
                    <span class="stock-badge">${p.stock} left</span>
                </li>`).join('');
        } else {
            list.innerHTML = '<li class="low-stock-empty">All items well-stocked ✓</li>';
        }
    } catch (e) {
        document.getElementById('lowStockList').innerHTML =
            '<li class="low-stock-empty">Could not load data.</li>';
    }
}

// ════════════════════════════════════════════════════════════
//  Customer Lookup
// ════════════════════════════════════════════════════════════
const emailInput    = document.getElementById('customerEmail');
const nameInput     = document.getElementById('customerName');
const customerIdEl  = document.getElementById('customerId');
const customerStatus = document.getElementById('customerStatus');

let lookupTimeout = null;

emailInput.addEventListener('blur', () => {
    clearTimeout(lookupTimeout);
    const email = emailInput.value.trim();
    if (!email) {
        resetCustomer();
        return;
    }
    lookupCustomer(email);
});

emailInput.addEventListener('input', () => {
    clearTimeout(lookupTimeout);
    // Debounce: don't clear name while user is still typing
    customerStatus.textContent = '';
    customerStatus.className = 'customer-status';
});

async function lookupCustomer(email) {
    customerStatus.textContent = 'Looking up…';
    customerStatus.className   = 'customer-status loading';
    nameInput.value      = '';
    customerIdEl.value   = '';

    try {
        const res  = await fetch(`${API_BASE}/customers/lookup?email=${encodeURIComponent(email)}`);
        const json = await res.json();
        if (json.success) {
            nameInput.value    = json.data.name;
            customerIdEl.value = json.data.id;
            customerStatus.textContent = '✓ Customer found';
            customerStatus.className   = 'customer-status found';
        } else {
            customerStatus.textContent = '✕ Customer not found — check email';
            customerStatus.className   = 'customer-status not-found';
        }
    } catch (e) {
        customerStatus.textContent = '✕ Network error';
        customerStatus.className   = 'customer-status not-found';
    }
}

function resetCustomer() {
    nameInput.value       = '';
    customerIdEl.value    = '';
    customerStatus.textContent = '';
    customerStatus.className   = 'customer-status';
}

// ════════════════════════════════════════════════════════════
//  Product Rows
// ════════════════════════════════════════════════════════════
function addProductRow() {
    rowCounter++;
    const id  = rowCounter;
    const tbody = document.getElementById('productRows');
    const tr    = document.createElement('tr');
    tr.id = `row-${id}`;
    tr.innerHTML = `
        <td>
            <select class="product-select" id="prod-${id}" onchange="onProductChange(${id})">
                <option value="">+ dropdown to add product row</option>
            </select>
        </td>
        <td>
            <input type="number" class="qty-input" id="qty-${id}"
                   value="1" min="1" onchange="recalcRow(${id})" oninput="recalcRow(${id})">
        </td>
        <td>
            <span class="price-cell" id="price-${id}">—</span>
        </td>
        <td>
            <span class="line-total-cell" id="lt-${id}">—</span>
        </td>
        <td>
            <button class="btn-remove" onclick="removeRow(${id})" title="Remove">✕</button>
        </td>`;
    tbody.appendChild(tr);
    populateProductSelect(document.getElementById(`prod-${id}`));
}

function removeRow(id) {
    const row = document.getElementById(`row-${id}`);
    if (row) row.remove();
    recalcTotals();
}

function onProductChange(id) {
    recalcRow(id);
}

function recalcRow(id) {
    const sel = document.getElementById(`prod-${id}`);
    const qty = parseFloat(document.getElementById(`qty-${id}`).value) || 0;
    const opt = sel.options[sel.selectedIndex];

    const priceEl = document.getElementById(`price-${id}`);
    const ltEl    = document.getElementById(`lt-${id}`);

    if (!sel.value || !opt.dataset.price) {
        priceEl.textContent = '—';
        ltEl.textContent    = '—';
        recalcTotals();
        return;
    }

    const price   = parseFloat(opt.dataset.price);
    const taxPct  = parseFloat(opt.dataset.tax || 0);
    const subLine = price * qty;
    const taxLine = subLine * taxPct / 100;
    const lineTotal = subLine + taxLine;

    priceEl.textContent = fmt(price);
    ltEl.textContent    = fmt(lineTotal);
    recalcTotals();
}

function recalcTotals() {
    let subtotal = 0, tax = 0;

    document.querySelectorAll('tr[id^="row-"]').forEach(tr => {
        const rowId = tr.id.replace('row-', '');
        const sel   = document.getElementById(`prod-${rowId}`);
        const qty   = parseFloat(document.getElementById(`qty-${rowId}`).value) || 0;
        if (!sel || !sel.value) return;
        const opt     = sel.options[sel.selectedIndex];
        const price   = parseFloat(opt.dataset.price || 0);
        const taxPct  = parseFloat(opt.dataset.tax || 0);
        const subLine = price * qty;
        const taxLine = subLine * taxPct / 100;
        subtotal += subLine;
        tax      += taxLine;
    });

    const grand = subtotal + tax;
    document.getElementById('summarySubtotal').textContent  = fmt(subtotal);
    document.getElementById('summaryTax').textContent       = fmt(tax);
    document.getElementById('summaryGrandTotal').textContent = fmt(grand);
}

// ════════════════════════════════════════════════════════════
//  Place Order
// ════════════════════════════════════════════════════════════
document.getElementById('btnPlaceOrder').addEventListener('click', async () => {
    hideAlerts();
    const customerId = document.getElementById('customerId').value;
    if (!customerId) {
        showAlert('error', 'Please enter a valid customer email and wait for the lookup to complete.');
        return;
    }

    const items = [];
    let hasError = false;

    document.querySelectorAll('tr[id^="row-"]').forEach(tr => {
        const rowId = tr.id.replace('row-', '');
        const sel   = document.getElementById(`prod-${rowId}`);
        const qty   = parseInt(document.getElementById(`qty-${rowId}`).value) || 0;
        if (sel && sel.value) {
            if (qty < 1) { hasError = true; return; }
            items.push({ product_id: parseInt(sel.value), quantity: qty });
        }
    });

    if (hasError) { showAlert('error', 'Please enter a valid quantity (≥ 1) for all products.'); return; }
    if (items.length === 0) { showAlert('error', 'Please add at least one product to the order.'); return; }

    // Check for duplicate product_id
    const ids = items.map(i => i.product_id);
    if (new Set(ids).size !== ids.length) {
        showAlert('error', 'Each product can only appear once in an order. Please merge duplicate rows.');
        return;
    }

    const btn = document.getElementById('btnPlaceOrder');
    btn.disabled   = true;
    btn.innerHTML  = '<span class="spinner"></span> Placing Order…';

    try {
        const res  = await fetch(`${API_BASE}/orders`, {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ customer_id: parseInt(customerId), items }),
        });

        const json = await res.json();

        if (json.success) {
            showAlert('success',
                `✅ Order #${json.data.id} placed successfully! ` +
                `Grand total: ${fmt(json.data.grand_total)}.`);
            // Reset form
            document.getElementById('productRows').innerHTML = '';
            recalcTotals();
            addProductRow();
            resetCustomer();
            emailInput.value = '';
            loadLowStock();   // refresh low-stock panel
        } else {
            // Handle Laravel validation errors
            if (json.errors) {
                const msgs = Object.values(json.errors).flat().join(' ');
                showAlert('error', msgs);
            } else {
                showAlert('error', json.message || 'Unable to place the order.');
            }
        }
    } catch (e) {
        showAlert('error', 'Network error — please check your connection and try again.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = 'Place Order';
    }
});

// ════════════════════════════════════════════════════════════
//  Add product row button
// ════════════════════════════════════════════════════════════
document.getElementById('btnAddProduct').addEventListener('click', () => addProductRow());

// ════════════════════════════════════════════════════════════
//  Init
// ════════════════════════════════════════════════════════════
(async function init() {
    await loadProducts();
    addProductRow();     // start with one empty row
    loadLowStock();
})();
</script>
</body>
</html>
