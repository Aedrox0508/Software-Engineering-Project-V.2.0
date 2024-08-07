<?php
session_start();
require_once "../tools/functions.php";
require_once "../controllers/roomController.php";
require_once "../includes/user_header.php";

$error = '';



if (isset($_POST['add_room'])) {

    $room = new Room();
    $room->room_number = filter_var($_POST['roomNumber'], FILTER_SANITIZE_NUMBER_INT);
    $room->room_name = filter_var($_POST['roomName'], FILTER_SANITIZE_SPECIAL_CHARS);
    $room->room_level = filter_var($_POST['roomLevel'], FILTER_SANITIZE_NUMBER_INT);
    $room->room_desc = filter_var($_POST['roomDesc'], FILTER_SANITIZE_SPECIAL_CHARS);
    $room->room_location = filter_var($_POST['roomLocation'], FILTER_SANITIZE_SPECIAL_CHARS);
    $room->room_price = filter_var($_POST['roomPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $room->room_type = filter_var($_POST['roomType'], FILTER_SANITIZE_SPECIAL_CHARS);
    $room->room_beds = filter_var($_POST['roomBeds'], FILTER_SANITIZE_NUMBER_INT);
    $room->room_status = filter_var($_POST['roomStatus'], FILTER_SANITIZE_SPECIAL_CHARS);
    $room->room_amenity = isset($_POST['roomAmenity']) ? array_map('htmlspecialchars', $_POST['roomAmenity']) : [];

    $filesArray = [];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB

    if (!empty($_FILES["roomImage"]["name"][0])) {
        $totalFiles = count($_FILES['roomImage']['name']);
        
        for ($i = 0; $i < $totalFiles; $i++) {
            $imageName = $_FILES["roomImage"]["name"][$i];
            $tmpName = $_FILES["roomImage"]["tmp_name"][$i];
            $fileSize = $_FILES["roomImage"]["size"][$i];

            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if (in_array($imageExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
                $newImageName = uniqid() . '.' . $imageExtension;

                if (move_uploaded_file($tmpName, '../uploads/' . $newImageName)) {
                    $filesArray[] = $newImageName;
                } else {
                    $error .= "Failed to upload image: $imageName<br>";
                }
            } else {
                $error .= "Invalid file type or size for image: $imageName<br>";
            }
        }
    }

    if (
        validate_field($room->room_number) &&
        validate_field($room->room_name) &&
        validate_field($room->room_desc) &&
        validate_field($room->room_location) &&
        validate_field($room->room_price) &&
        validate_field($room->room_beds)
    ) {
        if ($room->addRoom($filesArray)) {
            echo "Room Registered";
        } else {
            $error .= 'Unable to add the room<br>';
        }
    } else {
        $error .= 'Please fill out the form completely!<br>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room</title>
    <!-- Include your CSS and JS files here -->
</head>
<body>
<main id="main-content" class="p-5">
    <a href="room.php" class="border p-2 text-center text-decoration-none text-dark rounded-2 d-flex align-items-center justify-content-center" style="width: 40px; height:40px;"><i class="fa-solid fa-arrow-left"></i></a>
    <h3 class="font-poppins mt-5 fs-5 fw-bold">Add New Room</h3>
    <hr>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="" method="post" class="w-100 addRoom-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="wrapper d-flex justify-content-between">
            <div class="d-flex flex-column col-lg-6">
                <div class="room-details col-12 border p-4 d-flex flex-column rounded-2 h-100">
                    <p class="font-poppins fw-bold">Room Details</p>

                    <label for="Room number" class="font-poppins text-md">Room Number </label>
                    <input class="p-2 rounded-2 border mt-1" type="number" name="roomNumber" required>

                    <label for="Room level" class="mt-3 font-poppins text-md">Room Level</label>
                    <select name="roomLevel" class="border p-2 rounded-2 mt-1" id="">
                        <!-- Add your options here -->
                        <option value="1">1</option>
                        <!-- Add other options as needed -->
                    </select>
    
                    <label for="">Room Name</label>
                    <input type="text" name="roomName">

                    <label for="Room number" class="mt-3 text-md font-poppins">Description </label>
                    <textarea class="font-geist text-md border rounded-2 mt-1" name="roomDesc" id="" rows="4" cols="50" maxlength="1000" required></textarea>

                    <div class="roomDescExtra d-flex flex-column border mt-3 rounded-2 p-3">
                        <p class="font-poppins fw-bold">Room extra details</p>
                        <label class="font-poppins text-md" for="">Location</label>
                        <input name="roomLocation" class="border rounded-2 outline-none p-2 text-md font-poppins mt-1" type="text" required>

                        <label class="mt-3 text-md font-poppins" for="">Room Price</label>
                        <input name="roomPrice" placeholder="₱" class="border p-2 rounded-2 outline-none font-poppins text-md mt-1" type="number" required>

                        <select class="border outline-none p-2 rounded-2 mt-3" name="roomType" id="">
                            <option value="Studio">Studio</option>
                            <!-- Add other options as needed -->
                        </select>

                        <label class="mt-3 font-poppins text-md" for="">Max Number of Beds</label>
                        <input name="roomBeds" class="font-poppins p-2 outline-none rounded-2 border mt-1 text-md mt-1" type="number" required>
                    </div>
                </div>
            </div>
            <div class="room-extras col-lg-5 p-4 border rounded-2 d-flex flex-column">
                <div class="room-image border rounded-2 p-4">
                    <p class="font-poppins fw-bold">Room Image</p>
                    <label for="inputGroupFile02" class="btn btn-primary" >
                    <input type="file" name="roomImage[]" id="inputGroupFile02" multiple onchange="preview(event);" style="display: none;" >
                    Upload Photo
                    </label>
                    <div id="preview" class="d-flex flex-wrap"></div>
                </div>
                
                <div class="room-amenities border rounded-2 p-4 mt-3">
                    <p class="font-poppins fw-bold">Room Amenities</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roomAmenity[]" value="wifi" id="wifi">
                        <label class="form-check-label" for="wifi">WiFi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roomAmenity[]" value="aircon" id="aircon">
                        <label class="form-check-label" for="aircon">Aircon</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roomAmenity[]" value="pets" id="pets">
                        <label class="form-check-label" for="pets">Pets</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roomAmenity[]" value="smoking area" id="smoking-area">
                        <label class="form-check-label" for="smoking-area">Smoking Area</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roomAmenity[]" value="bathroom" id="bathroom">
                        <label class="form-check-label" for="bathroom">Bathroom</label>
                    </div>
                </div>
                <div class="room-status border p-4 rounded-2 mt-3">
                    <p class="font-poppins fw-bold" >Room Status</p>
                    <input type="radio" name="roomStatus" value="Available"> Available <br>
                    <input type="radio" name="roomStatus" value="Unavailable"> Unavailable <br>
                    <input type="radio" name="roomStatus" value="Maintenance"> Maintenance <br>

                </div>

                <!-- Additional Room Extras Here -->
                <div class="save-cta d-flex justify-content-end mt-3">
                    <button type="button" onclick="window.location.href='room.php'" style="width: 150px;" class="border rounded-2 font-poppins text-md p-2 me-3">Discard</button>
                    <button type="submit" name="add_room" style="width: 150px;" class="border rounded-2 font-poppins text-md bg-purple text-white p-2">Save</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        function preview(event) {
            var totalFiles = event.target.files.length;
            for (var i = 0; i < totalFiles; i++) {
                var img = document.createElement("img");
                img.src = URL.createObjectURL(event.target.files[i]);
                img.style.width = "100px";
                img.style.height = "100px";
                img.style.margin = "5px";
                document.getElementById('preview').appendChild(img);
            }
        }
    </script>
</main>
</body>
</html>
