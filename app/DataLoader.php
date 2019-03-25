<?php

namespace App;

use Illuminate\Support\Facades\Storage;

/**
 * Local disk files management
 *
 * Class DataLoader
 * @package App
 */
class DataLoader
{
    /**
     * Get an array from a CSV file
     *
     * @param string $filename File to read
     * @return array Associative array representing the CSV values
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getAssociativeArrayFromCSV($filename)
    {
        $data = [];
        $fileContent = self::getFileContent($filename);
        if ($fileContent) {
            $rows = preg_split('/\r\n|\r|\n/', $fileContent);
            $csv_rows = array_map('str_getcsv', $rows);
            $attributes = array_shift($csv_rows);
            foreach ($csv_rows as $row) {
                $data[] = array_combine($attributes, $row);
            }
        }
        return $data;
    }

    /**
     * Get the contents of a file (stored in local disk)
     *
     * @param string $filename File to read
     * @return string File contents
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private static function getFileContent($filename)
    {
        if (Storage::disk('local')->exists($filename)) {
            return Storage::disk('local')->get($filename);
        }
    }
}