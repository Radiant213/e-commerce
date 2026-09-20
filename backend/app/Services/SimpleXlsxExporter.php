<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class SimpleXlsxExporter
{
    /**
     * Generate and stream a true native Microsoft Excel (.xlsx) file.
     *
     * @param string $filename Name of the output file (e.g. Laporan_Penjualan.xlsx)
     * @param array $headers List of column headers
     * @param array $rows List of row data (array of arrays)
     * @param string $sheetTitle Title of the sheet tab
     * @return StreamedResponse
     */
    public static function download(string $filename, array $headers, array $rows, string $sheetTitle = 'Laporan'): StreamedResponse
    {
        if (!str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        return response()->streamDownload(function () use ($headers, $rows, $sheetTitle) {
            $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
            $zip = new ZipArchive();
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            // 1. [Content_Types].xml
            $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                . '<Default Extension="xml" ContentType="application/xml"/>'
                . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
                . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
                . '</Types>';
            $zip->addFromString('[Content_Types].xml', $contentTypes);

            // 2. _rels/.rels
            $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
                . '</Relationships>';
            $zip->addFromString('_rels/.rels', $rels);

            // 3. xl/_rels/workbook.xml.rels
            $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
                . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
                . '</Relationships>';
            $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

            // 4. xl/workbook.xml
            $safeSheetTitle = htmlspecialchars($sheetTitle, ENT_XML1, 'UTF-8');
            $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                . '<sheets>'
                . '<sheet name="' . $safeSheetTitle . '" sheetId="1" r:id="rId1"/>'
                . '</sheets>'
                . '</workbook>';
            $zip->addFromString('xl/workbook.xml', $workbook);

            // 5. xl/styles.xml (Header: Emerald green background #059669 with bold white text; Data: clean bordered rows)
            $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
                . '<fonts count="2">'
                . '<font><name val="Calibri"/><sz val="11"/><color rgb="FF1F2937"/></font>'
                . '<font><b/><name val="Calibri"/><sz val="11"/><color rgb="FFFFFFFF"/></font>'
                . '</fonts>'
                . '<fills count="3">'
                . '<fill><patternFill patternType="none"/></fill>'
                . '<fill><patternFill patternType="gray125"/></fill>'
                . '<fill><patternFill patternType="solid"><fgColor rgb="FF059669"/></patternFill></fill>'
                . '</fills>'
                . '<borders count="2">'
                . '<border><left/><right/><top/><bottom/></border>'
                . '<border>'
                . '<left style="thin"><color rgb="FFE2E8F0"/></left>'
                . '<right style="thin"><color rgb="FFE2E8F0"/></right>'
                . '<top style="thin"><color rgb="FFE2E8F0"/></top>'
                . '<bottom style="thin"><color rgb="FFE2E8F0"/></bottom>'
                . '</border>'
                . '</borders>'
                . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
                . '<cellXfs count="3">'
                . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'
                . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
                . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'
                . '</cellXfs>'
                . '</styleSheet>';
            $zip->addFromString('xl/styles.xml', $styles);

            // 6. xl/worksheets/sheet1.xml
            $colCount = count($headers);
            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
                . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
                . '<cols>';
            for ($c = 1; $c <= $colCount; $c++) {
                $sheetXml .= '<col min="' . $c . '" max="' . $c . '" width="20" customWidth="1"/>';
            }
            $sheetXml .= '</cols><sheetData>';

            // Header Row
            $sheetXml .= '<row r="1" ht="26" customHeight="1">';
            foreach ($headers as $cIndex => $headerText) {
                $cellRef = self::getColLetter($cIndex) . '1';
                $safeText = htmlspecialchars((string) $headerText, ENT_XML1, 'UTF-8');
                $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="1"><is><t>' . $safeText . '</t></is></c>';
            }
            $sheetXml .= '</row>';

            // Data Rows
            $rIndex = 2;
            foreach ($rows as $row) {
                $sheetXml .= '<row r="' . $rIndex . '" ht="20" customHeight="1">';
                $cIndex = 0;
                foreach ($row as $val) {
                    $cellRef = self::getColLetter($cIndex) . $rIndex;
                    if (is_numeric($val) && !str_starts_with((string) $val, '0') && strlen((string) $val) < 15) {
                        $sheetXml .= '<c r="' . $cellRef . '" s="2"><v>' . $val . '</v></c>';
                    } else {
                        $safeVal = htmlspecialchars((string) $val, ENT_XML1, 'UTF-8');
                        $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="2"><is><t>' . $safeVal . '</t></is></c>';
                    }
                    $cIndex++;
                }
                $sheetXml .= '</row>';
                $rIndex++;
            }

            $sheetXml .= '</sheetData></worksheet>';
            $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

            $zip->close();

            readfile($tempFile);
            @unlink($tempFile);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private static function getColLetter(int $colIndex): string
    {
        $letter = '';
        while ($colIndex >= 0) {
            $letter = chr($colIndex % 26 + 65) . $letter;
            $colIndex = intdiv($colIndex, 26) - 1;
        }
        return $letter;
    }
}
