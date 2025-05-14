<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Process;

class HomeController
{
    public function index(): View
    {
        return View::make('index');
    }

    public function showTransactions(): View
    {
        $processedFile = new process();

        $totals = $processedFile->totals;
        $transactions = $processedFile->processFile();
        
        $processedFile->create($transactions);

        return View::make('transactions', ['transactions' => $transactions, 'totals' => $totals]);
    }

    public function uploadTransactionsFile()
    {
        $filepath = STORAGE_PATH . DIRECTORY_SEPARATOR . $_FILES['csv_file']['name'];

        move_uploaded_file($_FILES['csv_file']['tmp_name'], $filepath);

        header('Location: /');

        exit;
    }
}
