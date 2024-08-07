<!DOCTYPE html>
<html lang="en">
<?php
include_once "../includes/user_header.php";
require_once "../controllers/roomController.php";

$roomObj = new Room();
$rooms = $roomObj->fetchRooms();
?>

<!-- ADD ROOM FORM MAIN CONTAINER -->
<main id="roomMain">
    <!-- FOR SESSION NAME -->
    <div class="admin-Topnavbar d-flex ">
        <h3 class="session-name font-poppins me-auto">Room</h3>
        <div class="search-bar d-flex align-items-center justify-content-around font-poppins border p-2 text-sm rounded-2 me-3">
            <input type="text" placeholder="Search here"><i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <div class="notifications border p-2 w-40 me-2 rounded-2 d-flex justify-content-center align-items-center ">
            <a href="#" class="text-dark"><i class="fa-solid fa-bell"></i></a>
        </div>

        <div class="message  d-flex border p-2 w-40 rounded-2 justify-content-center align-items-center">
            <a href="#" class="text-dark"><i class="fa-solid fa-comment"></i></a>
        </div>
    </div>

    <!-- ROOM CATEGORY -->
    <div class="roomCategory mt-5 d-flex justify-content-between">
        <div class="status-holder">
            <button class="status-btn border-0 me-2 p-2 rounded-2 font-poppins text-sm text-center">Available</button>
            <button class="status-btn border-0 me-2 p-2 rounded-2 font-poppins text-sm text-center">Unavailable</button>
            <button class="status-btn border-0 p-2 rounded-2 font-poppins text-sm text-center">Maintenance</button>
        </div>

        <div class="sorting">
            <select name="" id="" class="text-sm font-poppins rounded-2 border p-2 me-3">
                <option value="">Sorted By</option>
                <option value="">Room No.</option>
                <option value="">Date</option>
                <option value="">Amenities</option>
            </select>

            <a href="add_room.php" class="font-poppins border-0 text-sm p-2 text-white bg-purple text-decoration-none rounded-2">
                <span class="ms-1">Add New Room</span>
                <i class="fa-solid fa-plus ms-2"></i>
            </a>
        </div>
    </div>
    <!-- ROOM IMAGE SECTION -->

    <div class="">
        <table class=" w-100 text-center text-sm font-geist table-bordered  ">
            <thead class="border-top border-bottom">
                <tr class="mx-auto">
                    <th>Room ID</th>
                    <th style="width:35%;">Room Name</th>
                    <th>Bed Type</th>
                    <th>Room Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rooms as $room) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['room_id']); ?></td>
                        <td>
                            <p class="text-center"><?php echo htmlspecialchars($room['roomName']); ?></p>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($room['roomType']); ?>
                        </td>
                        <td>
                            <span>Floor <?php echo htmlspecialchars($room['roomLevel']); ?> </span>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($room['roomStatus']); ?>
                        </td>
                        <td class="d ">
                            <div class="border">
                                <a class="btn btn-primary" href="<? ?>"><i class="fa-solid fa-pen"></i></a>
                                <a class="btn btn-danger" href="<? ?>"><i class="fa-solid fa-trash"></i></a>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>

<?php
include_once "../scripts/external_scripts.php";
?>
</body>

</html>