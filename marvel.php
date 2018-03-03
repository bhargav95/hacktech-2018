<?php
    
    $site="http://gateway.marvel.com/v1/public/characters/1009189/comics";
    $public_key="6d9ab1cb36c79afd6168a751b4195251";
    $private_key="7c3998174e56ace40681561b366adf297c73931c";

    $time_stamp="12";

    $hash="c8abca43b7fdefb0a37974a45fd17d6a";

    $res = file_get_contents($site."?orderBy=-focDate&ts=12&apikey=".$public_key."&hash=".$hash);
    
    
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            
            body{
                background-color: red;
            }
            
            h3{
                color: white;
            }
            
            #chatwindow{
                height: 500px;
                width: 300px;
                vertical-align: bottom;
                overflow-y: scroll;
                display: table-cell;
                background-color: white;
            }
            
            .query{
                width: 300px;
                text-align: right;
            }
            
            .query span{
                background-color: skyblue;
                padding: 10px;
                border-radius: 10px 10px 0px 10px;
            }
            
            
            .answer span{
                background-color: #eee;
                padding: 10px;
                border-radius: 10px 10px 10px 0px;
            }
            
            .query, .answer{
                padding: 10px;
            }
            
            
            #mainwindow{
                margin: 0 auto;
                
                width: 300px;
            }
                        
            
        </style>
        
        
        
        <script>
            
            function getres(){
                var t= <?php echo $res; ?> ;
                
                t=t.data.results;
                
                tab='<table class="table" border=1><tr>';
                
                for(var i=0;i<t.length;++i){
                    
                    tab+='<td><img width=200 src="'+t[i].thumbnail.path+'.'+t[i].thumbnail.extension+'">';
                    tab+='<br>'+t[i].title;
                    
                }
                
                
                console.log(t);
                                
                document.getElementById("ress").innerHTML=tab;    
            }
            
            function loader(){
                
                document.getElementById('chat').onkeydown = function(event) {
                    if (event.keyCode == 13) {
                        reply();
                        
                        document.getElementById('chat').value="";
                    }
                }
            }
            
            function reply(query=document.getElementById("chat").value){
                microsoft="https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/79df3317-b46d-4ab6-9ae2-4448c73abab8?subscription-key=47f227a5156145c0ab9c8b44e5600969&verbose=true&timezoneOffset=0&q=";
                
                console.log(query);
                
                if(!query){
                    alert("Enter a Message");
                    return;
                }
                
                
                var chat=document.getElementById("chatwindow");
                
                chat.innerHTML += '<div class="query"><span>'+query+'</span></div>';
                
                
                var api = microsoft + encodeURIComponent(query);
                
                var oReq = new XMLHttpRequest();
                oReq.open("GET", api,false);
                oReq.send();
                
                var answer=oReq.responseText;
                
                var topIntent=JSON.parse(oReq.responseText).topScoringIntent.intent;
                
                var answerfrommicrosoft = eval(topIntent+'('+answer+')');
                
                var answer=JSON.parse(oReq.responseText).topScoringIntent.intent;
                
                chat.innerHTML += '<div class="answer"><span>'+answerfrommicrosoft+'</span></div>';
                //chat.innerHTML += '<div class="answer"><span>'+answer+'</span></div>';
            
            }
            
            function GiveJoke(ans=""){
                
                items=['What is a superhero\'s favorite part of the joke? The "punch" line!','Where\'s Spiderman\'s home page? On the world wide web.','What is Thor\'s favorite food? Thor-tillas','What did Bruce Banner say to Spider Man? "Don\'t bug me."','What is it called when Iron Man does a cart wheel? A Ferrous Wheel!']
                
                res="Here's a joke: "+items[Math.floor(Math.random()*items.length)];
                
                res+='<br><input type="button" name="another_joke" value="Another Joke" onclick=\'reply("joke")\'>';
                
                return res;
            }
            
            function AreYouFriendsWith(ans){
                
                friend=ans.entities[0].entity;
                
                if(friend=="Hulk"){
                    return "Yes! I'm friends with the Hulk";    
                }
                else{
                    return ""
                }
                
                
            }
            
            function GetComics(ans){
                getres();   
                
                return "Below are some of the comics I appear in this month!";
            }
            
            function Greeting(ans){
                return "Hey there! What is your name?";
            }
            
            function GetName(ans){
                return "Hello "+ans.entities[0].entity;
            }
            
            function clearchat(){
                document.getElementById("chatwindow").innerHTML="";
            }
            
            
            
        </script>
    </head>
    <body onload="loader()">
        
        
        <div id="mainwindow">
            
            <img width=300px src="https://upload.wikimedia.org/wikipedia/commons/0/04/MarvelLogo.svg">
            
            <h3>Chat With Black Widow!</h3>

            <div id="chatwindow">

            </div>

            
            
            <div class="input-group" style="padding-top:5px">
                <input class="form-control" type="text" name="chat" id="chat" placeholder="Type your message here!">

                <input type="button" class="btn btn-secondary" name="send" id="send" value=">" onclick="reply()">
                
            </div>    
            <input style="text-align:right" type="button" class="btn btn-secondary" name="clear" id="clear" value="Clear Chat" onclick="clearchat()">

        </div>
        
        
        <div id="ress"></div>
    </body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
</html>