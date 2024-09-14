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
$sheet->setCellValue('A3', 'Member Name: ' . $member_name . ', Phone Number: ' . $member_phone);
$sheet->setCellValue('A4', "From $sdate To $edate");
$sheet->mergeCells('A2:F2');
$sheet->mergeCells('A3:F3');
$sheet->mergeCells('A4:F4');
$sheet->getStyle('A1:A4')->applyFromArray($ReportHeader);

$header = ['S/N', 'Land Name', 'Plot No', 'Cost', 'Dimension', 'Payment(N)'];

$highest_row = $sheet->getHighestRow();

$sheet->setCellValue('A5', 'Transaction Details');
$sheet->mergeCells('A5:B5');

$sheet->fromArray($header, null, 'A6');
$sheet->getStyle('A6:F6')->applyFromArray($TableHeader);

$transactions = DB::select(
    "SELECT
 lands.land_name, plots.plot_no, plots.cost, plots.dimension
      , transactions.amount   FROM land_distributions JOIN plots
       ON land_distributions.plot_id = plots.id
       INNER JOIN lands ON lands.id = plots.land_id
       INNER JOIN members on members.id = land_distributions.member_id
		 LEFT JOIN transactions ON transactions.land_distribution_id = land_distributions.id
		 WHERE members.id = ? ORDER BY lands.land_name asc
			",
    [$id],
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
$sheet->getStyle("A7:F$highest_row")->applyFromArray($TableBody);
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
    ->getStyle("E7:F$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet
    ->getStyle("F7:F$highest_row")
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
$sheet->setCellValue('E' . $highest_row + 1, 'Total Transaction: ');
$sheet->setCellValue('F' . $highest_row + 1, 'N ' . number_format($total_transactions, 2));
$sheet
    ->getStyle('F' . $highest_row + 1)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// for ($i = 1; $i < 5; $i++) {
//     $sheet->setCellValue('A' . $row, $i);
//     $sheet->setCellValue('B' . $row, $results[]);
//     $row++;
// }

//summary
$highest_row = $sheet->getHighestRow();
$highest_row += 4;
$transaction_summary = DB::select("SELECT members.name, land_distributions.phone,
 lands.land_name,
       sum(transactions.amount) AS total   FROM land_distributions JOIN plots
       ON land_distributions.plot_id = plots.id
       INNER JOIN lands ON lands.id = plots.land_id
       INNER JOIN members on members.id = land_distributions.member_id
		 LEFT JOIN transactions ON transactions.land_distribution_id = land_distributions.id
		 WHERE members.id = $id GROUP BY lands.land_name, members.name, land_distributions.phone ORDER BY lands.land_name asc");

$header = ['S/N', 'Member Name', 'Phone', 'Land Name', 'Total Payment(N)'];

$sheet->setCellValue('A' . $highest_row, 'Summary');
$sheet->mergeCells("A$highest_row:B$highest_row");
$highest_row += 1;

$sheet->fromArray($header, null, 'A' . $highest_row);
$sheet->getStyle('A' . $highest_row . ':E' . $highest_row)->applyFromArray($TableHeader);

$highest_row += 1;
$result_summary = [];
$i = 0;
$total_transaction = 0;
foreach ($transaction_summary as $transaction) {
    $i++;
    $result_summary[] = array_merge([$i], array_values(get_object_vars($transaction)));
    $total_transaction += $transaction->total;
}
// This is the loop to populate data
$sheet->fromArray($result_summary, null, 'A' . $highest_row);
$first_row = $highest_row;
$highest_row = $sheet->getHighestRow();
$sheet->getStyle('A' . $first_row . ':E' . $highest_row)->applyFromArray($TableBody);
$sheet
    ->getStyle("D$first_row:E$highest_row")
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

//formating
$highest_column = $sheet->getHighestColumn();
$highestColumnAutoSize = $highest_column;
$highestColumnAutoSize++;

$sheet->setCellValue('D' . ($highest_row + 1), 'Total');
$sheet->setCellValue('E' . ($highest_row + 1), number_format($total_transaction, 2));
$sheet
    ->getStyle('E' . $highest_row + 1)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// for ($col = 'A'; $col !== $highestColumnAutoSize; $col++) {
//     $sheet->getColumnDimension($col)->setAutoSize(true);
// }

$writer = new Xlsx($spreadsheet);
$fileName = $member_phone . '-' . $sdate . ' to ' . $edate . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
