<?php

declare(strict_types=1);

namespace App\Models;

class Helpers
{
    public static function getTransactionFiles(string $dirPath): array
    {
        $files = [];

        foreach (scandir($dirPath) as $file) {
            if (is_dir($file)) {
                continue;
            }

            $files[] = $dirPath . DIRECTORY_SEPARATOR . $file;
        }

        return $files;
    }

    public static function getTransactions(string $fileName): array
    {
        if (! file_exists($fileName)) {
            trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
        }

        $file = fopen($fileName, 'r');

        fgetcsv($file); // get rid of the first row

        $transactions = [];

        while (($transactionRow = fgetcsv($file)) !== false) {
            $transactionRow = Helpers::extractTransaction($transactionRow);
            $transactions[] = $transactionRow;
        }

        return $transactions;
    }

    public static function extractTransaction(array $transactionRow): array
    {
        [$date, $checkNumber, $description, $amount] = $transactionRow;

        $amount = (float) str_replace(['$', ','], '', $amount);

        return [
            'date'        => $date,
            'checkNumber' => $checkNumber,
            'description' => $description,
            'amount'      => $amount,
        ];
    }

    public static function calculateTotals(array $transactions): array
    {
        $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

        foreach ($transactions as $transaction) {
            $totals['netTotal'] += $transaction['amount'];

            if ($transaction['amount'] >= 0) {
                $totals['totalIncome'] += $transaction['amount'];
            } else {
                $totals['totalExpense'] += $transaction['amount'];
            }
        }

        return $totals;
    }

    public static function formatDollarAmount(float $amount): string
    {
        $isNegative = $amount < 0;

        return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
    }

    public static function formatDate(string $date): string
    {
        return date('M j, Y', strtotime($date));
    }

}