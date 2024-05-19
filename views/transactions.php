<?php

require_once __DIR__ .'/../app/app.php';
$dataRows = readFiles($filenames);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php for ($i = 1; $i < count($dataRows); $i++): ?>      
                        <tr>
                        <td><?= htmlspecialchars(date('M j, Y', strtotime($dataRows[$i][0])) ?? ''); ?></td>
                        <td><?= htmlspecialchars($dataRows[$i][1] ?? ''); ?></td>
                        <td><?= htmlspecialchars($dataRows[$i][2] ?? ''); ?></td>

                        <?php if (str_replace('$','', $dataRows[$i][3]) > 0): ?>
                            <td style="color: green;"><?= htmlspecialchars($dataRows[$i][3] ?? ''); ?></td> 
                        <?php else: ?>
                            <td style="color: red;"><?= htmlspecialchars($dataRows[$i][3] ?? ''); ?></td> 
                        <?php endif; ?>
                        </tr>
                    <?php endfor; ?>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?= $income ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td ><?= $expenses ?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?= $net ?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>