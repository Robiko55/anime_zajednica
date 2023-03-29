<?php include "inc/header.php";?>



<?php 
if(isset($_SESSION['email'])) : ?>
<?php create_post(); ?>
<br>

<form method="POST">
    <h3>Kreiraj novu objavu</h3>
    <textarea name="post_content" cols="60" rows="10" placeholder ="Vasa objava..."></textarea>
    <input type="submit" value="Objavi" name="submit">
</form>


<div>
    <?php display_message(); ?>
</div>
<hr>


<div class="posts">

<?php fetch_all_posts();?>

</div>


<?php else : ?>
    
<div class="homepage">

<h1> Dobrodosli u anime chat room! </h1>
<p> Drustvena mreza za ljubitelje anime-a, slobodni ste da komentarisete i postavljate postove o vasim omiljenim anime serijalima! </p>
<h2> Pritisni <a href="login.php">ovde</a> da se ulogujes!</h2>

<img src="css/img/home.jpg" alt="">
</dv>

<?php endif?>


 <?php include "inc/footer.php";?>
