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
        $query = ActivityLog::with(['user'])
            ->orderBy('created_at');

        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->get()->map(fn ($log) => [
            'DATE'        => $log->created_at->format('Y-m-d H:i:s'),
            'OBJECT TYPE' => class_basename($log->loggable_type),
            'OBJECT ID'   => $log->loggable_id,
            'CODE'        => $log->loggable_code ?? '-',
            'ACTION'      => $log->action,
            'USER'        => $log->user->name ?? 'SYSTEM',
            'IP ADDRESS'  => $log->ip_address,
            'BEFORE'      => json_encode($log->before_data),
            'AFTER'       => json_encode($log->after_data),
        ]);
    }

    public function headings(): array
    {
        return [
            'DATE',
            'OBJECT TYPE',
            'OBJECT ID',
            'CODE',
            'ACTION',
            'USER',
            'IP ADDRESS',
            'BEFORE',
            'AFTER',
        ];
    }
}
