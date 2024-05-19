<?php

declare(strict_types = 1);

$filenames = getCsvFiles(FILES_PATH);
$dataRows = readFiles($filenames);
$dataColumns = processFile($filenames);
[
    'totalIncome' => $income, 
    'totalExpense' => $expenses, 
    'netTotal' => $net,
] = CalculateExpenses($dataColumns);

// implementation of needed functions

// scan the transaction_files directory to get the data from csv files
function getCsvFiles(string $dir): array {

    $files = scandir($dir); 
    $filenames = []; 

    // Check if $files is an array to ensure scandir was successful
    if ($files === false || glob($dir .'*.csv') === false) {
        echo "Failed to open directory.";
    } else {
        foreach ($files as $file) {
            //filtering out . and .., and directories:
            if ($file !== '.' && $file !== '..' && !is_dir($file)) {
                $filenames[] = $dir . '/' . $file ;
            }
        }
    }
    return $filenames;
}


//Read file rows
function readFiles(array $filenames): array {

    $data = []; // Initialize an empty array to hold the data
    foreach ($filenames as $filename) {
        // Open the file for reading
        $f = fopen($filename, 'r');
    
        if ($f === false) {
            die('Cannot open the file ' . $filename); // Error handling if the file cannot be opened
        }
    
        // Read each line in the CSV file
        while (($row = fgetcsv($f)) !== false) {
            $data[] = $row; // Append each row to the $data array
        }
    
        // Close the file
        fclose($f);
    }
    
    // format the dates
    for ($i = 1; $i < count($data); $i++) { 
        $data[$i][0] = date('M j, Y', strtotime($data[$i][0]));
    }
    return $data;
}
// Read the CSV file and store the data into an array
function processFile(array $filenames) {
    
    $data = [
        'dateOfTransaction'=> [],
        'check#'=> [],
        'description'=> [],
        'amount'=> [],
    
    ]; 

    foreach ($filenames as $filename) {
        $f = fopen($filename, 'r');
    
        if ($f === false) {
            die('Cannot open the file ' . $filename); // Error handling if the file cannot be opened
        }

        while (($row = fgetcsv($f)) !== false) {
            
            $data['dateOfTransaction'][] = $row[0];
            $data['check#'][] = $row[1];
            $data['description'][] = $row[2];
            $data['amount'][] = $row[3];

        }

        // Close the file
        fclose($f);
    }
    return $data;
}

// Calculate total income, total expenses, and Net total
function CalculateExpenses(array $dataColumns): array { 
    $result = [
        'totalIncome' => 0,
        'totalExpense' => 0,
        'netTotal' => 0,
    ];

    
    foreach ($dataColumns['amount'] as $amountItem) {
        // Clean the amount values by removing the $ and ,
        $amountItem = (float) str_replace(['$', ','], '', $amountItem);
        // echo ''. $amountItem .'<br>';
        $result['totalIncome'] += $amountItem > 0 ? $amountItem: 0;
        $result['totalExpense'] += $amountItem < 0 ? $amountItem : 0;

        $result['netTotal'] = $result['totalIncome'] + $result['totalExpense'];
    }

    return $result;
}




