<?php
    // Connect to database
    mysql_connect("pulkit24.kodingen.com","k37879_CoinsDB","rathsulen") or die(mysql_error());
    mysql_select_db("k37879_CoinsDB") or die(mysql_error());
    
    // Compute pagination
    $paginationLimit=10; //records per page
    
    if(isset($_GET['page']) && $_GET['page']!=null && is_numeric($_GET['page']) && $_GET['page']>0)
        $currentPage=$_GET['page'];
    else $currentPage=1;
    
    $startRecord=0+($currentPage-1)*$paginationLimit;
    
    // Page count
    $coinRecords=mysql_query("select count(*) from Coin");
    if($coinRecord=mysql_fetch_row($coinRecords)){
        $coinCount=$coinRecord[0];
        $pageCount=ceil($coinCount/$paginationLimit);
    }else $pageCount="?";
    
    if(is_numeric($pageCount) && $currentPage>$pageCount)
        $currentPage=$pageCount;
    
    // Any record that was just changed and needs highlighting?
    if(isset($_GET['highlight']) && $_GET['highlight']!=null && is_numeric($_GET['highlight']) && $_GET['highlight']>0)
        $highlightedCell=$_GET['highlight'];
    else $highlightedCell=0;

    // Get coin records
    $coinRecords=mysql_query("select n.id as id, n.Year as Year, c.Name as Country, n.Quantity as Quantity, v.Currency as Currency, v.Amount as Amount, v.Denomination as Denomination, l.Name as Colour, i.ObverseInscription as ObverseInscription, i.ReverseInscription as ReverseInscription, g.ObverseFilePath as ObverseFilePath, g.ReverseFilePath as ReverseFilePath from Coin n, Country c, Images g, Inscription i, Value v, Colour l where n.Country=c.id and n.Image=g.id and n.Inscription=i.id and n.Value=v.id and n.Colour=l.id order by n.id limit $startRecord,".($paginationLimit-1));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Coin Collection</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    <header>
        <h1><a href="index.php">Coin Collection</a></h1>
        <h2>Coins in the Database</h2>
    </header>
    <section>
        <form name="pagination1" id="pagination1" class="pagination" action="dbView.php" method="get">
            <fieldset>
                <input type="submit" class="hidden" value="" />
                <input type="submit" onclick="this.form.page.value=<?=($currentPage-1)?>;this.form.highlight.value='0';this.form.submit()" value="Previous" /> 
                Page
                <input type="text" name="page" value="<?=$currentPage?>" placeholder="1" />
                of <?=$pageCount?>
                <input type="hidden" name="highlight" value="0" />
                <input type="submit" onclick="this.form.page.value=<?=($currentPage+1)?>;this.form.highlight.value='0';this.form.submit()" value="Next" />
            </fieldset>
        </form>
        <header id="fixedHeader">
            <section class="cell">
                <h2>Country</h2>
                <p>Click to edit</p>
            </section>
            <section class="cell">
                <h2>Year</h2>
                <p>Click to edit</p>
            </section>
            <section class="cell">
                <h2>Value</h2>
                <p>Click to edit</p>
            </section>
            <section class="cell">
                <h2>Colour</h2>
                <p>Click to edit</p>
            </section>
            <section class="cell">
                <h2>Inscriptions</h2>
                <p>Click to edit</p>
            </section>
            <section class="cell imageShowcase">
                <h2>Images</h2>
                <p>Click to zoom</p>
            </section>
        </header>
<?
    $i=1;
    while($coinRecord=mysql_fetch_array($coinRecords)){
?>
        <section class="coinRecord">
            <section class="cell <?=($highlightedCell==$i)?'highlighted':''?>" onclick="showEdit(['<?=$i++?>','<?=$coinRecord['id']?>'],['Country','<?=$coinRecord['Country']?>'])">
                <p><?=$coinRecord['Country']?></p>
            </section>
            <section class="cell <?=($highlightedCell==$i)?'highlighted':''?>" onclick="showEdit(['<?=$i++?>','<?=$coinRecord['id']?>'],['Year','<?=$coinRecord['Year']?>','short'])">
                <p><?=$coinRecord['Year']?></p>
            </section>
            <section class="cell <?=($highlightedCell==$i)?'highlighted':''?>" onclick="showEdit(['<?=$i++?>','<?=$coinRecord['id']?>'],['Currency','<?=$coinRecord['Currency']?>','short'],['Amount','<?=$coinRecord['Amount']?>','short'],['Denomination','<?=$coinRecord['Denomination']?>','short'])">
                <p><?=$coinRecord['Currency']?> <?=$coinRecord['Amount']?> <?=$coinRecord['Denomination']?></p>
            </section>
            <section class="cell <?=($highlightedCell==$i)?'highlighted':''?>" onclick="showEdit(['<?=$i++?>','<?=$coinRecord['id']?>'],['Colour','<?=$coinRecord['Colour']?>'])">
                <p><?=$coinRecord['Colour']?></p>
            </section>
            <section class="cell <?=($highlightedCell==$i)?'highlighted':''?>" onclick="showEdit(['<?=$i++?>','<?=$coinRecord['id']?>'],['ObverseInscription','<?=$coinRecord['ObverseInscription']?>'],['ReverseInscription','<?=$coinRecord['ReverseInscription']?>'])">
                <p><?=$coinRecord['ObverseInscription']?></p>
                <p><?=$coinRecord['ReverseInscription']?></p>
            </section>
            <section class="cell imageShowcase">
                <a href="javascript:showImage('<?=$coinRecord['ObverseFilePath']?>')"><img src="<?=$coinRecord['ObverseFilePath']?>" alt="Coin Obverse" title="Click to zoom"></img></a>
                <a href="javascript:showImage('<?=$coinRecord['ReverseFilePath']?>')"><img src="<?=$coinRecord['ReverseFilePath']?>" alt="Coin Reverse" title="Click to zoom"></img></a>
            </section>
        </section>
<?
    }
