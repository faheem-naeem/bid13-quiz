<?php

function plotScatterGD($filename) {
    $width = 400;
    $height = 400;
    $image = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $blue = imagecolorallocate($image, 0, 0, 255);

    imagefill($image, 0, 0, $white);

    $x_min = PHP_INT_MAX;
    $x_max = PHP_INT_MIN;
    $y_min = PHP_INT_MAX;
    $y_max = PHP_INT_MIN;
    $points = [];

    // Read CSV and find min/max values
    if (($handle = fopen($filename, "r")) !== FALSE) {
        fgetcsv($handle); // Skip header
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $x = (int) $row[0];
            $y = (int) $row[1];

            $x_min = min($x_min, $x);
            $x_max = max($x_max, $x);
            $y_min = min($y_min, $y);
            $y_max = max($y_max, $y);

            $points[] = [$x, $y];
        }
        fclose($handle);
    }

    // Normalize values and plot points
    foreach ($points as [$x, $y]) {
        $norm_x = (int) (($x - $x_min) / ($x_max - $x_min) * $width);
        $norm_y = (int) (($y - $y_min) / ($y_max - $y_min) * $height);

        $norm_y = $height - $norm_y; // Invert Y for correct plotting

        imagesetpixel($image, $norm_x, $norm_y, $blue);
    }

    // Save image
    imagepng($image, "scatter.png");
    imagedestroy($image);

    echo "Scatter plot saved as scatter.png\n";
}

// Call function
plotScatterGD("PHP Quiz Question #2 - out.csv");

?>
