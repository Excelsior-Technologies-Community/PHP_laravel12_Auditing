<!DOCTYPE html>
<html>
<head>
    <title>Audit History</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 25px;
            padding: 8px 14px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        h2 {
            margin-bottom: 30px;
        }

        /* Timeline */
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

        .audit.created::before { background: #38c172; }
        .audit.updated::before { background: #3490dc; }
        .audit.deleted::before { background: #e3342f; }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-bottom: 10px;
        }

        .created-badge { background: #38c172; }
        .updated-badge { background: #3490dc; }
        .deleted-badge { background: #e3342f; }

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
    </style>
</head>
<body>

<div class="container">

    <a href="{{ route('products.index') }}" class="back-btn">
        ⬅ Back to Products
    </a>

    <h2>Audit History</h2>

    <div class="timeline">

        @forelse($audits as $audit)

            <div class="audit {{ $audit->event }}">

                {{-- Event Badge --}}
                <span class="badge 
                    {{ $audit->event == 'created' ? 'created-badge' : '' }}
                    {{ $audit->event == 'updated' ? 'updated-badge' : '' }}
                    {{ $audit->event == 'deleted' ? 'deleted-badge' : '' }}">
                    {{ ucfirst($audit->event) }}
                </span>

                {{-- Meta Info --}}
                <div class="meta">
                    <strong>User:</strong>
                    {{ $audit->user ? $audit->user->name : 'System' }}
                    |
                    <strong>Date:</strong>
                    {{ $audit->created_at->format('d M Y, h:i A') }}
                </div>

                {{-- Changes --}}
                <div class="changes">
                    <strong>Changes:</strong>

                    @foreach($audit->new_values as $field => $value)

                        <div class="change-row">
                            <span class="field">
                                {{ ucfirst($field) }}
                            </span> :

                            {{-- OLD VALUE --}}
                            <span class="old">
                                @if(isset($audit->old_values[$field]))
                                    @if(is_numeric($audit->old_values[$field]))
                                        ₹{{ number_format((float)$audit->old_values[$field]) }}
                                    @else
                                        {{ $audit->old_values[$field] }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>

                            →

                            {{-- NEW VALUE --}}
                            <span class="new">
                                @if(is_numeric($value))
                                    ₹{{ number_format((float)$value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        @empty
            <p>No audit history found.</p>
        @endforelse

    </div>

</div>

</body>
</html>
