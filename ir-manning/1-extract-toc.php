<?php
$html = file_get_contents('./html_src/irbook.html');

$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$csv = fopen("irbook-toc.csv", "w");

$liElements = $xpath->query("//li");
$previousParent = "-";

foreach ($liElements as $li) {
    $aElement = $xpath->query("./a", $li)->item(0);

    if ($aElement) {
        $fileName = $aElement->getAttribute("href");
        $text = $aElement->nodeValue;

        if ($xpath->query("./ul", $li)->length > 0) {
            $previousParent = $fileName;
            fputcsv($csv, array("-", $text, $fileName));
        } else {
            fputcsv($csv, array($previousParent, $text, $fileName));
        }
    }
}

fclose($csv);

echo "CSV file generated: irbook-toc.csv\n";
