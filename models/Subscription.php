<?php
require_once(__DIR__ . '/../config.php');

class Subscription
{
    private $id;
    private $price;
    private $listeningUrl;

    public function __construct($id, $price, $listingUrl)
    {
        $this->id = $id;
        $this->price = $price;
        $this->listeningUrl = $listingUrl;
    }

    public function getId(){
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getListingUrl()
    {
        return $this->listeningUrl;
    }

    public function getUsers()
    {
        $subscriptionUsers = [];

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT users.* FROM user_subscriptions
        JOIN users ON user_subscriptions.user_id = users.id
        WHERE user_subscriptions.subscription_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password_hash']
            );
            $subscriptionUsers[] = $user;
        }

        $stmt->close();
        $conn->close();

        return $subscriptionUsers;
    }
}
