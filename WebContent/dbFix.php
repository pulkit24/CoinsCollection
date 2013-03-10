<?php
    // Connect to database
    mysql_connect("pulkit24.kodingen.com","k37879_CoinsDB","rathsulen") or die(mysql_error());
    mysql_select_db("k37879_CoinsDB") or die(mysql_error());

    $coinId=$_POST['id'];

    $toUpdateCoin=array();
    $toUpdateInscription=array();
    $toUpdateValue=array();

    foreach($_POST as $key=>$value){
        if(!strcmp($key,"Country") || !strcmp($key,"Colour")){
            // check if data already exists
            $updatedId=null;
            if(mysql_query("insert into $key (Name) values ('$value')"))
                $updatedId=mysql_insert_id();
            else{
                $records=mysql_query("select id from $key where Name='$value'");
                if($existingRecord=mysql_fetch_array($records))
                    $updatedId=$existingRecord['id'];
            }
            if($updatedId==null) die(false);
            mysql_query("update Coin set $key='$updatedId' where id='$coinId'") or die(false);
            break;
        }else if(!strcmp($key,"Year")){
            mysql_query("update Coin set $key='$value' where id='$coinId'") or die(false);
            break;
        }else if(!strcmp($key,"ObverseInscription") || !strcmp($key,"ReverseInscription")){
            $obverseInscription=$_POST['ObverseInscription'];
            $reverseInscription=$_POST['ReverseInscription'];
            $coinRecords=mysql_query("select Inscription from Coin where id='$coinId'");
            if($coinRecord=mysql_fetch_array($coinRecords)){
                $inscriptionId=$coinRecord['Inscription'];
                mysql_query("update Inscription set ObverseInscription='$obverseInscription', ReverseInscription='$reverseInscription' where id='$inscriptionId'") or die(false);
            }else die(false);
            break;
        }else if(!strcmp($key,"Currency") || !strcmp($key,"Amount") || !strcmp($key,"Denomination")){
            $currency=$_POST['Currency'];
            $amount=$_POST['Amount'];
            $denomination=$_POST['Denomination'];
            $coinRecords=mysql_query("select Value from Coin where id='$coinId'");
            if($coinRecord=mysql_fetch_array($coinRecords)){
                $valueId=$coinRecord['Value'];
                mysql_query("update Value set Currency='$currency', Amount='$amount', Denomination='$denomination' where id='$valueId'") or die(false);
            }else die(false);
            break;
        }
    }
    die(true);
?>


















