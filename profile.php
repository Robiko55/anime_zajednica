<?php include ('inc/header.php');

user_restrictions();
?>

</div> <?php display_message(); ?> </div>
<?php 

$user = get_user();
echo "<img class='profile-image' src='" . $user['profile_image'] . "'>";

user_profile_image_upload();

?>

<form method="POST" enctype="multipart/form-data">
    Izaberite profilnu sliku:
    <input type="file" name="profile_image_file">
    <input type="submit" value="Postavi sliku" name="submit">

<?php include('inc/footer.php'); ?>