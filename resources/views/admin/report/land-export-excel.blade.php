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
$sheet->setCellValue('A3', 'Land Name: ' . $land_name . ', Cost :N ' . number_format($cost, 2) . ' at ' . $lga . ' LGA');
$sheet->setCellValue('A4', "From $sdate To $edate");
$sheet->mergeCells('A2:F2');
$sheet->mergeCells('A3:F3');
$sheet->mergeCells('A4:F4');
$sheet->getStyle('A1:A4')->applyFromArray($ReportHeader);

$header = ['S/N', 'Phone', 'Name', 'Plot No', 'Dimension', 'Cost', 'Payment(N)'];
$sheet->fromArray($header, null, 'A6');
$sheet->getStyle('A6:G6')->applyFromArray($TableHeader);

$transactions = DB::select(
    "SELECT
 land_distributions.phone,members.name, plots.plot_no
,plots.dimension,plots.cost AS plot_cost, transactions.amount FROM
land_distributions
INNER JOIN transactions ON land_distributions.id = transactions.land_distribution_id
INNER JOIN plots ON land_distributions.plot_id = plots.id
INNER JOIN lands ON lands.id = plots.land_id
INNER JOIN members ON members.id = land_distributions.member_id
			  WHERE lands.id = ? AND transactions.created_at >= ? AND  transactions.created_at <= ?
			  ORDER BY land_distributions.phone asc",
    [$id, $sdate, $edate],
);

$results = [];
$i = 0;
$total_transactions = 0;
foreach ($transactions as $transaction) {
    $i++;
    $results[] = array_merge([$i], array_values(get_object_vars($transaction)));
    $total_transactions += $transaction->amount;
}
// This is the loop to populate data
$sheet->fromArray($results, null, 'A7');

$highest_row = $sheet->getHighestRow();
$highest_column = $sheet->getHighestColumn();
$sheet->getStyle("A7:G$highest_row")->applyFromArray($TableBody);
$sheet
    ->getStyle("B7:B$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet
    ->getStyle("E7:E$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet
    ->getStyle("F7:F$highest_row")
    ->getNumberFormat()
    ->setFormatCode('0.00');
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
$sheet->getColumnDimension('C')->setWidth(25);

$highest_row = $sheet->getHighestRow();
$sheet->setCellValue('B' . $highest_row + 3, 'Total Transaction: ');
$sheet->setCellValue('C' . $highest_row + 3, 'N ' . number_format($total_transactions, 2));
// for ($i = 1; $i < 5; $i++) {
//     $sheet->setCellValue('A' . $row, $i);
//     $sheet->setCellValue('B' . $row, $results[]);
//     $row++;
// }

$writer = new Xlsx($spreadsheet);
$fileName = $land_name . '-' . $sdate . ' to ' . $edate . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
