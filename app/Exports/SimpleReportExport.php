<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SimpleReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $records;
    protected $columnNames;
    protected $columnLabels;

    public function __construct($records, $columnNames, $columnLabels)
    {
        $this->records = $records;
        $this->columnNames = $columnNames;
        $this->columnLabels = $columnLabels;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return $this->columnLabels;
    }

    public function map($record): array
    {
        $data = [];
        foreach ($this->columnNames as $columnName) {
            try {
                // Check if this is a relationship column
                if (str_contains($columnName, '.')) {
                    // This is a relationship column like 'user.name'
                    $value = data_get($record, $columnName);
                } else {
                    // This is a regular column
                    $value = data_get($record, $columnName);
                }
                
                // Convert to string safely
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                } elseif (is_null($value)) {
                    $value = '';
                } else {
                    $value = (string) $value;
                }
                
                $data[] = $value;
            } catch (\Exception $e) {
                $data[] = '';
            }
        }
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
