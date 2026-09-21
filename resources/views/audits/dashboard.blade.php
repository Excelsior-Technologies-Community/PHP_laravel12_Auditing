<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Audit Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .navbar-title {
            font-size: 22px;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 9px 14px;
            border-radius: 8px;
            background: #374151;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-links a:hover {
            background: #4b5563;
        }

        .container {
            width: 94%;
            max-width: 1400px;
            margin: 30px auto;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            border-left: 5px solid #2563eb;
        }

        .stat-card.created {
            border-left-color: #16a34a;
        }

        .stat-card.updated {
            border-left-color: #2563eb;
        }

        .stat-card.deleted {
            border-left-color: #dc2626;
        }

        .stat-card.today {
            border-left-color: #9333ea;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
        }

        .card-body {
            padding: 20px;
        }

        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }

        .user-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .user-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #eef0f3;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-name {
            font-weight: 600;
        }

        .user-count {
            background: #eff6ff;
            color: #2563eb;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .table-card {
            margin-top: 25px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 13px;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eef0f3;
            font-size: 14px;
        }

        tr:hover td {
            background: #fafafa;
        }

        .event-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .event-created {
            background: #dcfce7;
            color: #166534;
        }

        .event-updated {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .event-deleted {
            background: #fee2e2;
            color: #991b1b;
        }

        .event-restored {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .event-default {
            background: #f3f4f6;
            color: #374151;
        }

        .product-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .product-link:hover {
            text-decoration: underline;
        }

        .view-button {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
        }

        .view-button:hover {
            background: #1d4ed8;
        }

        .empty-state {
            text-align: center;
            padding: 35px 15px;
            color: #6b7280;
        }

        .chart-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .chart-stat {
            padding: 12px;
            background: #f9fafb;
            border-radius: 9px;
            text-align: center;
        }

        .chart-stat strong {
            display: block;
            font-size: 20px;
            margin-bottom: 4px;
        }

        .chart-stat span {
            font-size: 12px;
            color: #6b7280;
        }

        @media (max-width: 1100px) {

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 700px) {

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .chart-summary {
                grid-template-columns: 1fr 1fr;
            }

            .navbar {
                padding: 15px;
            }

            .container {
                width: 96%;
                margin: 20px auto;
            }

            .page-header h1 {
                font-size: 24px;
            }

        }

        @media (max-width: 450px) {

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .chart-summary {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    {{-- =========================
         NAVIGATION
    ========================== --}}

    <nav class="navbar">

        <div class="navbar-title">
            Laravel Audit Dashboard
        </div>

        <div class="nav-links">

            <a href="{{ route('products.index') }}">
                Products
            </a>

            <a href="{{ route('audit.dashboard') }}">
                Audit Dashboard
            </a>

            <a href="{{ route('audit.index') }}">
                Audit Logs
            </a>

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

        </div>

    </nav>


    <main class="container">

        {{-- =========================
             PAGE HEADER
        ========================== --}}

        <div class="page-header">

            <h1>
                Audit Activity Overview
            </h1>

            <p>
                Monitor product changes, user activity and audit events.
            </p>

        </div>


        {{-- =========================
             SUCCESS MESSAGE
        ========================== --}}

        @if(session('success'))

            <div
                style="
                    background:#dcfce7;
                    color:#166534;
                    padding:14px 18px;
                    border-radius:10px;
                    margin-bottom:20px;
                    border:1px solid #bbf7d0;
                "
            >
                {{ session('success') }}
            </div>

        @endif


        {{-- =========================
             STAT CARDS
        ========================== --}}

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-label">
                    Total Audits
                </div>

                <div class="stat-value">
                    {{ number_format($totalAudits ?? 0) }}
                </div>

            </div>


            <div class="stat-card created">

                <div class="stat-label">
                    Created
                </div>

                <div class="stat-value">
                    {{ number_format($created ?? 0) }}
                </div>

            </div>


            <div class="stat-card updated">

                <div class="stat-label">
                    Updated
                </div>

                <div class="stat-value">
                    {{ number_format($updated ?? 0) }}
                </div>

            </div>


            <div class="stat-card deleted">

                <div class="stat-label">
                    Deleted
                </div>

                <div class="stat-value">
                    {{ number_format($deleted ?? 0) }}
                </div>

            </div>


            <div class="stat-card today">

                <div class="stat-label">
                    Today's Activity
                </div>

                <div class="stat-value">
                    {{ number_format($today ?? 0) }}
                </div>

            </div>

        </div>


        {{-- =========================
             CHART + ACTIVE USERS
        ========================== --}}

        <div class="content-grid">

            {{-- =========================
                 AUDIT EVENT CHART
            ========================== --}}

            <div class="card">

                <div class="card-header">

                    <h2>
                        Audit Event Chart
                    </h2>

                    <span style="color:#6b7280;font-size:13px;">
                        Event summary
                    </span>

                </div>

                <div class="card-body">

                    @php

                        $eventData = [
                            'Created' => $eventChart['created'] ?? $created ?? 0,
                            'Updated' => $eventChart['updated'] ?? $updated ?? 0,
                            'Deleted' => $eventChart['deleted'] ?? $deleted ?? 0,
                            'Restored' => $eventChart['restored'] ?? 0,
                        ];

                    @endphp


                    <div class="chart-summary">

                        <div class="chart-stat">

                            <strong>
                                {{ number_format($eventData['Created']) }}
                            </strong>

                            <span>
                                Created
                            </span>

                        </div>


                        <div class="chart-stat">

                            <strong>
                                {{ number_format($eventData['Updated']) }}
                            </strong>

                            <span>
                                Updated
                            </span>

                        </div>


                        <div class="chart-stat">

                            <strong>
                                {{ number_format($eventData['Deleted']) }}
                            </strong>

                            <span>
                                Deleted
                            </span>

                        </div>


                        <div class="chart-stat">

                            <strong>
                                {{ number_format($eventData['Restored']) }}
                            </strong>

                            <span>
                                Restored
                            </span>

                        </div>

                    </div>


                    <div class="chart-container">

                        <canvas id="auditEventChart"></canvas>

                    </div>

                </div>

            </div>


            {{-- =========================
                 ACTIVE USERS
            ========================== --}}

            <div class="card">

                <div class="card-header">

                    <h2>
                        Most Active Users
                    </h2>

                    <span style="color:#6b7280;font-size:13px;">
                        Top 5
                    </span>

                </div>

                <div class="card-body">

                    @if(isset($activeUsers) && $activeUsers->count())

                        <ul class="user-list">

                            @foreach($activeUsers as $activeUser)

                                @php

                                    $activeUserModel = null;

                                    if (
                                        !empty($activeUser->user_id)
                                        && class_exists(\App\Models\User::class)
                                    ) {
                                        $activeUserModel =
                                            \App\Models\User::find($activeUser->user_id);
                                    }

                                @endphp

                                <li class="user-item">

                                    <div>

                                        <div class="user-name">

                                            {{ $activeUserModel->name ?? 'Unknown User' }}

                                        </div>

                                        @if($activeUserModel?->email)

                                            <div
                                                style="
                                                    font-size:12px;
                                                    color:#6b7280;
                                                    margin-top:3px;
                                                "
                                            >
                                                {{ $activeUserModel->email }}
                                            </div>

                                        @endif

                                    </div>


                                    <div class="user-count">

                                        {{ number_format($activeUser->total ?? 0) }}

                                        {{ ($activeUser->total ?? 0) == 1 ? 'audit' : 'audits' }}

                                    </div>

                                </li>

                            @endforeach

                        </ul>

                    @else

                        <div class="empty-state">

                            No user activity found.

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================
             RECENT ACTIVITY
        ========================== --}}

        <div class="card table-card">

            <div class="card-header">

                <h2>
                    Recent Audit Activity
                </h2>

                <a
                    href="{{ route('audit.index') }}"
                    class="view-button"
                >
                    View All
                </a>

            </div>


            <div class="table-wrapper">

                @if(isset($recentAudits) && $recentAudits->count())

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    User
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($recentAudits as $audit)

                                @php

                                    $event = strtolower($audit->event ?? 'unknown');

                                    $eventClass = match($event) {

                                        'created' => 'event-created',

                                        'updated' => 'event-updated',

                                        'deleted' => 'event-deleted',

                                        'restored' => 'event-restored',

                                        default => 'event-default',

                                    };

                                @endphp


                                <tr>

                                    <td>

                                        <span
                                            class="event-badge {{ $eventClass }}"
                                        >
                                            {{ ucfirst($event) }}
                                        </span>

                                    </td>


                                    <td>

                                        @if($audit->auditable)

                                            <a
                                                href="{{ route('products.audits', $audit->auditable->id) }}"
                                                class="product-link"
                                            >
                                                {{ $audit->auditable->name }}
                                            </a>

                                        @else

                                            <span style="color:#9ca3af;">
                                                Product deleted
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $audit->user->name ?? 'System' }}

                                    </td>


                                    <td>

                                        {{ $audit->created_at?->format('d M Y, h:i A') }}

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route('audit.show', $audit->id) }}"
                                            class="view-button"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="empty-state">

                        No recent audit activity found.

                    </div>

                @endif

            </div>

        </div>

    </main>


    {{-- =========================
         CHART SCRIPT
    ========================== --}}

    <script>

        const eventChartData = @json(array_values($eventData));

        const eventChartLabels = @json(array_keys($eventData));

        const chartElement =
            document.getElementById('auditEventChart');

        if (chartElement) {

            new Chart(chartElement, {

                type: 'bar',

                data: {

                    labels: eventChartLabels,

                    datasets: [

                        {

                            label: 'Audit Events',

                            data: eventChartData,

                            borderWidth: 1,

                            borderRadius: 8

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            display: false

                        },

                        tooltip: {

                            callbacks: {

                                label: function(context) {

                                    return ' ' + context.raw + ' audit(s)';

                                }

                            }

                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                precision: 0

                            }

                        }

                    }

                }

            });

        }

    </script>

</body>

</html>