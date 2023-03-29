<?php include "inc/header.php";?>



<?php 
if(isset($_SESSION['email'])) : ?>

<form method="POST">
    <h3>Kreiraj novu objavu</h3>
    <textarea name="post_content" cols="60" rows="10" placeholder ="Vasa objava..."></textarea>
    <input type="submit" value="Objavi" name="submit">
</form>

    
<?php else : ?>
    


<?php endif?>


 <?php include "inc/footer.php";?>
