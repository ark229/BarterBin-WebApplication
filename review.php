<?php
require_once('session.php');
require_once('nav2.php');
include 'config.php';

// Submit the review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $name = $_POST['name'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO reviews (rating, name, review) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $rating, $name, $review);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$result = $conn->query("SELECT rating, name, review FROM reviews");

$reviews = [];
$total_rating = 0;
$total_reviews = 0;
$star_count = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);


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

$conn->close();

?>



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>BarginBin</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Animated-Type-Heading.css">
    <link rel="stylesheet" href="assets/css/DA_About.css">
    <link rel="stylesheet" href="assets/css/Navbar-Centered-Brand-icons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.min.js"></script>
    <style>
        .star-light {
            color: #d4d4d4;
        }

        .text-warning {
            color: #ffc107;
        }
    </style>
</head>


<body style="color: var(--bs-orange);">
    <!-- NAV GOES HERE -->


    <div class="container">
        <h1 class="mt-5 mb-5">Ratings and Reviews</h1>
        <div class="card">
            <div class="card-header">Tells us about your experience!</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <h1 class="text-warning mt-4 mb-4">
                            <b><span id="average_rating">0.0</span> / 5</b>
                        </h1>
                        <div class="mb-3">
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                        </div>
                        <h3><span id="total_review">0</span> Review</h3>
                    </div>

                    <div class="col-sm-4">
                        <p>
                        <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_five_star_review">0</span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                        </div>
                        </p>
                        <p>
                        <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_four_star_review">0</span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                        </div>
                        </p>

                        <p>
                        <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_three_star_review">0</span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                        </div>
                        </p>


                        <p>
                        <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_two_star_review">0</span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                        </div>
                        </p>
                        <p>
                        <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_one_star_review">0</span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                        </div>
                        </p>
                    </div>

                    <div class="col-sm-4 text-center">
                        <h3 class="mt-4 mb-3">Write Your Review Here</h3>
                        <form action="review.php" method="post" id="review_form" name="review_form">
                            <input type="hidden" name="rating" id="rating" value="">
                            <h4 class="text-center mt-2 mb-4">
                                <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                                <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                                <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                                <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                                <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                            </h4>
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name" required />
                            </div>
                            <div class="form-group">
                                <textarea name="review" id="review" class="form-control" placeholder="Type Review Here" required></textarea>
                            </div>
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="save_review">Submit</button>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>

        <div class="mt-5" id="review_content"></div>
    </div>

    <div id="review_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="submit_review.php" method="post" id="review_modal_form">
                        <h4 class="text-center mt-2 mb-4">
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                            <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                        </h4>
                        <input type="hidden" name="rating" id="modal_rating" value="">
                        <div class="form-group">
                            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Your Name" required />
                        </div>
                        <div class="form-group">
                            <textarea name="user_review" id="user_review" class="form-control" placeholder="Type Review Here" required></textarea>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary" id="save_review">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .progress-label-left {
            float: left;
            margin-right: 0.5em;
            line-height: 1em;
        }

        .progress-label-right {
            float: right;
            margin-left: 0.3em;
            line-height: 1em;
        }

        .star-light {
            color: #e9ecef;
        }
    </style>


    <footer class="text-center" style="padding-top: 119px">
        <hr style="height: 2px; color: #214a80; background-color: #172f76" />
        <div class="container text-muted py-4 py-lg-5">
            <ul class="list-inline">
                <li class="list-inline-item me-4">
                    <a class="link-secondary" href="about.php">About</a>
                </li>
                <li class="list-inline-item me-4">
                    <a class="link-secondary" href="contact.php">Contact</a>
                </li>
                <li class="list-inline-item">
                    <a class="link-secondary" href="register.php">Register</a>
                </li>
                <li class="list-inline-item">
                    <a class="link-secondary" href="review.php">Leave Review</a>
                </li>
            </ul>
            <ul class="list-inline">
                <li class="list-inline-item me-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-facebook">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path>
                    </svg>
                </li>
                <li class="list-inline-item me-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-twitter">
                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path>
                    </svg>
                </li>
                <li class="list-inline-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-instagram">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"></path>
                    </svg>
                </li>
            </ul>
            <p class="mb-0">Copyright © 2023 Barter Bin</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $(".submit_star").on("click", function() {
                var rating = $(this).data("rating");
                $("#rating").val(rating);
                $(".submit_star").removeClass("text-warning");
                for (var i = 1; i <= rating; i++) {
                    $("#submit_star_" + i).addClass("text-warning");
                }
            });

            // Display fetched data
            var fetchedReviews = <?php echo json_encode($reviews); ?>;
            var averageRating = <?php echo $average_rating; ?>;
            var totalReviews = <?php echo $total_reviews; ?>;
            var percentageRatings = <?php echo json_encode($percentage_ratings); ?>;
            displayFetchedData(fetchedReviews, averageRating, totalReviews, percentageRatings);
        });

        function displayFetchedData(fetchedReviews, averageRating, totalReviews, percentageRatings) {
            $("#average_rating").text(averageRating);
            $("#total_review").text(totalReviews);

            $("#total_five_star_review").text(percentageRatings[5]);
            $("#total_four_star_review").text(percentageRatings[4]);
            $("#total_three_star_review").text(percentageRatings[3]);
            $("#total_two_star_review").text(percentageRatings[2]);
            $("#total_one_star_review").text(percentageRatings[1]);

            $("#five_star_progress").css("width", percentageRatings[5] + "%");
            $("#four_star_progress").css("width", percentageRatings[4] + "%");
            $("#three_star_progress").css("width", percentageRatings[3] + "%");
            $("#two_star_progress").css("width", percentageRatings[2] + "%");
            $("#one_star_progress").css("width", percentageRatings[1] + "%");

            // Set main stars
            var mainStars = Math.round(averageRating);
            $(".main_star").removeClass("text-warning");
            for (var i = 1; i <= mainStars; i++) {
                $(".main_star:nth-child(" + i + ")").addClass("text-warning");
            }

            // Display review content
            var reviewContent = "";
            for (var i = 0; i < fetchedReviews.length; i++) {
                reviewContent += '<div class="card mt-3"><div class="card-header">';
                for (var j = 1; j <= 5; j++) {
                    if (j <= fetchedReviews[i].rating) {
                        reviewContent += '<i class="fas fa-star text-warning mr-1"></i>';
                    } else {
                        reviewContent += '<i class="fas fa-star star-light mr-1"></i>';
                    }
                }
                reviewContent += '</div><div class="card-body"><h5 class="card-title">' + fetchedReviews[i].name + '</h5><p class="card-text">' + fetchedReviews[i].review + '</p></div></div>';
            }
            $("#review_content").html(reviewContent);
        }
    </script>




    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Animated-Type-Heading-type-headline.js"></script>

</body>

</html>