?>
        <form name="pagination2" id="pagination2" class="pagination" action="dbView.php" method="get">
            <fieldset>
                <input type="submit" class="hidden" value="" />
                <input type="submit" onclick="this.form.page.value=<?=($currentPage-1)?>;this.form.highlight.value='0';this.form.submit()" value="Previous" /> 
                Page
                <input type="text" name="page" value="<?=$currentPage?>" placeholder="1" />
                of <?=$pageCount?>
                <input type="submit" onclick="this.form.page.value=<?=($currentPage+1)?>;this.form.highlight.value='0';this.form.submit()" value="Next" />
            </fieldset>
        </form>
        <section id="imagePopup" class="hidden popup">
            <span class="screenDimmer" onclick="hideImage()"></span>
            <h1>Photograph of the Coin</h1>
            <br />
            <img id="imageContainer" src=""></img>
            <br />
            <button onclick="javascript:hideImage()" class="popupCloseButton">Close</button>
        </section>
        <section id="editPopup" class="hidden popup">
            <span class="screenDimmer" onclick="hideEdit(true)"></span>
            <h1>Enter the new value for <span id="editPopupHeading"></span></h1>
            <form name="editForm" id="editForm">
                <input type="hidden" id="coinId" name="coinId" value="" />
                <fieldset id="parameterCollection">
                </fieldset>
                <fieldset>
                    <input type="submit" value="Submit Change" onclick="edit(); return false" />
                </fieldset>
                <fieldset>
                    <input type="button" onclick="javascript:hideEdit(true)" class="popupCloseButton" value="Cancel" />
                </fieldset>
            </form>
        </section>
        <section id="editSuccess" class="hidden popup">
            <span class="screenDimmer"></span>
            <h1>Success!</h1>
            <br />
            <p>The requested change has been saved successfully.</p>
        </section>
        <section id="editFail" class="hidden popup">
            <span class="screenDimmer"></span>
            <h1>Failure</h1>
            <br />
            <p>The changes could not be saved. Please try again later.</p>
        </section>
    </section>
    <script type="text/javascript" src="ajax.js"></script>
    <script type="text/javascript">
        function showImage(url){
            document.getElementById("imageContainer").setAttribute("src",url);
            document.getElementById("imagePopup").setAttribute("class","popup");
        }
        function hideImage(){
            document.getElementById("imagePopup").setAttribute("class","hidden popup");
        }
        function showEdit(identifiers, parameters){ 
            // send parameters as (name, value) pairs as needed
            // optional, send as (name, value, length) pairs where length is "short"
            clearEdit(true); // because people may click on another edit without cancelling the current one
            var parameterNameElement, parameterValueElement;
            var parameterCollection=document.getElementById("parameterCollection");
            document.getElementById("coinId").value=identifiers[1];
            for(i=1;i<arguments.length;i++){
                parameterNameElement=document.createElement("input");
                parameterNameElement.setAttribute("type","hidden");
                parameterNameElement.setAttribute("id","parameterName"+i);
                parameterNameElement.value=arguments[i][0];
                parameterCollection.appendChild(parameterNameElement);
                
                parameterValueElement=document.createElement("input");
                parameterValueElement.setAttribute("type","text");
                parameterValueElement.setAttribute("id","parameterValue"+i);
                parameterValueElement.value=arguments[i][1];
                parameterValueElement.setAttribute("placeholder",arguments[i][0]);
                parameterCollection.appendChild(parameterValueElement);

                if(arguments[i].length=3)
                    parameterValueElement.setAttribute("class",arguments[i][2]);
            }
            document.getElementById("editPopup").setAttribute("class","popup");
            setCellHighlightInfo(identifiers[0]);
        }
        function clearEdit(cancel){
            document.getElementById("coinId").value="";
            document.getElementById("parameterCollection").innerHTML="";
            if(cancel) clearCellHighlightInfo();
        }
        function hideEdit(cancel){
            clearEdit(cancel);
            document.getElementById("editPopup").setAttribute("class","hidden popup");
        }
        function edit(){
            var url="dbFix.php";
            var parameterName, parameterValue;
            var parameterString="id="+document.getElementById("coinId").value;
            parameterCollection=document.getElementById("parameterCollection");
            for(i=1;document.getElementById("parameterValue"+i);i++){
                    parameterName=document.getElementById("parameterName"+i).value; 
                    parameterValue=document.getElementById("parameterValue"+i).value;
                    parameterString+="&"+parameterName;
                    parameterString+="="+escape(parameterValue);
            }
            ajaxRequestEscaped(url,"editStatus",true,parameterString);
        }
        function editStatus(isSuccessful){
            hideEdit(false);
            showResponse(unescape(isSuccessful));
        }
        function showResponse(success){
            if(success){
                document.getElementById("editSuccess").setAttribute("class","popup");
                setTimeout("refresh()",500);
            }else{
                document.getElementById("editFail").setAttribute("class","popup");
                setTimeout("refresh()",500);
            }
        }
        function setCellHighlightInfo(cellIndex){
            document.getElementById("pagination1").highlight.value=""+cellIndex;
        }
        function clearCellHighlightInfo(){
            document.getElementById("pagination1").highlight.value="0";
        }
        function refresh(){
            document.getElementById("pagination1").page.value="<?=$currentPage?>";
            document.getElementById("pagination1").submit();
        }
    </script>
</body>
</html>


