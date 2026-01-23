<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivitiesExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $query = ActivityLog::with(['user', 'loggable'])
            ->latest();

        // 🔹 FILTER OBJECT TYPE
        if (!empty($this->filters['subject_type'])) {
            $map = [
                'asset'   => \App\Models\Asset::class,
                'package' => \App\Models\AssetPackage::class,
            ];

            if (isset($map[$this->filters['subject_type']])) {
                $query->where(
                    'loggable_type',
                    $map[$this->filters['subject_type']]
                );
            }
        }

        // 🔹 FILTER ACTION
        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }

        // 🔹 FILTER USER
        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        // 🔹 FILTER DATE RANGE
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->get()->map(function ($log) {
            return [
                'Tanggal'     => $log->created_at,
                'Object'      => class_basename($log->loggable_type),
                'Object ID'   => $log->loggable_id,
                'Action'      => strtoupper($log->action),
                'User'        => $log->user->name ?? 'System',
                'IP Address'  => $log->ip_address,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Object',
            'Object ID',
            'Action',
            'User',
            'IP Address',
        ];
    }
}
