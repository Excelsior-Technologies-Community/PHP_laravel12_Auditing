<!DOCTYPE html>
<html>

<head>

    <title>Products</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #3490dc;
            color: white;
        }

        .btn-edit {
            background: #38c172;
            color: white;
        }

        .btn-delete {
            background: #e3342f;
            color: white;
        }

        .btn-audit {
            background: #7c3aed;
            color: white;
        }

        .btn-dashboard {
            background: #111827;
            color: white;
        }

        .product {
            padding: 18px;
            border-bottom: 1px solid #ddd;
        }

        .product:last-child {
            border-bottom: none;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
        }

        .price {
            margin-top: 5px;
            color: #555;
        }

        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            margin-top: 12px;
        }

        form {
            display: inline;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="topbar">

        <h1>Product List</h1>

        <div>

            <a
                href="{{ route('products.create') }}"
                class="btn btn-primary"
            >
                + Add Product
            </a>

            <a
                href="{{ route('audit.dashboard') }}"
                class="btn btn-dashboard"
            >
                📊 Audit Dashboard
            </a>

            <a
                href="{{ route('audit.index') }}"
                class="btn btn-audit"
            >
                🔎 Audit Logs
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <hr>

    @forelse($products as $product)

        <div class="product">

            <div class="product-name">
                {{ $product->name }}
            </div>

            <div class="price">
                ₹{{ number_format($product->price) }}
            </div>

            <span
                class="status status-{{ $product->status }}"
            >
                {{ ucfirst($product->status) }}
            </span>

            <div class="actions">

                <a
                    href="{{ route('products.edit', $product->id) }}"
                    class="btn btn-edit"
                >
                    Edit
                </a>

                <form
                    action="{{ route('products.destroy', $product->id) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this product?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-delete"
                    >
                        Delete
                    </button>

                </form>

                <a
                    href="{{ route('products.audits', $product->id) }}"
                    class="btn btn-audit"
                >
                    View Audits
                </a>

            </div>

        </div>

    @empty

        <p>
            No products found.
        </p>

    @endforelse

</div>

</body>

</html>