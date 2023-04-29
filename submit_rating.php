<?php
include 'config.php';
require_once('session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'load_data') {
        // Load and process data from the database
        $query = "SELECT user_rating, user_name, user_review, datetime FROM review_table";
        $result = $conn->query($query);
        $review_data = $result->fetch_all(MYSQLI_ASSOC);

        $total_review = count($review_data);
        $rating_sum = 0;
        $rating_counts = [0, 0, 0, 0, 0];

        foreach ($review_data as $review) {
            $rating_sum += $review['user_rating'];
            $rating_counts[$review['user_rating'] - 1]++;
        }

        $average_rating = $rating_sum / $total_review;

        $data = [
            'average_rating' => $average_rating,
            'total_review' => $total_review,
            'five_star_review' => $rating_counts[4],
            'four_star_review' => $rating_counts[3],
            'three_star_review' => $rating_counts[2],
            'two_star_review' => $rating_counts[1],
            'one_star_review' => $rating_counts[0],
            'review_data' => $review_data
        ];

        echo json_encode($data);
    } elseif (isset($_POST['rating_data']) && isset($_POST['user_name']) && isset($_POST['user_review'])) {
        // Save review to the database
        $user_name = $conn->real_escape_string($_POST['user_name']);
        $rating_data = $conn->real_escape_string($_POST['rating_data']);
        $user_review = $conn->real_escape_string($_POST['user_review']);
        $datetime = date('Y-m-d H:i:s');

        $query = "INSERT INTO review_table (user_name, user_rating, user_review, datetime) VALUES ('$user_name', '$rating_data', '$user_review', '$datetime')";
        $result = $conn->query($query);

        if ($result) {
            echo "Review submitted successfully!";
        } else {
            echo "Error submitting review.";
        }
    }
}
