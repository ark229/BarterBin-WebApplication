<?php
error_reporting(E_ERROR | E_PARSE);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('session.php');

$result = $conn->query("SELECT rating, name, review FROM reviews");

$reviews = [];
$total_rating = 0;
$total_reviews = 0;
$star_count = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
    $total_rating += $row['rating'];
    $total_reviews++;
    $star_count[$row['rating']]++;
}

$average_rating = $total_reviews > 0 ? round($total_rating / $total_reviews, 1) : 0;
$percentage_ratings = array_map(function ($count) use ($total_reviews) {
    return $total_reviews > 0 ? round(($count / $total_reviews) * 100, 1) : 0;
}, $star_count);

header('Content-Type: text/plain');
echo "JSON data:\n";
echo json_encode([
    'reviews' => $reviews,
    'average_rating' => $average_rating,
    'total_reviews' => $total_reviews,
    'star_count' => $star_count,
    'percentage_ratings' => $percentage_ratings
]);
echo "\nUnexpected content:\n";
ob_start();
var_dump(ob_get_clean());

$conn->close();
