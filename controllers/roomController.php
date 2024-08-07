<?php
require_once 'databaseController.php';

class Room
{
    public $room_id;
    public $room_number;
    public $room_name;
    public $room_level;
    public $room_location;
    public $room_status;
    public $room_desc;
    public $room_beds;
    public $room_type;
    public $room_price;
    public $room_image;
    public $room_amenity;
    public $addedAt;
    public $updatedAt;

    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addRoom($images)
    {
        // Convert arrays to JSON strings for storage
        $imagesJson = json_encode($images);
        $amenitiesJson = json_encode($this->room_amenity);

        // Prepare the SQL query
        $sql = "INSERT INTO rooms (roomNumber, roomName, roomLevel, roomLocation, roomStatus, roomDesc, roomBeds, roomType, roomPrice, roomImage, roomAmenity, addedAt, updatedAt)
                VALUES (:roomNumber, :roomName, :roomLevel, :roomLocation, :roomStatus, :roomDesc, :roomBeds, :roomType, :roomPrice, :roomImage, :roomAmenity, CURDATE(), CURDATE())";

        $query = $this->db->connect()->prepare($sql);

        // Bind parameters to the query
        $query->bindValue(':roomNumber', htmlspecialchars($this->room_number));
        $query->bindValue(':roomName', htmlspecialchars($this->room_name));
        $query->bindValue(':roomLevel', htmlspecialchars($this->room_level));
        $query->bindValue(':roomLocation', htmlspecialchars($this->room_location));
        $query->bindValue(':roomStatus', htmlspecialchars($this->room_status));
        $query->bindValue(':roomDesc', htmlspecialchars($this->room_desc));
        $query->bindValue(':roomBeds', htmlspecialchars($this->room_beds));
        $query->bindValue(':roomType', htmlspecialchars($this->room_type));
        $query->bindValue(':roomPrice', htmlspecialchars($this->room_price));
        $query->bindValue(':roomImage', $imagesJson);
        $query->bindValue(':roomAmenity', $amenitiesJson);

        try {
            // Execute the query
            if ($query->execute()) {
                return true;
            } else {
                // Log any errors from the query
                error_log("SQL error: " . print_r($query->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error
            error_log("Failed to add room: " . $e->getMessage());
            return false;
        }
    }
    public function fetchRooms() {
        $sql = "SELECT * FROM rooms";
        $query = $this->db->connect()->prepare($sql);

        try {
            if ($query->execute()) {
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Log any errors from the query
                error_log(print_r($query->errorInfo(), true));
                return [];
            }
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error
            error_log($e->getMessage());
            return [];
        }
    }

    public function fetchAllImages($room_id) {
        $sql = "SELECT roomImage FROM rooms WHERE room_id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $room_id);
    
        try {
            if ($query->execute()) {
                $result = $query->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    // Assuming roomImage is stored as a JSON array in the database
                    $images = json_decode($result['roomImage'], true);
                    
                    if (is_array($images)) {
                        return $images; // Return the array of images
                    }
                }
            } else {
                // Log any errors from the query
                error_log(print_r($query->errorInfo(), true));
            }
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error
            error_log($e->getMessage());
        }
        return []; // Return an empty array if no images are found
    }
}
