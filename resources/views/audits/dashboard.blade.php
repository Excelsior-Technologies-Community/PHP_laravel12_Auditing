<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Audit Dashboard</title>

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
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            margin: 0 0 6px;
            font-size: 30px;
        }

        .page-title p {
            margin: 0;
            color: #6b7280;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.07);
        }

        .stat-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
        }

        .created {
            border-left: 5px solid #16a34a;
        }

        .updated {
            border-left: 5px solid #2563eb;
        }

        .deleted {
            border-left: 5px solid #dc2626;
        }

        .total {
            border-left: 5px solid #7c3aed;
        }

        .today {
            border-left: 5px solid #f59e0b;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.07);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .user-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-row:last-child {
            border-bottom: none;
        }

        .user-name {
            font-weight: 600;
        }

        .user-email {
            color: #6b7280;
            font-size: 13px;
            margin-top: 3px;
        }

        .count {
            background: #eef2ff;
            color: #3730a3;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            background: #f9fafb;
            padding: 12px;
            font-size: 13px;
            color: #6b7280;
        }

        td {
            padding: 13px 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-created {
            background: #16a34a;
        }

        .badge-updated {
            background: #2563eb;
        }

        .badge-deleted {
            background: #dc2626;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 25px;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
            }

            .table-wrapper {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <h2>🔐 Laravel Audit System</h2>

    <div class="nav-links">
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('audit.index') }}">Audit Logs</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </div>
</nav>

<div class="container">

    <div class="page-title">
        <h1>📊 Audit Dashboard</h1>
        <p>Overview of product changes and user activity.</p>
    </div>

    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card total">
            <div class="stat-title">Total Audits</div>
            <div class="stat-number">{{ $totalAudits }}</div>
        </div>

        <div class="stat-card created">
            <div class="stat-title">Created</div>
            <div class="stat-number">{{ $createdAudits }}</div>
        </div>

        <div class="stat-card updated">
            <div class="stat-title">Updated</div>
            <div class="stat-number">{{ $updatedAudits }}</div>
        </div>

        <div class="stat-card deleted">
            <div class="stat-title">Deleted</div>
            <div class="stat-number">{{ $deletedAudits }}</div>
        </div>

        <div class="stat-card today">
            <div class="stat-title">Today's Activity</div>
            <div class="stat-number">{{ $todayAudits }}</div>
        </div>

    </div>

    <div class="grid">

        <!-- Most Active Users -->

        <div class="card">

            <h2>👤 Most Active Users</h2>

            @forelse($activeUsers as $activeUser)

                @php
                    $user = \App\Models\User::find($activeUser->user_id);
                @endphp

                <div class="user-row">

                    <div>
                        <div class="user-name">
                            {{ $user?->name ?? 'Unknown User' }}
                        </div>

                        @if($user)
                            <div class="user-email">
                                {{ $user->email }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <span class="count">
                            {{ $activeUser->total }} changes
                        </span>
                    </div>

                </div>

            @empty

                <div class="empty">
                    No user activity found.
                </div>

            @endforelse

        </div>

        <!-- Recent Activity -->

        <div class="card">

            <h2>🕒 Recent Audit Activity</h2>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Product</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentAudits as $audit)

                            <tr>

                                <td>
                                    <span class="badge badge-{{ $audit->event }}">
                                        {{ ucfirst($audit->event) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $audit->auditable?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $audit->user?->name ?? 'System' }}
                                </td>

                                <td>
                                    {{ $audit->created_at->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    No audit activity found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>