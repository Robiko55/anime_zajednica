<?php include "inc/header.php";?>
<h1> Welcome </h1>

<?php 
if(isset($_SESSION['email'])){

    echo "Vas email je:  ". $_SESSION['email'];
} else {
    echo "Molimo vas da se ulogujete!";
}

 include "inc/footer.php";?>
