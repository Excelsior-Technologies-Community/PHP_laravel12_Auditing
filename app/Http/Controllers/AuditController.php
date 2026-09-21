<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditController extends Controller
{
    /**
     * Display the audit dashboard.
     */
    public function dashboard()
    {
        $baseQuery = Audit::query()
            ->where('auditable_type', Product::class);

        $totalAudits = (clone $baseQuery)->count();

        $createdAudits = (clone $baseQuery)
            ->where('event', 'created')
            ->count();

        $updatedAudits = (clone $baseQuery)
            ->where('event', 'updated')
            ->count();

        $deletedAudits = (clone $baseQuery)
            ->where('event', 'deleted')
            ->count();

        $todayAudits = (clone $baseQuery)
            ->whereDate('created_at', today())
            ->count();

        $activeUsers = (clone $baseQuery)
            ->whereNotNull('user_id')
            ->where('user_type', User::class)
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentAudits = (clone $baseQuery)
            ->with(['user', 'auditable'])
            ->oldest()
            ->limit(5)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Audit Event Statistics
    |--------------------------------------------------------------------------
    */

        $eventStats = [
            'created' => $createdAudits,
            'updated' => $updatedAudits,
            'deleted' => $deletedAudits,
            'restored' => (clone $baseQuery)
                ->where('event', 'restored')
                ->count(),
        ];

        return view('audits.dashboard', compact(
            'totalAudits',
            'createdAudits',
            'updatedAudits',
            'deletedAudits',
            'todayAudits',
            'activeUsers',
            'recentAudits',
            'eventStats'
        ));
    }



    /**
     * Display searchable and filterable audit logs.
     */
    public function index(Request $request)
    {
        $query = Audit::query()
            ->with(['user', 'auditable'])
            ->where('auditable_type', Product::class);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $productIds = Product::query()
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');

            $query->where(function ($q) use ($search, $productIds) {
                $q->whereIn('auditable_id', $productIds)
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Event Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        /*
        |--------------------------------------------------------------------------
        | User Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user_id')) {
            $query
                ->where('user_id', $request->user_id)
                ->where('user_type', User::class);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $audits = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('audits.index', compact(
            'audits',
            'users'
        ));
    }

    /**
     * Export filtered audit logs to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Audit::query()
            ->with(['user', 'auditable'])
            ->where('auditable_type', Product::class);

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $productIds = Product::query()
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');

            $query->where(function ($q) use ($search, $productIds) {
                $q->whereIn('auditable_id', $productIds)
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Event Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        /*
        |--------------------------------------------------------------------------
        | User Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user_id')) {
            $query
                ->where('user_id', $request->user_id)
                ->where('user_type', User::class);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        /*
        |--------------------------------------------------------------------------
        | CSV File Name
        |--------------------------------------------------------------------------
        */

        $fileName = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {

            $handle = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | UTF-8 BOM
            |--------------------------------------------------------------------------
            |
            | This helps Microsoft Excel correctly display special characters
            | such as the ₹ symbol.
            |
            */

            fwrite($handle, "\xEF\xBB\xBF");

            /*
            |--------------------------------------------------------------------------
            | CSV Header
            |--------------------------------------------------------------------------
            */

            fputcsv($handle, [
                'ID',
                'Event',
                'Product',
                'User',
                'User Email',
                'Changed Fields',
                'Old Values',
                'New Values',
                'IP Address',
                'URL',
                'Date',
            ]);

            /*
            |--------------------------------------------------------------------------
            | CSV Data
            |--------------------------------------------------------------------------
            */

            $query
                ->latest()
                ->chunk(500, function ($audits) use ($handle) {

                    foreach ($audits as $audit) {

                        /*
                        |--------------------------------------------------------------------------
                        | Get old and new values
                        |--------------------------------------------------------------------------
                        */

                        $oldValues = $audit->old_values ?? [];
                        $newValues = $audit->new_values ?? [];

                        /*
                        |--------------------------------------------------------------------------
                        | Make sure values are arrays
                        |--------------------------------------------------------------------------
                        */

                        if (!is_array($oldValues)) {
                            $oldValues = json_decode($oldValues, true) ?? [];
                        }

                        if (!is_array($newValues)) {
                            $newValues = json_decode($newValues, true) ?? [];
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Get all fields involved in this audit
                        |--------------------------------------------------------------------------
                        */

                        $fields = array_unique(
                            array_merge(
                                array_keys($oldValues),
                                array_keys($newValues)
                            )
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Changed Fields
                        |--------------------------------------------------------------------------
                        */

                        $changedFields = collect($fields)
                            ->map(function ($field) {
                                return ucfirst(
                                    str_replace('_', ' ', $field)
                                );
                            })
                            ->implode(', ');

                        /*
                        |--------------------------------------------------------------------------
                        | Old Values
                        |--------------------------------------------------------------------------
                        */

                        $formattedOldValues = empty($oldValues)
                            ? '—'
                            : collect($fields)
                            ->map(function ($field) use ($oldValues) {
                                if (!array_key_exists($field, $oldValues)) {
                                    return '—';
                                }

                                return $this->formatAuditValue(
                                    $field,
                                    $oldValues[$field]
                                );
                            })
                            ->implode(' | ');

                        /*
                        |--------------------------------------------------------------------------
                        | New Values
                        |--------------------------------------------------------------------------
                        */

                        $formattedNewValues = collect($fields)
                            ->map(function ($field) use ($newValues) {

                                if (!array_key_exists($field, $newValues)) {
                                    return '—';
                                }

                                return $this->formatAuditValue(
                                    $field,
                                    $newValues[$field]
                                );
                            })
                            ->implode(' | ');

                        /*
                        |--------------------------------------------------------------------------
                        | Product Name
                        |--------------------------------------------------------------------------
                        */

                        $productName = $audit->auditable?->name;

                        /*
                        |--------------------------------------------------------------------------
                        | If the product was deleted, the relationship may be null.
                        | Try to get its historical name from the audit values.
                        |--------------------------------------------------------------------------
                        */

                        if (!$productName) {
                            $productName =
                                $oldValues['name']
                                ?? $newValues['name']
                                ?? 'N/A';
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Write CSV Row
                        |--------------------------------------------------------------------------
                        */

                        fputcsv($handle, [

                            $audit->id,

                            ucfirst($audit->event),

                            $productName,

                            $audit->user?->name ?? 'System',

                            $audit->user?->email ?? 'N/A',

                            $changedFields ?: '—',

                            $formattedOldValues ?: '—',

                            $formattedNewValues ?: '—',

                            $audit->ip_address ?? 'N/A',

                            $audit->url ?? 'N/A',

                            optional($audit->created_at)
                                ->format('d-m-Y H:i'),

                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Format individual audit values.
     */
    private function formatAuditValue(
        string $field,
        mixed $value
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Empty Value
        |--------------------------------------------------------------------------
        */

        if ($value === null || $value === '') {
            return '—';
        }

        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        if ($field === 'price' && is_numeric($value)) {
            return '₹' . number_format((float) $value);
        }

        /*
        |--------------------------------------------------------------------------
        | Array Value
        |--------------------------------------------------------------------------
        */

        if (is_array($value)) {
            return json_encode($value);
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Text Value
        |--------------------------------------------------------------------------
        */

        return (string) $value;
    }
}
