<!DOCTYPE html>
<html>

<head>

    <title>Audit History - {{ $product->name }}</title>

    <style>

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 40px;
            margin: 0;
        }

        .container {
            max-width: 950px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .top-actions {
            margin-bottom: 25px;
        }

        .back-btn,
        .dashboard-btn,
        .logs-btn {
            display: inline-block;
            margin-right: 8px;
            padding: 8px 14px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-btn {
            background: #6c757d;
        }

        .dashboard-btn {
            background: #111827;
        }

        .logs-btn {
            background: #7c3aed;
        }

        h2 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #3490dc;
        }

        .audit {
            position: relative;
            background: #f9fafb;
            padding: 20px 25px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .audit::before {
            content: "";
            position: absolute;
            left: -24px;
            top: 25px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #3490dc;
        }

        .audit.created::before {
            background: #38c172;
        }

        .audit.updated::before {
            background: #3490dc;
        }

        .audit.deleted::before {
            background: #e3342f;
        }

        .audit.restored::before {
            background: #7c3aed;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-bottom: 10px;
        }

        .created-badge {
            background: #38c172;
        }

        .updated-badge {
            background: #3490dc;
        }

        .deleted-badge {
            background: #e3342f;
        }

        .restored-badge {
            background: #7c3aed;
        }

        .meta {
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
        }

        .changes {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .change-row {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .change-row:last-child {
            margin-bottom: 0;
        }

        .field {
            font-weight: 600;
        }

        .old {
            color: #e3342f;
            font-weight: bold;
        }

        .new {
            color: #38c172;
            font-weight: bold;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #6b7280;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="top-actions">

        <a
            href="{{ route('products.index') }}"
            class="back-btn"
        >
            ⬅ Back to Products
        </a>

        <a
            href="{{ route('audit.dashboard') }}"
            class="dashboard-btn"
        >
            📊 Audit Dashboard
        </a>

        <a
            href="{{ route('audit.index') }}"
            class="logs-btn"
        >
            🔎 All Audit Logs
        </a>

    </div>

    <h2>
        Audit History
    </h2>

    <div class="subtitle">
        Product: <strong>{{ $product->name }}</strong>
        |
        Product ID: {{ $product->id }}
    </div>

    <div class="timeline">

        @forelse($audits as $audit)

            <div class="audit {{ $audit->event }}">

                <span
                    class="badge
                    {{ $audit->event == 'created' ? 'created-badge' : '' }}
                    {{ $audit->event == 'updated' ? 'updated-badge' : '' }}
                    {{ $audit->event == 'deleted' ? 'deleted-badge' : '' }}
                    {{ $audit->event == 'restored' ? 'restored-badge' : '' }}"
                >
                    {{ ucfirst($audit->event) }}
                </span>

                <div class="meta">

                    <strong>User:</strong>
                    {{ $audit->user ? $audit->user->name : 'System' }}

                    |

                    <strong>Date:</strong>
                    {{ $audit->created_at->format('d M Y, h:i A') }}

                    |

                    <strong>IP:</strong>
                    {{ $audit->ip_address ?? 'N/A' }}

                </div>

                <div class="changes">

                    <strong>Changes:</strong>

                    @php
                        $oldValues = $audit->old_values ?? [];
                        $newValues = $audit->new_values ?? [];

                        $fields = array_unique(
                            array_merge(
                                array_keys($oldValues),
                                array_keys($newValues)
                            )
                        );
                    @endphp

                    @forelse($fields as $field)

                        <div class="change-row">

                            <span class="field">
                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                            </span>

                            :

                            <span class="old">

                                @if(array_key_exists($field, $oldValues))

                                    @if(is_numeric($oldValues[$field]))
                                        ₹{{ number_format((float) $oldValues[$field]) }}
                                    @else
                                        {{ $oldValues[$field] }}
                                    @endif

                                @else

                                    N/A

                                @endif

                            </span>

                            →

                            <span class="new">

                                @if(array_key_exists($field, $newValues))

                                    @if(is_numeric($newValues[$field]))
                                        ₹{{ number_format((float) $newValues[$field]) }}
                                    @else
                                        {{ $newValues[$field] }}
                                    @endif

                                @else

                                    N/A

                                @endif

                            </span>

                        </div>

                    @empty

                        <div class="change-row">
                            No field changes recorded.
                        </div>

                    @endforelse

                </div>

            </div>

        @empty

            <div class="empty">
                No audit history found for this product.
            </div>

        @endforelse

    </div>

</div>

</body>

</html>