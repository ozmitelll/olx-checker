<?php
require_once(__DIR__ . '/../config.php');
require 'service\WebScrapper.php';


class User
{
    private $id;
    private $name;
    private $email;
    private $passwordHash;

    public function __construct($id, $name, $email, $passwordHash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getSubscriptions()
    {
        $userSubscriptions = [];
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT subscriptions.* FROM user_subscriptions
        JOIN subscriptions ON user_subscriptions.subscription_id = subscriptions.id
        WHERE user_subscriptions.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $subscription = new Subscription(
                $row['id'],
                $row['price'],
                $row['listening_url']
            );
            $userSubscriptions[] = $subscription;
        }

        $stmt->close();
        $conn->close();

        return $userSubscriptions;
    }

    public function addSubscription($listeningUrl)
    {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT user_id FROM user_subscriptions
            JOIN subscriptions ON user_subscriptions.subscription_id = subscriptions.id
            WHERE user_subscriptions.user_id = ? AND subscriptions.listening_url = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $this->id, $listeningUrl);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "You are already subscribed to this URL.";
        } else {
            $sql = "SELECT id FROM subscriptions WHERE listening_url = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $listeningUrl);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $subscriptionId = $row['id'];
            } else {
                $scrapper = new WebScrapper();
                $price = $scrapper->getOlxListingPrice($listeningUrl);

                $sql = "INSERT INTO subscriptions (price, listening_url) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $price, $listeningUrl);
                if ($stmt->execute()) {
                    $subscriptionId = $stmt->insert_id;
                } else {
                    echo "Error inserting subscription: " . $stmt->error;
                    return;
                }
            }

            $sql = "INSERT INTO user_subscriptions (user_id, subscription_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $this->id, $subscriptionId);

            if ($stmt->execute()) {
                echo "Subscription added successfully.";
            } else {
                echo "Error adding user subscription: " . $stmt->error;
            }
        }


        $stmt->close();
        $conn->close();
    }
}
