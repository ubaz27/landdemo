<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use Illuminate\Support\Facades\DB;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$ReportHeader = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$TableHeader = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];

$TableBody = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$sheet->setTitle('Sheet 1'); // This is where you set the title
// $sheet->setCellValue('A1', 'No'); // This is where you set the column header
// $sheet->setCellValue('B1', 'Name'); // This is where you set the column header
$row = 2; // Initialize row counter

$sheet->setCellValue('A2', 'Company Name');
$sheet->setCellValue('A3', 'Land Name: ' . $land_name);
$sheet->setCellValue('A4', 'Dimension: ' . $land_dimension . ', Cost: ' . number_format($land_cost, 2));
$sheet->mergeCells('A2:F2');
$sheet->mergeCells('A3:F3');
$sheet->mergeCells('A4:F4');
$sheet->getStyle('A1:A4')->applyFromArray($ReportHeader);

$header = ['S/N', 'Land Name', 'Plot No', 'Cost', 'Dimension', 'Occupier', 'Total Generated', 'Agent Code'];
$sheet->fromArray($header, null, 'A6');
$sheet->getStyle('A6:H6')->applyFromArray($TableHeader);

$transactions = DB::select(
    "SELECT lands.land_name,plots.plot_no, plots.cost,
plots.dimension, (case when land_distributions.phone <> '' then land_distributions.phone ELSE '' END ) AS phone, SUM( case when (transactions.amount) > 0 then (transactions.amount) ELSE '' end) AS total_paid ,
agents.phone AS agent_code FROM plots
left JOIN land_distributions ON land_distributions.plot_id = plots.id
left JOIN transactions ON land_distributions.id = transactions.land_distribution_id
left JOIN lands ON plots.land_id = lands.id
left JOIN agents ON agents.id = land_distributions.agent_id
WHERE plots.land_id = ?
GROUP BY lands.land_name, plots.cost,plots.dimension , land_distributions.phone,
agents.phone, plots.plot_no",
    [$id],
);

$results = [];
$i = 0;
$total_transactions = 0;
foreach ($transactions as $transaction) {
    $i++;
    $results[] = array_merge([$i], array_values(get_object_vars($transaction)));
    $total_transactions += $transaction->total_paid;
}
// This is the loop to populate data
$sheet->fromArray($results, null, 'A7');

$highest_row = $sheet->getHighestRow();
$highest_column = $sheet->getHighestColumn();
$sheet->getStyle("A7:H$highest_row")->applyFromArray($TableBody);
$sheet
    ->getStyle("B7:B$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet
    ->getStyle("C7:C$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet
    ->getStyle("D7:D$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet
    ->getStyle("G7:G$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet
    ->getStyle("G7:G$highest_row")
    ->getNumberFormat()
    ->setFormatCode('0.00');

$highestColumnAutoSize = $highest_column;
$highestColumnAutoSize++;
for ($col = 'A'; $col !== $highestColumnAutoSize; $col++) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getColumnDimension('C')->setAutoSize(false);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(5);

$highest_row = $sheet->getHighestRow();
$sheet->setCellValue('F' . $highest_row + 1, 'Total Transaction: ');
$sheet->setCellValue('G' . $highest_row + 1, 'N ' . number_format($total_transactions, 2));
$sheet
    ->getStyle('G' . $highest_row + 1)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// for ($i = 1; $i < 5; $i++) {
//     $sheet->setCellValue('A' . $row, $i);
//     $sheet->setCellValue('B' . $row, $results[]);
//     $row++;
// }

$writer = new Xlsx($spreadsheet);
$fileName = $land_name . '_plots.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
