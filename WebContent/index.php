<?php
    // Connect to database
    $con=mysql_connect("pulkit24.kodingen.com","k37879_CoinsDB","rathsulen") or die(mysql_error());
    mysql_select_db("k37879_CoinsDB",$con) or die(mysql_error());
?>
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8" />
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    <header>
        <h1><a href="index.php">Coin Collection</a></h1>
        <h2>Welcome!</h2>
    </header>
    <section id="coinShowcase">
        <a href="add.php" class="giant">Add a New Coin</a>
        <br />
        <a href="dbView.php" class="giant variant">View/Edit Coins</a>
    </section>
</body>
</html>



