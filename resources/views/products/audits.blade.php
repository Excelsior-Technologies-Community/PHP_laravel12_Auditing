<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Audit History - {{ $product->name }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

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
            font-size: 21px;
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
            background: #374151;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: #4b5563;
        }

        .container {
            width: 94%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .header-card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            margin-bottom: 25px;
        }

        .header-card h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header-card p {
            margin: 5px 0;
            color: #6b7280;
        }

        .product-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .summary-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 10px;
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 20px;
            font-weight: 700;
        }

        .back-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            border: none;
            cursor: pointer;
            padding: 9px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .button-primary {
            background: #2563eb;
            color: white;
        }

        .button-primary:hover {
            background: #1d4ed8;
        }

        .button-secondary {
            background: #374151;
            color: white;
        }

        .button-secondary:hover {
            background: #1f2937;
        }

        .timeline {
            position: relative;
            margin-top: 25px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 23px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e5e7eb;
        }

        .audit-card {
            position: relative;
            margin-bottom: 22px;
            margin-left: 55px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .timeline-dot {
            position: absolute;
            left: -42px;
            top: 25px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 4px solid #f5f7fb;
            background: #2563eb;
            box-shadow: 0 0 0 2px #2563eb;
        }

        .timeline-dot.created {
            background: #16a34a;
            box-shadow: 0 0 0 2px #16a34a;
        }

        .timeline-dot.updated {
            background: #2563eb;
            box-shadow: 0 0 0 2px #2563eb;
        }

        .timeline-dot.deleted {
            background: #dc2626;
            box-shadow: 0 0 0 2px #dc2626;
        }

        .timeline-dot.restored {
            background: #9333ea;
            box-shadow: 0 0 0 2px #9333ea;
        }

        .audit-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .audit-event {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .created {
            background: #dcfce7;
            color: #166534;
        }

        .updated {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .deleted {
            background: #fee2e2;
            color: #991b1b;
        }

        .restored {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .unknown {
            background: #f3f4f6;
            color: #374151;
        }

        .audit-date {
            color: #6b7280;
            font-size: 13px;
        }

        .audit-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 18px 20px;
            background: #fafafa;
            border-bottom: 1px solid #e5e7eb;
        }

        .meta-item {
            font-size: 13px;
        }

        .meta-label {
            display: block;
            color: #6b7280;
            margin-bottom: 4px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .meta-value {
            font-weight: 600;
            word-break: break-word;
        }

        .audit-body {
            padding: 20px;
        }

        .changes-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .changes-table-wrapper {
            overflow-x: auto;
        }

        .changes-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        .changes-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px;
            border: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .changes-table td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 13px;
        }

        .old-value {
            background: #fff1f2;
            color: #9f1239;
        }

        .new-value {
            background: #f0fdf4;
            color: #166534;
        }

        .field-name {
            font-weight: 700;
            color: #374151;
        }

        .empty-value {
            color: #9ca3af;
            font-style: italic;
        }

        .json-value {
            white-space: pre-wrap;
            word-break: break-word;
            font-family: Consolas, monospace;
            font-size: 12px;
        }

        .empty-state {
            background: white;
            border-radius: 14px;
            padding: 50px 20px;
            text-align: center;
            color: #6b7280;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
        }

        .audit-count {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 800px) {

            .product-summary {
                grid-template-columns: 1fr;
            }

            .audit-meta {
                grid-template-columns: 1fr;
            }

            .timeline::before {
                left: 10px;
            }

            .audit-card {
                margin-left: 32px;
            }

            .timeline-dot {
                left: -30px;
            }

        }

        @media (max-width: 500px) {

            .container {
                width: 96%;
                margin: 18px auto;
            }

            .header-card h1 {
                font-size: 23px;
            }

            .navbar {
                padding: 14px;
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
            Product Audit History
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
             PRODUCT INFORMATION
        ========================== --}}

        <div class="header-card">

            <h1>
                {{ $product->name }}
            </h1>

            <p>
                Complete audit history for this product.
            </p>


            <div class="product-summary">

                <div class="summary-box">

                    <div class="summary-label">
                        Product ID
                    </div>

                    <div class="summary-value">
                        #{{ $product->id }}
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-label">
                        Price
                    </div>

                    <div class="summary-value">
                        ₹{{ number_format((float) $product->price, 2) }}
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-label">
                        Status
                    </div>

                    <div class="summary-value">

                        {{ ucfirst($product->status) }}

                    </div>

                </div>

            </div>


            <div class="back-buttons">

                <a
                    href="{{ route('products.index') }}"
                    class="button button-secondary">
                    ← Products
                </a>

                <a
                    href="{{ route('audit.index') }}"
                    class="button button-primary">
                    All Audit Logs
                </a>

            </div>

        </div>


        {{-- =========================
             AUDIT COUNT
        ========================== --}}

        <div
            style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:10px;
                margin-bottom:15px;
                flex-wrap:wrap;
            ">

            <h2
                style="
                    margin:0;
                    font-size:22px;
                    font-weight:700;
                ">
                Audit Timeline
            </h2>

            <span class="audit-count">

                {{ $audits->count() }}

                {{ $audits->count() == 1 ? 'Audit' : 'Audits' }}

            </span>

        </div>


        {{-- =========================
             AUDIT TIMELINE
        ========================== --}}

        @if($audits->count())


        <div class="timeline">


            @foreach($audits as $audit)


            @php

            $event = strtolower($audit->event ?? 'unknown');

            $eventClass = match($event) {

            'created' => 'created',

            'updated' => 'updated',

            'deleted' => 'deleted',

            'restored' => 'restored',

            default => 'unknown',

            };


            /*
            * Convert old values to array.
            */

            $oldValues = $audit->old_values ?? [];

            if (!is_array($oldValues)) {

            $decodedOldValues =
            json_decode($oldValues, true);

            $oldValues =
            is_array($decodedOldValues)
            ? $decodedOldValues
            : [];

            }


            /*
            * Convert new values to array.
            */

            $newValues = $audit->new_values ?? [];

            if (!is_array($newValues)) {

            $decodedNewValues =
            json_decode($newValues, true);

            $newValues =
            is_array($decodedNewValues)
            ? $decodedNewValues
            : [];

            }


            /*
            * Combine old and new fields.
            */

            $fields = array_unique(
            array_merge(
            array_keys($oldValues),
            array_keys($newValues)
            )
            );

            @endphp


            <div class="audit-card">


                {{-- TIMELINE DOT --}}

                <div class="timeline-dot {{ $eventClass }}"></div>


                {{-- =========================
                             AUDIT HEADER
                        ========================== --}}

                <div class="audit-header">

                    <div>

                        <span
                            class="audit-event {{ $eventClass }}">
                            {{ ucfirst($event) }}
                        </span>

                    </div>


                    <div class="audit-date">

                        {{ $audit->created_at?->format('d M Y, h:i:s A') }}

                    </div>

                </div>


                {{-- =========================
                             AUDIT META
                        ========================== --}}

                <div class="audit-meta">


                    <div class="meta-item">

                        <span class="meta-label">
                            User
                        </span>

                        <span class="meta-value">

                            @if($audit->user)

                            {{ $audit->user->name }}

                            @if($audit->user->email)

                            <br>

                            <small
                                style="
                                                    color:#6b7280;
                                                    font-weight:normal;
                                                ">
                                {{ $audit->user->email }}
                            </small>

                            @endif

                            @else

                            System

                            @endif

                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            IP Address
                        </span>

                        <span class="meta-value">

                            {{ $audit->ip_address ?? '—' }}

                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            Audit ID
                        </span>

                        <span class="meta-value">

                            #{{ $audit->id }}

                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            URL
                        </span>

                        <span
                            class="meta-value"
                            style="
                                        word-break:break-all;
                                    ">

                            {{ $audit->url ?? '—' }}

                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            HTTP Method
                        </span>

                        <span class="meta-value">

                            {{ $audit->http_method ?? '—' }}

                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            User Agent
                        </span>

                        <span
                            class="meta-value"
                            style="
                                        font-size:12px;
                                        font-weight:normal;
                                    ">

                            {{ $audit->user_agent ?? '—' }}

                        </span>

                    </div>

                </div>


                {{-- =========================
                             CHANGES
                        ========================== --}}

                <div class="audit-body">


                    <div class="changes-title">

                        Changed Values

                    </div>


                    @if(count($fields))


                    <div class="changes-table-wrapper">

                        <table class="changes-table">

                            <thead>

                                <tr>

                                    <th style="width:20%;">
                                        Field
                                    </th>

                                    <th style="width:40%;">
                                        Old Value
                                    </th>

                                    <th style="width:40%;">
                                        New Value
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @foreach($fields as $field)


                                @php

                                $oldValue =
                                $oldValues[$field]
                                ?? null;

                                $newValue =
                                $newValues[$field]
                                ?? null;


                                if (is_array($oldValue)) {

                                $oldDisplay =
                                json_encode(
                                $oldValue,
                                JSON_PRETTY_PRINT
                                | JSON_UNESCAPED_UNICODE
                                );

                                } elseif (
                                $oldValue === null
                                || $oldValue === ''
                                ) {

                                $oldDisplay = '—';

                                } else {

                                $oldDisplay =
                                (string) $oldValue;

                                }


                                if (is_array($newValue)) {

                                $newDisplay =
                                json_encode(
                                $newValue,
                                JSON_PRETTY_PRINT
                                | JSON_UNESCAPED_UNICODE
                                );

                                } elseif (
                                $newValue === null
                                || $newValue === ''
                                ) {

                                $newDisplay = '—';

                                } else {

                                $newDisplay =
                                (string) $newValue;

                                }


                                /*
                                * Price formatting.
                                */

                                if (
                                $field === 'price'
                                && is_numeric($oldValue)
                                ) {

                                $oldDisplay =
                                '₹' .
                                number_format(
                                (float) $oldValue,
                                2
                                );

                                }


                                if (
                                $field === 'price'
                                && is_numeric($newValue)
                                ) {

                                $newDisplay =
                                '₹' .
                                number_format(
                                (float) $newValue,
                                2
                                );

                                }

                                @endphp


                                <tr>

                                    <td>

                                        <span class="field-name">

                                            {{ ucwords(str_replace('_', ' ', $field)) }}

                                        </span>

                                    </td>


                                    <td class="old-value">

                                        @if(
                                        is_array($oldValue)
                                        || is_array($newValue)
                                        )

                                        <div class="json-value">

                                            {{ $oldDisplay }}

                                        </div>

                                        @else

                                        {{ $oldDisplay }}

                                        @endif

                                    </td>


                                    <td class="new-value">

                                        @if(
                                        is_array($oldValue)
                                        || is_array($newValue)
                                        )

                                        <div class="json-value">

                                            {{ $newDisplay }}

                                        </div>

                                        @else

                                        {{ $newDisplay }}

                                        @endif

                                    </td>

                                </tr>


                                @endforeach


                            </tbody>

                        </table>

                    </div>


                    @else


                    <div
                        style="
                                        padding:18px;
                                        background:#f9fafb;
                                        border-radius:9px;
                                        color:#6b7280;
                                    ">

                        No field-level changes were recorded
                        for this audit event.

                    </div>


                    @endif


                    {{-- =========================
                                 AUDIT JSON DATA
                            ========================== --}}

                    @if($audit->old_values || $audit->new_values)

                    <details
                        style="
                                        margin-top:20px;
                                        border:1px solid #e5e7eb;
                                        border-radius:9px;
                                        padding:12px;
                                    ">

                        <summary
                            style="
                                            cursor:pointer;
                                            font-weight:700;
                                        ">
                            View Raw Audit Data
                        </summary>


                        <div
                            style="
                                            margin-top:15px;
                                            display:grid;
                                            grid-template-columns:1fr 1fr;
                                            gap:15px;
                                        ">

                            <div>

                                <h4
                                    style="
                                                    margin:0 0 8px;
                                                    color:#991b1b;
                                                ">
                                    Old Values
                                </h4>

                                <pre
                                    style="
                                                    background:#fff1f2;
                                                    padding:15px;
                                                    border-radius:8px;
                                                    overflow:auto;
                                                    font-size:12px;
                                                ">{{ json_encode($oldValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                            </div>


                            <div>

                                <h4
                                    style="
                                                    margin:0 0 8px;
                                                    color:#166534;
                                                ">
                                    New Values
                                </h4>

                                <pre
                                    style="
                                                    background:#f0fdf4;
                                                    padding:15px;
                                                    border-radius:8px;
                                                    overflow:auto;
                                                    font-size:12px;
                                                ">{{ json_encode($newValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                            </div>

                        </div>

                    </details>

                    @endif


                </div>

            </div>


            @endforeach


        </div>


        @else


        <div class="empty-state">

            <div
                style="
                        font-size:40px;
                        margin-bottom:15px;
                    ">
                📋
            </div>

            <h3
                style="
                        margin:0 0 8px;
                        font-size:20px;
                        color:#374151;
                    ">
                No Audit History
            </h3>

            <p style="margin:0;">
                No audit events have been recorded for this product yet.
            </p>

        </div>


        @endif


    </main>

</body>

</html>