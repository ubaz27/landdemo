<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\AgentTransaction;

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
$sheet->setCellValue('A3', 'Member Name: ' . $agent_name . ', Phone Number: ' . $agent_phone);
$sheet->setCellValue('A4', "From $sdate To $edate");
$sheet->mergeCells('A2:I2');
$sheet->mergeCells('A3:I3');
$sheet->mergeCells('A4:I4');
$sheet->getStyle('A1:A4')->applyFromArray($ReportHeader);

$header = ['S/N', 'Name', 'Phone', 'Land Name', 'Plot No', 'Cost', 'Dimension', 'Payment(N)', 'Payment Date'];

$highest_row = $sheet->getHighestRow();

$sheet->setCellValue('A5', 'Transaction Details');
$sheet->mergeCells('A5:B5');

$sheet->fromArray($header, null, 'A6');
$sheet->getStyle('A6:I6')->applyFromArray($TableHeader);

$transactions = DB::select(
    "select
  `agents`.`name`,
  `land_distributions`.`phone`,
  `lands`.`land_name`,
  `plots`.`plot_no`,
  `plots`.`cost`,
  `plots`.`dimension`,
  `agent_transactions`.`amount_paid`,
  `agent_transactions`.`payment_date`
from
  `agent_transactions`
  inner join `land_distributions` on `land_distributions`.`id` = `agent_transactions`.`land_distribution_id`
  inner join `agents` on `agents`.`id` = `agent_transactions`.`agent_id`
  inner join `plots` on `plots`.`id` = `land_distributions`.`plot_id`
  inner join `lands` on `lands`.`id` = `plots`.`land_id`
where
  agent_transactions.`agent_id` = ? and agent_transactions.payment_date >= ? and  agent_transactions.payment_date <= ?
order by
  `agent_transactions`.`id` desc",
    [$id, $sdate, $edate],
);

// $transactions = AgentTransaction::join('land_distributions', 'land_distributions.id', 'agent_transactions.land_distribution_id')
//     ->join('agents', 'agents.id', 'agent_transactions.agent_id')
//     ->join('plots', 'plots.id', 'land_distributions.plot_id')
//     ->join('lands', 'lands.id', 'plots.land_id')
//     ->orderBy('agent_transactions.id', 'desc')
//     ->where('agent_transactions.agent_id', $id)
//     ->get(['agents.name', 'land_distributions.phone', 'lands.land_name', 'plots.plot_no', 'plots.cost', 'plots.dimension', 'agent_transactions.amount_paid', 'agent_transactions.payment_date']);

// dd($transactions);
$results = [];
$i = 0;
$total_transactions = 0;
foreach ($transactions as $transaction) {
    $i++;
    $results[] = array_merge([$i], array_values(get_object_vars($transaction)));
    $total_transactions += $transaction->amount_paid;
}
// This is the loop to populate data
$sheet->fromArray($results, null, 'A7');

$highest_row = $sheet->getHighestRow();
$highest_column = $sheet->getHighestColumn();
$sheet->getStyle("A7:I$highest_row")->applyFromArray($TableBody);
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
    ->getStyle("H7:H$highest_row")
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
$sheet->setCellValue('G' . $highest_row + 1, 'Total Transaction: ');
$sheet->setCellValue('H' . $highest_row + 1, 'N ' . number_format($total_transactions, 2));
$sheet
    ->getStyle('F' . $highest_row + 1)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// for ($i = 1; $i < 5; $i++) {
//     $sheet->setCellValue('A' . $row, $i);
//     $sheet->setCellValue('B' . $row, $results[]);
//     $row++;
// }

// for ($col = 'A'; $col !== $highestColumnAutoSize; $col++) {
//     $sheet->getColumnDimension($col)->setAutoSize(true);
// }

$writer = new Xlsx($spreadsheet);
$fileName = $agent_phone . '-' . $sdate . ' to ' . $edate . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
