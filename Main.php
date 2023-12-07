<?php
session_start();

if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    header("Location: pages/login.php");
    exit();
}

require 'service\MailSender.php';
require 'models\Subscription.php';
require 'models\User.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>OLX - Checker</title>
</head>

<body>
    <header class="header">
    <h1 class="label">OLX - Checker</h1>
        <?php if (isset($_SESSION["user_id"])) { ?>
            <ul class="nav">
                <li class="link"><a class="element" href="pages/logout.php">Logout</a></li>
            </ul>
        <?php } else { ?>
            <ul class="nav">
                <li class="link"><a class="element" href="pages/login.php">Login</a></li>
                <li class="link"><a class="element" href="pages/registrate.php">Registration</a></li>
            </ul>
        <?php } ?>
    </header>
    
    <div class="container">
    <?php if (isset($_SESSION["user_name"])) { ?>
        <p class="welcome">Welcome, <?php echo $_SESSION["user_name"]; ?> !</p>


        <h2 class="subscription-title">Your Subscriptions</h2>
        <?php
        $user = new User($_SESSION["user_id"], $_SESSION["user_name"], $_SESSION["user_email"],$_SESSION["user_password"]);
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["listeningUrl"])) {
            $listeningUrl = $_POST["listeningUrl"];
            $user->addSubscription($listeningUrl);
        }
        $subscriptions = $user->getSubscriptions();

        if (!empty($subscriptions)) {
            echo "<ul class='subscriptions-list'>";
            foreach ($subscriptions as $subscription) {
                echo "<li class='sub-item'>{$subscription->getListingUrl()}</li>";
            }
            echo "</ul>";
        } else {
            echo "You don't have any subscriptions.";
        }
        ?>

        <button id="addSubscriptionBtn">Add Subscription</button>
        <div id="subscriptionForm" style="display:none;">
            <form method="POST" id="subscriptionForm">
                <label for="listeningUrl">Enter URL:</label>
                <input type="text" id="listeningUrl" name="listeningUrl" required><br>
                <input type="submit" value="Subscribe">
            </form>
        </div>

        <script>
            const addSubscriptionBtn = document.getElementById("addSubscriptionBtn");
            const subscriptionForm = document.getElementById("subscriptionForm");

            addSubscriptionBtn.addEventListener("click", function() {
                subscriptionForm.style.display = "block";
            });
        </script>
    <?php } ?>
    </div>
</body>

</html>
