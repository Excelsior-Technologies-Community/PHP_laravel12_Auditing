<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Product Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 35px;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .container {
            max-width: 1250px;
            margin: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 9px 14px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        .btn-purple {
            background: #7c3aed;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-warning {
            background: #d97706;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-gray {
            background: #6b7280;
            color: white;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, .07);
        }

        .stat-title {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 700;
        }

        .filter-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, .07);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 12px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
        }

        .filter-actions {
            margin-top: 15px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .bulk-bar {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .bulk-bar span {
            font-weight: 600;
            margin-right: auto;
        }

        .bulk-bar select {
            width: 180px;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, .07);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #f9fafb;
            padding: 14px;
            text-align: left;
            font-size: 13px;
            color: #6b7280;
        }

        td {
            padding: 14px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .product-name {
            font-weight: 700;
        }

        .price {
            font-weight: 600;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .actions .btn {
            padding: 6px 9px;
            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Pagination
        |--------------------------------------------------------------------------
        */

        .pagination {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            min-width: 38px;
            height: 38px;
            padding: 8px 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .pagination a:hover {
            background: #eff6ff;
            border-color: #2563eb;
            color: #2563eb;
        }

        .pagination .active-page {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            cursor: default;
        }

        .pagination .dots {
            border: none;
            background: transparent;
            cursor: default;
            min-width: 25px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .checkbox {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        @media(max-width: 1000px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width: 600px) {

            body {
                padding: 15px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats,
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .pagination a,
            .pagination span {
                min-width: 34px;
                height: 34px;
                padding: 6px 9px;
            }

            .bulk-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .bulk-bar span {
                margin-right: 0;
            }

            .bulk-bar select {
                width: 100%;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <!-- =========================================================
             Header
        ========================================================== -->

        <div class="topbar">

            <div>

                <h1>
                    📦 Product Management
                </h1>

                <p>
                    Search, filter, sort and manage products.
                </p>

            </div>

            <div class="top-actions">

                <a
                    href="{{ route('products.create') }}"
                    class="btn btn-primary">
                    + Add Product
                </a>

                <a
                    href="{{ route('audit.dashboard') }}"
                    class="btn btn-dark">
                    📊 Audit Dashboard
                </a>

                <a
                    href="{{ route('audit.index') }}"
                    class="btn btn-purple">
                    🔎 Audit Logs
                </a>

            </div>

        </div>


        <!-- =========================================================
             Success Message
        ========================================================== -->

        @if(session('success'))

            <div class="success">
                ✅ {{ session('success') }}
            </div>

        @endif


        <!-- =========================================================
             Error Messages
        ========================================================== -->

        @if($errors->any())

            <div class="error">

                <strong>
                    Please fix the following:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- =========================================================
             Statistics
        ========================================================== -->

        <div class="stats">

            <div class="stat">

                <div class="stat-title">
                    Total Products
                </div>

                <div class="stat-value">
                    {{ $totalProducts }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Active Products
                </div>

                <div class="stat-value">
                    {{ $activeProducts }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Inactive Products
                </div>

                <div class="stat-value">
                    {{ $inactiveProducts }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Inventory Value
                </div>

                <div class="stat-value">
                    ₹{{ number_format($totalValue) }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Average Price
                </div>

                <div class="stat-value">
                    ₹{{ number_format($averagePrice, 2) }}
                </div>

            </div>

        </div>


        <!-- =========================================================
             Search + Filters
        ========================================================== -->

        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('products.index') }}">

                <div class="filter-grid">

                    <!-- Search -->

                    <div>

                        <label>
                            Search Product
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by product name">

                    </div>


                    <!-- Status -->

                    <div>

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <!-- Minimum Price -->

                    <div>

                        <label>
                            Minimum Price
                        </label>

                        <input
                            type="number"
                            name="min_price"
                            value="{{ request('min_price') }}"
                            min="0"
                            placeholder="₹ Minimum">

                    </div>


                    <!-- Maximum Price -->

                    <div>

                        <label>
                            Maximum Price
                        </label>

                        <input
                            type="number"
                            name="max_price"
                            value="{{ request('max_price') }}"
                            min="0"
                            placeholder="₹ Maximum">

                    </div>


                    <!-- Sort -->

                    <div>

                        <label>
                            Sort By
                        </label>

                        <select name="sort">

                            <option
                                value="latest"
                                {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                                Newest
                            </option>

                            <option
                                value="oldest"
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                Oldest
                            </option>

                            <option
                                value="price_low"
                                {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                Price Low → High
                            </option>

                            <option
                                value="price_high"
                                {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                Price High → Low
                            </option>

                            <option
                                value="name_az"
                                {{ request('sort') === 'name_az' ? 'selected' : '' }}>
                                Name A → Z
                            </option>

                            <option
                                value="name_za"
                                {{ request('sort') === 'name_za' ? 'selected' : '' }}>
                                Name Z → A
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Filter Buttons -->

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        🔍 Apply Filters
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-gray">
                        ↻ Reset
                    </a>

                </div>

            </form>

        </div>


        <!-- =========================================================
             Bulk Status Form
        ========================================================== -->

        <form
            method="POST"
            action="{{ route('products.bulk-status') }}"
            id="bulkStatusForm">

            @csrf

            @method('PATCH')

            <div class="bulk-bar">

                <span>
                    Selected:
                    <strong id="selectedCount">0</strong>
                </span>


                <select
                    name="bulk_status"
                    id="bulkStatus">

                    <option value="">
                        Change Status
                    </option>

                    <option value="active">
                        Set Active
                    </option>

                    <option value="inactive">
                        Set Inactive
                    </option>

                </select>


                <button
                    type="submit"
                    class="btn btn-success"
                    onclick="return prepareBulkStatus()">
                    🔄 Update Status
                </button>


                <button
                    type="button"
                    class="btn btn-danger"
                    onclick="bulkDelete()">
                    🗑️ Delete Selected
                </button>

            </div>

        </form>


        <!-- =========================================================
             Product Table
        ========================================================== -->

        <div class="table-card">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>

                                <input
                                    type="checkbox"
                                    class="checkbox"
                                    id="selectAll">

                            </th>

                            <th>
                                ID
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                <!-- Checkbox -->

                                <td>

                                    <input
                                        type="checkbox"
                                        class="checkbox product-checkbox"
                                        value="{{ $product->id }}">

                                </td>


                                <!-- ID -->

                                <td>
                                    #{{ $product->id }}
                                </td>


                                <!-- Product -->

                                <td>

                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>

                                </td>


                                <!-- Price -->

                                <td>

                                    <span class="price">
                                        ₹{{ number_format($product->price) }}
                                    </span>

                                </td>


                                <!-- Status -->

                                <td>

                                    <span
                                        class="status {{ $product->status }}">
                                        {{ ucfirst($product->status) }}
                                    </span>

                                </td>


                                <!-- Actions -->

                                <td>

                                    <div class="actions">

                                        <!-- Edit -->

                                        <a
                                            href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-success">
                                            Edit
                                        </a>


                                        <!-- Audits -->

                                        <a
                                            href="{{ route('products.audits', $product->id) }}"
                                            class="btn btn-purple">
                                            Audits
                                        </a>


                                        <!-- Duplicate -->

                                        <form
                                            method="POST"
                                            action="{{ route('products.duplicate', $product->id) }}">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-warning">
                                                Copy
                                            </button>

                                        </form>


                                        <!-- Delete -->

                                        <form
                                            method="POST"
                                            action="{{ route('products.destroy', $product->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="empty">

                                    No products found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- =====================================================
                 Numeric-only Pagination
            ====================================================== -->

            @if($products->hasPages())

                <div class="pagination">

                    @php

                        $current = $products->currentPage();

                        $last = $products->lastPage();

                        $start = max(
                            1,
                            $current - 1
                        );

                        $end = min(
                            $last,
                            $current + 1
                        );

                    @endphp


                    {{-- First Page --}}

                    @if($start > 1)

                        <a href="{{ $products->url(1) }}">
                            1
                        </a>

                        @if($start > 2)

                            <span class="dots">
                                ...
                            </span>

                        @endif

                    @endif


                    {{-- Pages Around Current Page --}}

                    @for($page = $start; $page <= $end; $page++)

                        @if($page == $current)

                            <span class="active-page">
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $products->url($page) }}">
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last Page --}}

                    @if($end < $last)

                        @if($end < $last - 1)

                            <span class="dots">
                                ...
                            </span>

                        @endif

                        <a href="{{ $products->url($last) }}">
                            {{ $last }}
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>


    <!-- =============================================================
         JavaScript
    ============================================================= -->

    <script>

        /*
        |--------------------------------------------------------------------------
        | Select All
        |--------------------------------------------------------------------------
        */

        const selectAll =
            document.getElementById('selectAll');

        const checkboxes =
            document.querySelectorAll('.product-checkbox');

        const selectedCount =
            document.getElementById('selectedCount');


        /*
        |--------------------------------------------------------------------------
        | Select All Change
        |--------------------------------------------------------------------------
        */

        selectAll.addEventListener('change', function () {

            checkboxes.forEach(function (checkbox) {

                checkbox.checked =
                    selectAll.checked;

            });

            updateSelectedCount();

        });


        /*
        |--------------------------------------------------------------------------
        | Individual Checkbox Change
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                updateSelectedCount();

                const checkedCount =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    ).length;

                const totalCount =
                    checkboxes.length;

                selectAll.checked =
                    totalCount > 0 &&
                    checkedCount === totalCount;

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Update Selected Count
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {

            const selected =
                document.querySelectorAll(
                    '.product-checkbox:checked'
                ).length;

            selectedCount.textContent =
                selected;

        }


        /*
        |--------------------------------------------------------------------------
        | Get Selected IDs
        |--------------------------------------------------------------------------
        */

        function getSelectedIds() {

            return Array.from(
                document.querySelectorAll(
                    '.product-checkbox:checked'
                )
            ).map(function (checkbox) {

                return checkbox.value;

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Delete
        |--------------------------------------------------------------------------
        */

        function bulkDelete() {

            const ids =
                getSelectedIds();


            /*
            | No Product Selected
            */

            if (ids.length === 0) {

                alert(
                    'Please select at least one product.'
                );

                return;

            }


            /*
            | Confirmation
            */

            const confirmed =
                confirm(
                    'Are you sure you want to delete ' +
                    ids.length +
                    ' selected product(s)?'
                );


            if (!confirmed) {

                return;

            }


            /*
            | Create Form
            */

            const form =
                document.createElement('form');

            form.method =
                'POST';

            form.action =
                "{{ route('products.bulk-delete') }}";


            /*
            | CSRF Token
            */

            const csrf =
                document.createElement('input');

            csrf.type =
                'hidden';

            csrf.name =
                '_token';

            csrf.value =
                "{{ csrf_token() }}";

            form.appendChild(csrf);


            /*
            | DELETE Method
            */

            const method =
                document.createElement('input');

            method.type =
                'hidden';

            method.name =
                '_method';

            method.value =
                'DELETE';

            form.appendChild(method);


            /*
            | Product IDs
            */

            ids.forEach(function (id) {

                const input =
                    document.createElement('input');

                input.type =
                    'hidden';

                input.name =
                    'product_ids[]';

                input.value =
                    id;

                form.appendChild(input);

            });


            /*
            | Submit
            */

            document.body.appendChild(form);

            form.submit();

        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Status
        |--------------------------------------------------------------------------
        */

        function prepareBulkStatus() {

            const ids =
                getSelectedIds();


            /*
            | No Product Selected
            */

            if (ids.length === 0) {

                alert(
                    'Please select at least one product.'
                );

                return false;

            }


            /*
            | Get Status
            */

            const status =
                document.getElementById(
                    'bulkStatus'
                ).value;


            /*
            | Status Required
            */

            if (!status) {

                alert(
                    'Please select a status.'
                );

                return false;

            }


            /*
            | Confirmation
            */

            const confirmed =
                confirm(
                    'Change status of ' +
                    ids.length +
                    ' selected product(s)?'
                );


            if (!confirmed) {

                return false;

            }


            /*
            | Add Product IDs to Form
            */

            const form =
                document.getElementById(
                    'bulkStatusForm'
                );


            /*
            | Prevent Duplicate Hidden Inputs
            */

            form.querySelectorAll(
                'input[name="product_ids[]"]'
            ).forEach(function (input) {

                input.remove();

            });


            /*
            | Add Selected IDs
            */

            ids.forEach(function (id) {

                const input =
                    document.createElement('input');

                input.type =
                    'hidden';

                input.name =
                    'product_ids[]';

                input.value =
                    id;

                form.appendChild(input);

            });


            return true;

        }

    </script>

</body>

</html>
