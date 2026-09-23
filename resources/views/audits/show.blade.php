<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Details &amp; Visual Diff Inspector - Audit #{{ $audit->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f4f6f9; font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6 md:p-10">

    <div class="max-w-6xl mx-auto">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-xl shadow-lg mb-6 flex justify-between items-center">
                <span>✅ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-xl font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-xl shadow-lg mb-6 flex justify-between items-center">
                <span>❌ {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-xl font-bold">&times;</button>
            </div>
        @endif

        {{-- Header Bar --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Audit Detail #{{ $audit->id }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Event: <span class="uppercase font-bold text-indigo-600">{{ $audit->event }}</span> | Date: {{ $audit->created_at?->format('d M Y, h:i A') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('audit.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg font-semibold hover:bg-gray-800 transition">
                    ← Back to Audit Logs
                </a>
                <form method="POST" action="{{ route('audit.rollback', $audit->id) }}" onsubmit="return confirm('Rollback database state to this historical audit snapshot?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg shadow-md transition">
                        ⏪ 1-Click Rollback
                    </button>
                </form>
            </div>
        </div>

        {{-- Audit Meta Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">
                📋 Audit Meta Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg border">
                    <span class="text-xs text-gray-500 block">Auditable Model</span>
                    <strong class="text-gray-800">{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</strong>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border">
                    <span class="text-xs text-gray-500 block">User Responsible</span>
                    <strong class="text-gray-800">{{ $audit->user?->name ?? 'System' }} ({{ $audit->user?->email ?? 'N/A' }})</strong>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border">
                    <span class="text-xs text-gray-500 block">IP Address</span>
                    <strong class="text-gray-800 font-mono">{{ $audit->ip_address ?? '127.0.0.1' }}</strong>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border">
                    <span class="text-xs text-gray-500 block">URL Path</span>
                    <strong class="text-gray-800 font-mono truncate block">{{ $audit->url ?? '/' }}</strong>
                </div>
            </div>
        </div>

        {{-- Deep Side-by-Side Visual Diff Inspector --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-indigo-700 to-purple-700 p-5 text-white flex justify-between items-center">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    🔍 Deep Side-by-Side Visual Diff Inspector
                </h2>
                <span class="px-3 py-1 bg-white/20 text-xs font-semibold rounded-full">Before vs After Comparison</span>
            </div>

            <div class="p-6">
                @php
                    $oldValues = $audit->old_values ?? [];
                    $newValues = $audit->new_values ?? [];
                    $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                @endphp

                @if(!empty($allFields))
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-800 text-white text-left text-xs uppercase tracking-wider">
                                    <th class="p-3 w-1/4">Field Name</th>
                                    <th class="p-3 w-3/8 bg-red-900 text-red-100">Before (Old State)</th>
                                    <th class="p-3 w-3/8 bg-green-900 text-green-100">After (New State)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($allFields as $field)
                                    @php
                                        $oldVal = $oldValues[$field] ?? null;
                                        $newVal = $newValues[$field] ?? null;
                                        $isChanged = $oldVal !== $newVal;
                                    @endphp
                                    <tr class="{{ $isChanged ? 'bg-amber-50/50' : 'bg-white' }}">
                                        <td class="p-3 text-sm">
                                            <span class="font-bold text-gray-700 uppercase">{{ str_replace('_', ' ', $field) }}</span>
                                            @if($isChanged)
                                                <span class="ml-2 px-2 py-0.5 bg-amber-200 text-amber-900 text-xs font-bold rounded-full">MODIFIED</span>
                                            @else
                                                <span class="ml-2 px-2 py-0.5 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">UNCHANGED</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm font-mono {{ $isChanged ? 'bg-red-50 text-red-700 font-bold' : 'text-gray-600' }}">
                                            @if($oldVal !== null)
                                                <span class="{{ $isChanged ? 'line-through' : '' }}">
                                                    {{ is_array($oldVal) ? json_encode($oldVal) : $oldVal }}
                                                </span>
                                            @else
                                                <em class="text-gray-400 font-normal">[NONE / CREATED]</em>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm font-mono {{ $isChanged ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600' }}">
                                            @if($newVal !== null)
                                                <span>
                                                    {{ is_array($newVal) ? json_encode($newVal) : $newVal }}
                                                </span>
                                            @else
                                                <em class="text-gray-400 font-normal">[DELETED]</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-100 text-gray-600 p-4 rounded-xl text-center">
                        No modified field values recorded for this audit entry.
                    </div>
                @endif
            </div>
        </div>

    </div>

</body>
</html>
