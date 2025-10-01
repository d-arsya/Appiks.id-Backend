<?php

// app/Exports/StudentMoodExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentMoodExport implements WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $stud = $this->data['stud'];
                $recap = $this->data['recap'];
                $mean = $this->data['mean'];
                $moods = $this->data['moods'];

                // === Title ===
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Mood Report');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(25);

                // === Student Info Section ===
                $row = 3;
                foreach (
                    [
                        'Name' => $stud['name'],
                        'Room' => $stud['room'],
                        'Identifier' => $stud['identifier'],
                        'Counselor' => $stud['counselor'],
                        'Mentor' => $stud['mentor'],
                    ] as $label => $value
                ) {
                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->setCellValue("B{$row}", $value);
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $row++;
                }

                // === Recap Section ===
                $row += 1;
                $sheet->setCellValue("A{$row}", 'Recap');
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $row++;

                foreach ($recap as $status => $count) {
                    $sheet->setCellValue("A{$row}", ucfirst($status));
                    $sheet->setCellValue("B{$row}", $count);
                    $row++;
                }
                $sheet->setCellValue("A{$row}", 'Mean');
                $sheet->setCellValue("B{$row}", $mean);

                // Style recap table
                $sheet->getStyle('A'.($row - count($recap) - 1).":B{$row}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $sheet->getStyle('A'.($row - count($recap) - 1).':B'.($row - count($recap) - 1))
                    ->getFont()->setBold(true);

                $startRow = $row + 2;
                $sheet->setCellValue("A{$startRow}", 'Recorded');
                $sheet->setCellValue("B{$startRow}", 'Status');

                $sheet->getStyle("A{$startRow}:B{$startRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '808080'],
                    ],
                ]);

                $cur = $startRow + 1;
                foreach ($moods as $m) {
                    $sheet->setCellValue("A{$cur}", $m['recorded']);
                    $sheet->setCellValue("B{$cur}", ucfirst($m['status']));
                    $cur++;
                }

                $sheet->getStyle("A{$startRow}:B".($cur - 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
