<?php
require_once('config.php');
require 'service\MailSender.php';
require 'models\Subscription.php';
require 'models\User.php';

function updateSubscriptionPrice($subscriptionId, $newPrice) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE subscriptions SET price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $newPrice, $subscriptionId);

    if ($stmt->execute()) {
        echo "Price updated successfully for subscription with ID: $subscriptionId";
    } else {
        echo "Error updating price for subscription with ID: $subscriptionId - " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
function getAllSubscriptions() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $subscriptions = [];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM subscriptions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subscription = new Subscription(
                $row['id'],
                $row['price'],
                $row['listening_url']
            );
            $subscriptions[] = $subscription;
        }
    }

    $conn->close();

    return $subscriptions;
}

$subscriptions = getAllSubscriptions();
$scraper = new WebScrapper();

foreach ($subscriptions as $subscription) {
    $currentPrice = $scraper->getOlxListingPrice($subscription->getListingUrl());

    if ($currentPrice !== null && $currentPrice != $subscription->getPrice()) {
        $users = $subscription->getUsers();
        foreach ($users as $user) {
            $mailSender = new MailSender();
            $mailSender->sendEmail($user->getEmail(), "Price Changed!", "Price for your subscription has changed to " . $currentPrice);
        }
        
        updateSubscriptionPrice($subscription->getId(), $currentPrice);
    }
}