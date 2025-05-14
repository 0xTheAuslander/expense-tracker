<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Models\Helpers;


class Process extends Model
{
    
    public array $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    public function __construct() {
        parent::__construct();
        $this->totals = Helpers::calculateTotals($this->processFile());
    }
    public function processFile(): array
    {
        $files = Helpers::getTransactionFiles(STORAGE_PATH);

        $transactions = [];
        foreach($files as $file) {
            $transactions = array_merge($transactions, Helpers::getTransactions($file));
        }

        return $transactions;
    }

    public function create(array $transactions)
    {
        foreach($transactions as $transaction) {
            $stmt = $this->db->prepare('INSERT INTO transactions (transaction_date, transaction_check, transaction_description, amount) VALUES (?, ?, ?, ?)');

            $stmt->execute([date('Y-m-d', strtotime($transaction['date'])), $transaction['checkNumber'], $transaction['description'], $transaction['amount']]);
        }
    }

    public function all(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM transactions');
        $stmt->execute();

        return $stmt->fetchAll();
    }

}