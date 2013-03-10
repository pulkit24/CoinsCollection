<?php
    // Connect to database
    $con=mysql_connect("pulkit24.kodingen.com","k37879_CoinsDB","rathsulen") or die(mysql_error());
    mysql_select_db("k37879_CoinsDB",$con) or die(mysql_error());
?>
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8" />
    <title>Add Coins</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    <header>
        <h1><a href="index.php">Coin Collection</a></h1>
        <h2>Add a Coin to the Database</h2>
    </header>
    <section>
        <form name="addCoin" method="post" action="dbAdd.php" enctype="multipart/form-data">
            <fieldset>
                <label for="country">Country (select or enter): </label>
                <input type="text" name="country" id="country" placeholder="e.g., India" />
                <input type="button" name="moreCountries" id="moreCountries" value="Country List" />
                <section class="hidden" id="countryList">
                    <section class="column">
<?
                // Get list of countries
                $countryCountRecords=mysql_query("select count(*) from Country");
                if($countryCountRecord=mysql_fetch_row($countryCountRecords))
                    $countryCount=$countryCountRecord[0];
                if($countryCount==0){
?>
                        <p>No countries added yet. As soon as you add a coin, we'll list its country here.</p>
<?
                }else{
                    $countryRecords=mysql_query("select id, Name from Country");
    
                    // Compute the no. of countries to be put in each column, assuming a fixed column count
                    // Note that if the country count is less than column count, the divide function won't work properly
                    // Hence, in that case, just put all the countries in a single column
                    $columnCount=2;
                    if($countryCount>$columnCount)
                        $countriesPerColumn=round($countryCount/$columnCount);
                    else $countriesPerColumn=$countryCount;
                    $currentColumn=1;
                    $countriesCoveredInColumn=0;
                    while($countryRecord=mysql_fetch_array($countryRecords)){
?>
                        <input type="button" class="unstyled" id="<?=$countryRecord['id']?>" 
                        value="<?=$countryRecord['Name']?>" onclick="selectCountry(this.value)" />
                        <br />
<?
                        $countriesCoveredInColumn++;
                        if($countriesCoveredInColumn==$countriesPerColumn){
                            $countriesCoveredInColumn=0;
                            $currentColumn++;
?>
                    </section>
                    <section class="column">
<?
                        }
                    }
                }
?>
                    </section>
                </section>
            </fieldset>
            <fieldset>
                <label for="year">Year: </label>
                <input type="text" name="year" id="year" placeholder="e.g., 1900" />
            </fieldset>
            <fieldset>
                <label for="valueCurrency">Coin Value: </label>
                <input type="text" class="short" name="valueCurrency" id="valueCurrency" placeholder="INR" />
                <input type="text" class="short" name="valueAmount" id="valueAmount" placeholder="42" />
                <input type="text" class="short" name="valueDenomination" id="valueDenomination" placeholder="Anna" />
            </fieldset>
            <fieldset>
                <label for="colour">Colour: </label>
                <input type="text" name="colour" id="colour" placeholder="e.g., Silver" />
            </fieldset>
            <fieldset>
                <section class="left">
                    <label for="photoObverse" class="long">Photo of the coin's <strong>obverse</strong> (front) side</label>
                    <br />
                    <input type="file" name="photoObverse" id="photoObverse" class="long" />
                </section>
                <section class="left">
                    <label for="inscriptionObverse">What does the coin say on this side?</label>
                    <textarea name="inscriptionObverse" id="inscriptionObverse"></textarea>
                </section>
            </fieldset>
            <fieldset>
                <section class="left">
                    <label for="photoReverse" class="long">Photo of the coin's <strong>reverse</strong> (rear) side</label>
                    <br />
                    <input type="file" name="photoReverse" id="photoReverse" class="long" />
                </section>
                <section class="left">
                    <label for="inscriptionReverse">What does the coin say on this side?</label>
                    <textarea name="inscriptionReverse" id="inscriptionReverse"></textarea>
                </section>
            </fieldset>
            <fieldset>
                <label for="quantity">How many such coins do you have?</label>
                <input type="text" name="quantity" id="quantity" value="1" />
            </fieldset>
            <fieldset>
                <label for="submit" class="long">Done filling all the fields?</label>
                <input type="submit" name="submit" id="submit" value="Add!" />
            </fieldset>
        </form>
    </section>
    <script type="text/javascript">
        var countryListIsVisible=false;
        function toggleCountryList(){
            var countryListBox=document.getElementById("countryList");
            if(!countryListIsVisible) countryListBox.setAttribute("class","");
            else countryListBox.setAttribute("class","hidden");
            countryListIsVisible=!countryListIsVisible;
        }
        document.getElementById("moreCountries").onclick=function(){
            toggleCountryList();
        }
        function selectCountry(countryName){
            if(countryListIsVisible) toggleCountryList();
            document.getElementById("country").value=countryName;
        }
    </script>
</body>
</html>



