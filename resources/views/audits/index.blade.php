<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Audit Logs</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 21px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            background: #374151;
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: #4b5563;
        }

        .container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .filter-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.07);
            margin-bottom: 25px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .actions {
            display: flex;
            align-items: end;
            gap: 10px;
            margin-top: 18px;
        }

        .btn {
            display: inline-block;
            border: none;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-search {
            background: #2563eb;
            color: white;
        }

        .btn-search:hover {
            background: #1d4ed8;
        }

        .btn-reset {
            background: #6b7280;
            color: white;
        }

        .btn-reset:hover {
            background: #4b5563;
        }

        .btn-export {
            background: #16a34a;
            color: white;
        }

        .btn-export:hover {
            background: #15803d;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .results-count {
            color: #6b7280;
            font-size: 14px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th {
            background: #f9fafb;
            padding: 13px 15px;
            text-align: left;
            font-size: 13px;
            color: #6b7280;
            white-space: nowrap;
        }

        td {
            padding: 14px 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }

        .event-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }

        .event-created {
            background: #16a34a;
        }

        .event-updated {
            background: #2563eb;
        }

        .event-deleted {
            background: #dc2626;
        }

        .event-restored {
            background: #7c3aed;
        }

        .product-name {
            font-weight: 600;
        }

        .user-name {
            font-weight: 600;
        }

        .user-email {
            color: #6b7280;
            font-size: 12px;
            margin-top: 3px;
        }

        .date {
            white-space: nowrap;
        }

        .ip {
            font-family: monospace;
            font-size: 12px;
        }

        .pagination {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: flex;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            text-decoration: none;
            color: #374151;
            font-size: 13px;
        }

        .pagination span[aria-current="page"] {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .empty {
            text-align: center;
            padding: 45px;
            color: #6b7280;
        }

        .export-note {
            margin-top: 10px;
            color: #6b7280;
            font-size: 12px;
        }

        @media (max-width: 1000px) {

            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
            }

            .actions {
                flex-wrap: wrap;
            }

        }

    </style>

</head>

<body>

<nav class="navbar">

    <h2>🔐 Laravel Audit System</h2>

    <div class="nav-links">
        <a href="{{ route('audit.dashboard') }}">
            Audit Dashboard
        </a>

        <a href="{{ route('products.index') }}">
            Products
        </a>

        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </div>

</nav>

<div class="container">

    <div class="header">

        <h1>🔎 Audit Logs</h1>

        <p>
            Search, filter and export product audit history.
        </p>

    </div>

    <!-- Filters -->

    <div class="filter-card">

        <form method="GET" action="{{ route('audit.index') }}">

            <div class="filter-grid">

                <div class="form-group">

                    <label for="search">
                        Search
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Product name, user name or email"
                    >

                </div>

                <div class="form-group">

                    <label for="event">
                        Event
                    </label>

                    <select name="event" id="event">

                        <option value="">
                            All Events
                        </option>

                        <option
                            value="created"
                            {{ request('event') === 'created' ? 'selected' : '' }}
                        >
                            Created
                        </option>

                        <option
                            value="updated"
                            {{ request('event') === 'updated' ? 'selected' : '' }}
                        >
                            Updated
                        </option>

                        <option
                            value="deleted"
                            {{ request('event') === 'deleted' ? 'selected' : '' }}
                        >
                            Deleted
                        </option>

                        <option
                            value="restored"
                            {{ request('event') === 'restored' ? 'selected' : '' }}
                        >
                            Restored
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label for="user_id">
                        User
                    </label>

                    <select name="user_id" id="user_id">

                        <option value="">
                            All Users
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">

                    <label for="from_date">
                        From Date
                    </label>

                    <input
                        type="date"
                        id="from_date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                    >

                </div>

                <div class="form-group">

                    <label for="to_date">
                        To Date
                    </label>

                    <input
                        type="date"
                        id="to_date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                    >

                </div>

            </div>

            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-search"
                >
                    🔍 Search / Filter
                </button>

                <a
                    href="{{ route('audit.index') }}"
                    class="btn btn-reset"
                >
                    ↻ Reset
                </a>

                <a
                    href="{{ route('audit.export', request()->query()) }}"
                    class="btn btn-export"
                >
                    📥 Export CSV
                </a>

            </div>

            <div class="export-note">
                CSV export uses the same filters currently selected above.
            </div>

        </form>

    </div>

    <!-- Audit Table -->

    <div class="table-card">

        <div class="table-header">

            <h2>
                Audit Records
            </h2>

            <div class="results-count">
                {{ $audits->total() }} record(s) found
            </div>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($audits as $audit)

                        <tr>

                            <td>
                                #{{ $audit->id }}
                            </td>

                            <td>

                                <span class="event-badge event-{{ $audit->event }}">
                                    {{ ucfirst($audit->event) }}
                                </span>

                            </td>

                            <td>

                                <div class="product-name">
                                    {{ $audit->auditable?->name ?? 'N/A' }}
                                </div>

                                @if($audit->auditable_id)
                                    <small>
                                        Product ID: {{ $audit->auditable_id }}
                                    </small>
                                @endif

                            </td>

                            <td>

                                <div class="user-name">
                                    {{ $audit->user?->name ?? 'System' }}
                                </div>

                                @if($audit->user)

                                    <div class="user-email">
                                        {{ $audit->user->email }}
                                    </div>

                                @endif

                            </td>

                            <td>

                                <span class="ip">
                                    {{ $audit->ip_address ?? 'N/A' }}
                                </span>

                            </td>

                            <td class="date">

                                {{ $audit->created_at->format('d M Y') }}

                                <br>

                                <small>
                                    {{ $audit->created_at->format('h:i A') }}
                                </small>

                            </td>

                            <td>

                                <a
                                    href="{{ route('products.audits', $audit->auditable_id) }}"
                                    class="btn btn-search"
                                    style="padding: 7px 10px;"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty"
                            >
                                No audit records found for the selected filters.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($audits->hasPages())

            <div class="pagination">

                {{ $audits->links() }}

            </div>

        @endif

    </div>

</div>

</body>

</html>