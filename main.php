<?php

require_once('config.php');
require_once('session.php');
require_once('nav-main.php');

// Get the current user ID (you should replace this with the ID of the logged-in user)
$current_user_id = $_SESSION['user_id'];

// Process the form data
$city = $_POST['city'] ?? '';
$state = $_POST['state_name'] ?? '';
$ignore_location = isset($_POST['ignore_location']) ? true : false;

// Add the debugging statements here
if ($ignore_location) {
    echo "Ignore location is checked<br>";
} else {
    echo "Ignore location is not checked<br>";
}
echo "City: $city<br>";
echo "State: $state<br>";

// Fetch the user's needs and offers
$sql = "SELECT GROUP_CONCAT(DISTINCT needs.needs) as needs, GROUP_CONCAT(DISTINCT offers.offers) as offers
        FROM users
        LEFT JOIN needs ON users.user_id = needs.user_id
        LEFT JOIN offers ON users.user_id = offers.user_id
        WHERE users.user_id = ?
        GROUP BY users.user_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_needs_offers = $result->fetch_assoc();

// Add the debugging code here
echo "Current user needs: {$user_needs_offers['needs']}<br>";
echo "Current user offers: {$user_needs_offers['offers']}<br>";

// Find 100% and 50% matches
$matches_100 = findMatches($conn, $current_user_id, $city, $state, $ignore_location, true);
$matches_50 = findMatches($conn, $current_user_id, $city, $state, $ignore_location, false);


