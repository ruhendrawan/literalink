<?php

// Read the CSV file
$csvFile = 'irbook-toc.csv';
$csvData = array_map('str_getcsv', file($csvFile));

$inputDir = './html_src';

// Create the output directory if it doesn't exist
$outputDir = './out_part';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Define the strings to search for
$string1 = "<!--End of Navigation Panel-->";
$string2 = "<HR>
<!--Navigation Panel-->";

$string2b = "<HR>
<ADDRESS>";

// Loop through the CSV data and process each HTML file
foreach ($csvData as $row) {
    if (!isset($row[2])) continue;
    if ($row[2] == '') continue;

    $htmlFile = $row[2];
    $inputFile = $inputDir . '/' . $htmlFile;
    $outputFile = $outputDir . '/' . $htmlFile;
    $is_found = false;

    // Check if the HTML file exists
    if (file_exists($inputFile)) {
        $content = file_get_contents($inputFile);

        $startPos = strpos($content, $string1);
        $endPos = strpos($content, $string2, $startPos);

        if ($startPos !== false && $endPos !== false) {
            $startPos += strlen($string1);
            $length = $endPos - $startPos;
            $extractedContent = substr($content, $startPos, $length);

            file_put_contents($outputFile, $extractedContent);

            echo "Extracted content (type 1) from $htmlFile and saved to $outputFile\n";
            $is_found = true;
        }


        if (!$is_found) {
            $startPos = strpos($content, $string1);
            $endPos = strpos($content, $string2b, $startPos);
    
            if ($startPos !== false && $endPos !== false) {
                $startPos += strlen($string1);
                $length = $endPos - $startPos;
                $extractedContent = substr($content, $startPos, $length);

                file_put_contents($outputFile, $extractedContent);

                echo "Extracted content (type 2) from $htmlFile and saved to $outputFile\n";
                $is_found = true;
            }
        }

        if (!$is_found) {
            echo "One or both strings not found in $htmlFile.\n";
        }

    } else {
        echo "File not found: $htmlFile\n";
    }
}

?>
