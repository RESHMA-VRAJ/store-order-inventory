<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A simple list of customers and email addresses.">
    <title>Customers</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #fff;
            color: #1f2937;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        main { max-width: 860px; margin: 0 auto; padding: 32px 20px; }
        nav { margin-bottom: 24px; font-size: 0.875rem; }
        nav a { color: #475569; text-decoration: underline; text-underline-offset: 3px; }
        h1 { margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: -0.02em; }
        .subtitle { margin: 6px 0 24px; color: #64748b; font-size: 0.9rem; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; min-width: 400px; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        thead th { color: #64748b; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        tbody th { font-weight: 500; text-align: left; }
        .empty { padding: 28px 12px; color: #64748b; text-align: center; }
        @media (max-width: 480px) {
            main { padding: 24px 14px; }
            th, td { padding: 10px 8px; }
        }
    </style>
</head>
<body>
    <main>
        <nav aria-label="Inventory navigation">
            <a href="{{ route('products.catalog') }}">Products &amp; stock</a>
        </nav>
        <h1>Customers</h1>
        <p class="subtitle">Customer names and email addresses.</p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <th scope="row">{{ $customer->name }}</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="2">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
