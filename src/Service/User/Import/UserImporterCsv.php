<?php

namespace App\Service\User\Import;

class UserImporterCsv
{
    /**
     * @param string $filepath
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getData(string $filepath): array
    {
        if (($handle = fopen($filepath, "r")) !== false) {
            $userData = [];
            while (($data = fgetcsv($handle, 255)) !== false) {
                $userData[] = $data;
            }
            fclose($handle);

            return $userData;
        }

        throw new \Exception(sprintf('Could not open file: %s', $filepath));
    }
}