// Function to find matches
function findMatches($conn, $current_user_id, $city, $state, $ignore_location, $full_match)
{
    $location_condition = $ignore_location ? '' : "AND users.city = ? AND users.state_name = ?";
    $match_condition = $full_match ? "HAVING COUNT(needs_match) = COUNT(offers_match)" : "HAVING COUNT(needs_match) > 0 OR COUNT(offers_match) > 0";

    $sql = "SELECT users.user_id, users.city, users.state_name,
                   COUNT(DISTINCT my_needs.needs) AS needs_match,
                   COUNT(DISTINCT my_offers.offers) AS offers_match,
                   users.date_added as date_added
            FROM users
            INNER JOIN needs ON users.user_id = needs.user_id
            INNER JOIN offers ON users.user_id = offers.user_id
            LEFT JOIN needs AS my_needs ON my_needs.user_id = ? AND needs.needs = my_needs.needs
            LEFT JOIN offers AS my_offers ON my_offers.user_id = ? AND offers.offers = my_offers.offers
            WHERE users.user_id != ? $location_condition
            GROUP BY users.user_id
            $match_condition";


    // Add debugging statements here
    echo "Query: $sql<br>";
    echo "Ignore location: " . ($ignore_location ? "true" : "false") . "<br>";
    echo "Full match: " . ($full_match ? "true" : "false") . "<br>";

    $stmt = $conn->prepare($sql);

    if ($ignore_location) {
        $stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
    } else {
        $stmt->bind_param("iiiss", $current_user_id, $current_user_id, $current_user_id, $city, $state);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $matches = [];


    while ($row = $result->fetch_assoc()) {

        // Add the debugging code here
        echo "Matched user ID: {$row['user_id']}<br>";
        echo "Matched user needs: {$row['needs_match']}<br>";
        echo "Matched user offers: {$row['offers_match']}<br>";

        $matched_user_id = $row['user_id'];
        $sql_needs_offers = "SELECT GROUP_CONCAT(DISTINCT needs.needs) as needs, GROUP_CONCAT(DISTINCT offers.offers) as offers
            FROM users
            LEFT JOIN needs ON users.user_id = needs.user_id
            LEFT JOIN offers ON users.user_id = offers.user_id
            WHERE users.user_id = ?
            GROUP BY users.user_id";
        $stmt_needs_offers = $conn->prepare($sql_needs_offers);
        $stmt_needs_offers->bind_param("i", $matched_user_id);
        $stmt_needs_offers->execute();
        $result_needs_offers = $stmt_needs_offers->get_result();
        $matched_user_needs_offers = $result_needs_offers->fetch_assoc();

        $row['needs'] = implode(', ', array_filter(explode(',', $matched_user_needs_offers['needs'])));
        $row['offers'] = implode(', ', array_filter(explode(',', $matched_user_needs_offers['offers'])));

        unset($row['needs_match']);
        unset($row['offers_match']);

        $matches[] = $row;
    }

    return $matches;
}

//Function to get time elapsed
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Function to get the total number of users
function getTotalUsers($conn, $current_user_id)
{
    $sql = "SELECT COUNT(*) as total_users FROM users WHERE user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_users'];
}

// Fetch data and calculate match percentage here
$total_users = getTotalUsers($conn, $current_user_id);

// Merge the two matches arrays
$all_matches = array_merge($matches_100, $matches_50);

// Calculate match percentage
$match_percentage = count($matches_100) / $total_users * 100;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Barter Bin - Main</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Animated-Type-Heading.css">
    <link rel="stylesheet" href="assets/css/DA_About.css">
    <link rel="stylesheet" href="assets/css/Navbar-Centered-Brand-icons.css">
</head>

<body>
    <!-- NAV BAR -->
    <div class="container" style="margin-top: 30px;">
        <div class="row">
            <div class="col-md-12" style="margin-right: 0px;margin-left: 35px;">

                <!-- SEARCH FORM -->

                <form action="main.php" method="post">
                    <input class="form-control form-control-lg" name="city" type="text" style="width: 250px;margin-top: 10px;margin-bottom: 10px;text-align: center;" placeholder="City">
                    <select class="bg-light form-select" name="state_name" value="state" style="width: 250px;height: 48px;font-size: 20px;text-align: center;color: var(--bs-gray);">
                        <optgroup label="State">
                            <option value="AK">AK</option>
                            <option value="AL">AL</option>
                            <option value="AR">AR</option>
                            <option value="AZ">AZ</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DC">DC</option>
                            <option value="DE">DE</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="IA">IA</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="MA">MA</option>
                            <option value="MD">MD</option>
                            <option value="ME">ME</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MO">MO</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="NC">NC</option>
                            <option value="ND">ND</option>
                            <option value="NE">NE</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NV">NV</option>
                            <option value="NY">NY</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VA">VA</option>
                            <option value="VT">VT</option>
                            <option value="WA">WA</option>
                            <option value="WI">WI</option>
                            <option value="WV">WV</option>
                            <option value="WY">WY</option>
                        </optgroup>
                    </select>
                    <div class="form-check" style="width: 250px;margin-top: 10px;margin-left: 10px;text-align: left;">
                        <input class="form-check-input" type="checkbox" id="formCheck-1" name="ignore_location"><label class="form-check-label" for="formCheck-1" style="font-size: 18px; color: #495057;">Ignore Location</label>
                    </div><button class="btn btn-primary btn-lg" type="submit" style="width: 250px;background: var(--bs-green);margin-top: 10px;margin-left: 0px;margin-bottom: 10px;">Update Results</button>
                </form>

            </div>
        </div>
    </div>

    <!-- WHERE USER MATCHES WILL BE DISPLAYED -->
    <div class="container" style="margin-top: 30px;">
        <div class="row">
            <div class="col-md-12">
                <h1 style="font-size: 28px;color: var(--bs-gray-800);">
                    <span style="font-weight: bold;color: var(--bs-teal);">
                        <?php echo round($match_percentage); ?>% Matches
                    </span>
                    (They have what you need, they want what you can offer, or both).
                </h1>
            </div>
        </div>
    </div>
    <div class="container" style="margin-bottom: 25px;">
        <div class="row">
            <?php
            $all_matches = array_merge($matches_100, $matches_50);
            foreach ($all_matches as $match) : ?>
                <div class="col-md-6" style="margin-bottom: 20px;">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" style="font-weight: bold; color: #212529;">Their wants:</h4>
                            <p class="wantsOffers" style="font-size: 18px;color: var(--bs-gray-600);"><?php echo htmlspecialchars($match['needs']); ?></p>
                            <h4 class="card-title" style="font-weight: bold; color: #212529;">Their offers:</h4>
                            <p class="wantsOffers" style="font-size: 18px;color: var(--bs-gray-600);"><?php echo htmlspecialchars($match['offers']); ?></p><a href="#" style="text-align: left;font-size: 25px;color: var(--bs-orange);font-weight: bold;">Contact Barter Buddy</a>
                            <p class="barter-data" style="color: var(--bs-gray-500);font-size: 14px;">POSTED <?php echo time_elapsed_string($match['date_added']); ?> - <?php echo htmlspecialchars($match['city'] . ', ' . $match['state_name']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <footer class="text-center" style="padding-top: 119px;">
        <div class="container text-muted py-4 py-lg-5">
            <ul class="list-inline">
                <li class="list-inline-item me-4"><a class="link-secondary" href="about.php">About</a></li>
                <li class="list-inline-item me-4"><a class="link-secondary" href="contact.php">Contact</a></li>
                <li class="list-inline-item"><a class="link-secondary" href="register.php">Register</a></li>
            </ul>
            <ul class="list-inline">
                <li class="list-inline-item me-4"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-facebook">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path>
                    </svg></li>
                <li class="list-inline-item me-4"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-twitter">
                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path>
                    </svg></li>
                <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-instagram">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"></path>
                    </svg></li>
            </ul>
            <p class="mb-0">Copyright © 2023 Barter Bin</p>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Animated-Type-Heading-type-headline.js"></script>
</body>

</html>