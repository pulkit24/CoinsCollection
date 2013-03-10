<?php
    // Confirm form submission
    if(!isset($_POST['country'])){
        header("Location: http://pulkit24.kodingen.com/");
        die();
    }

    // Collect coin details
    $countryName=$_POST['country'];
    $coinYear=$_POST['year'];
    $coinValueCurrency=$_POST['valueCurrency'];
    $coinValueAmount=$_POST['valueAmount'];
    $coinValueDenomination=$_POST['valueDenomination'];
    $colourName=$_POST['colour'];
    $inscriptionObverse=$_POST['inscriptionObverse'];
    $inscriptionReverse=$_POST['inscriptionReverse'];
    $quantity=$_POST['quantity'];
    
    // Check if this is a variant
    if(isset($_POST['isVariant']) && $_POST['isVariant']==true){
        // Get the id of the coin whose variant we are adding
        $variantCoinId=$_POST['variantCoinId'];
        // Get the paths to the original coin's images
        $imageObverseBaseName=$_POST['imageObversePath'];
        $imageReverseBaseName=$_POST['imageReversePath'];
    }else{
        $variantCoinId=null;
        // Upload the fresh images
        $imageObverse=$_FILES['photoObverse']['tmp_name'];
        $imageReverse=$_FILES['photoReverse']['tmp_name'];
        // Get temporary unique identifier
        $tempCoinIdentifier="";
        $tableInfo=mysql_query("show table status like Coin"); 
        if($tableInfoRecord=mysql_fetch_array($tableInfo))
            $tempCoinIdentifier=$tableInfoRecord['Auto_increment'];
        $imageObverseBaseName=$tempCoinIdentifier.urlencode(basename($_FILES['photoObverse']['name']));
        $imageReverseBaseName=$tempCoinIdentifier.urlencode(basename($_FILES['photoReverse']['name']));
    }
    
    // Coin image library
    $photoLibrary="coinImages/";
    $imageObversePath=$photoLibrary.$imageObverseBaseName;
    $imageReversePath=$photoLibrary.$imageReverseBaseName;
    
    // Save photos if not a variant
    if(!(isset($_POST['isVariant']) && $_POST['isVariant']==true)){
        move_uploaded_file($imageObverse,$imageObversePath);
        move_uploaded_file($imageReverse,$imageReversePath);
    }

    // Connect to database
    mysql_connect("pulkit24.kodingen.com","k37879_CoinsDB","rathsulen") or die(mysql_error());
    mysql_select_db("k37879_CoinsDB") or die(mysql_error());
    
    // Save country data
    if(mysql_query("insert into Country (Name) values ('$countryName')"))
        $countryId=mysql_insert_id();
    else{
        $countryRecords=mysql_query("select id from Country where Name='$countryName'");
        if($existingCountryRecord=mysql_fetch_array($countryRecords))
            $countryId=$existingCountryRecord['id'];
    }
    
    // Save image data
    mysql_query("insert into Images (ObverseFilePath, ReverseFilePath) values ('$imageObversePath','$imageReversePath')");
    $imageId=mysql_insert_id();
    
    // Save colour data
    if(mysql_query("insert into Colour (Name) values ('$colourName')"))
        $colourId=mysql_insert_id();
    else{
        $colourRecords=mysql_query("select id from Colour where Name='$colourName'");
        if($existingColourRecord=mysql_fetch_array($colourRecords))
            $colourId=$existingColourRecord['id'];
    }

    // Save inscription data
    mysql_query("insert into Inscription (ObverseInscription, ReverseInscription) values ('$inscriptionObverse','$inscriptionReverse')");
    $inscriptionId=mysql_insert_id();
    
    // Save value data
    mysql_query("insert into Value (Currency, Amount, Denomination) values ('$coinValueCurrency','$coinValueAmount','$coinValueDenomination')");
    $valueId=mysql_insert_id();
    
    // Save coin data
    mysql_query("insert into Coin (Country,Year,Inscription,Value,Colour,Image,Quantity,VariantId) values ('$countryId','$coinYear','$inscriptionId','$valueId','$colourId','$imageId','$quantity','$variantCoinId')");
    if(!isset($variantCoinId) || $variantCoinId==null)
        $variantCoinId=mysql_insert_id();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Coin Added</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    <header>
        <h1><a href="index.php">Coin Collection</a></h1>
        <h2>Coin Added to the Database</h2>
    </header>
    <section id="coinShowcase">
        <section>
            <section class="left">
                <h2>
                    <strong><?=$quantity?> <?=$colourName?></strong> coin<? if($quantity>1) echo "s"; ?> 
                    from <strong><?=$countryName?></strong>, 
                    of currency <strong><?=$coinValueCurrency." ".$coinValueAmount." ".$coinValueDenomination ?></strong> 
                    belonging to <strong><?=$coinYear?></strong>.
                </h2>
            </section>
            <section class="right">
                <p>This coin has been successfully added to the database.</p>
                <a href="add.php">Add another coin</a>
                <a href="#" class="variant" id="variantAdder">Add a variation of this coin</a>
            </section>
        </section>
        <section class="hidden variant" id="variantDetails">
            <form name="addCoinVariant" method="post" action="dbAdd.php">
                <section class="left">
                    <fieldset>
                        I have 
                        <input type="text" class="short" id="quantity" name="quantity" value="1" placeholder="1" /> 
                        more coin(s) 
                    </fieldset>
                    <fieldset>
                        of 
                        <input type="text" class="short" id="colour" name="colour" value="<?=$colourName?>" placeholder="<?=$colourName?>" /> 
                        colour 
                    </fieldset>
                    <fieldset>
                        from 
                        <input type="text" class="short" id="country" name="country" value="<?=$countryName?>" placeholder="<?=$countryName?>" /> 
                    </fieldset>
                    <fieldset>
                        of currency 
                        <input type="text" class="short" id="valueCurrency" name="valueCurrency" value="<?=$coinValueCurrency?>" placeholder="<?=$coinValueCurrency?>" /> 
                        <input type="text" class="short" id="valueAmount" name="valueAmount" value="<?=$coinValueAmount?>" placeholder="<?=$coinValueAmount?>" /> 
                        <input type="text" class="short" id="valueDenomination" name="valueDenomination" value="<?=$coinValueDenomination?>" placeholder="<?=$coinValueDenomination?>" /> 
                    </fieldset>
                    <fieldset>
                        belonging to 
                        <input type="text" class="short" id="year" name="year" value="<?=$coinYear?>" placeholder="<?=$coinYear?>" />
                    </fieldset>
                    <fieldset>
                        <input type="hidden" id="inscriptionObverse" name="inscriptionObverse" value="<?=$inscriptionObverse?>" />
                        <input type="hidden" id="inscriptionReverse" name="inscriptionReverse" value="<?=$inscriptionReverse?>" />
                        <input type="hidden" id="imageObversePath" name="imageObversePath" value="<?=$imageObverseBaseName?>" />
                        <input type="hidden" id="imageReversePath" name="imageReversePath" value="<?=$imageReverseBaseName?>" />
                        <input type="hidden" id="variantCoinId" name="variantCoinId" value="<?=$variantCoinId?>" />
                        <input type="hidden" id="isVariant" name="isVariant" value="true" />
                    </fieldset>
                </section>
                <section class="right">
                    <fieldset>
                        <input type="submit" name="submit" id="submit" value="Add this coin" />
                        <p>You will be able to add more variants similarly.</p>
                    </fieldset>
                </section>
            </form>
        </section>  
        <section class="clear">
            <section class="left">
                <h2>Obverse</h2>
<?
        if(!$imageObverseBaseName==null){
?>
                <img src="<?=$imageObversePath?>" alt="Photo of the obverse side" title="Obverse Side of the coin" />
<?
        }
?>
                <br />
                <p><?=$inscriptionObverse?></p>
            </section>
            <section class="right">
                <h2>Reverse</h2>
<?
        if(!$imageReverseBaseName==null){
?>

                <img src="<?=$imageReversePath?>" alt="Photo of the reverse side" title="Reverse side of the coin" />
<?
        }
?>                
                <br />
                <p><?=$inscriptionReverse?></p>
            </section>
            <section class="clear unstyled"></section>
        </section>
    </section>
    <script type="text/javascript">
        document.getElementById("variantAdder").onclick=function(){
            document.getElementById("variantDetails").setAttribute("class","variant");
        }
    </script>
</body>
</html>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    