<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportService
{
    public function exportReclamationsToExcel(array $reclamations): void
    {
        // Créez un objet Spreadsheet
        $spreadsheet = new Spreadsheet();
        
        // Ajoutez des données à la feuille de calcul
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Type');
        $sheet->setCellValue('C1', 'Contenu');
        $sheet->setCellValue('D1', 'Date');

        foreach ($reclamations as $index => $reclamation) {
            $row = $index + 2; // Commencez à la ligne 2
            $sheet->setCellValue('A' . $row, $reclamation->getId());
            $sheet->setCellValue('B' . $row, $reclamation->getType());
            $sheet->setCellValue('C' . $row, $reclamation->getContenu());
            $sheet->setCellValue('D' . $row, $reclamation->getDate());

        }

        // Créez un objet Writer pour exporter en fichier Excel
        $writer = new Xlsx($spreadsheet);

        // Définissez le chemin du fichier Excel
        $filePath = 'export_reclamations.xlsx';

        // Enregistrez le fichier Excel sur le serveur
        $writer->save($filePath);
    }
}