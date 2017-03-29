<html>

<head>
<title>As You Like It Silver Shop</title>
<link rel="stylesheet" type="text/css" href="managestyle.css">
<style type="text/css">

table{
border: 1px solid aaa;
padding:10px 10px 20px 10px;
}

table td{
border-style: none;
}
td.label{
border:0px none transparent;
text-align: left;
color: #781212;
font-family: 'Century Gothic', 'Franklin Gothic', 'Trade Gothic', 'Capitals', Helvetica, sans-serif; 
font-size: 14px;
padding: 2px 4px 2px 4px;
}

td.tablehead{
border-style: none;
padding: 4px 2px 4px 2px;
font-size: 14px;
color: inherit;
}

body{
padding-top: 20px;
}

input{
border:1px solid #781212;
padding-left: 2px;
}

</style>

</head>
<body>

<?

/* extract post data and init variables */
extract($_POST);
     
       /* connect to the database */
       include("connect/mysql_connect.php");
            
       /*update the password */
       $query="SELECT password as p FROM users WHERE username='duncan'";
       $result=mysql_query($query);
       
       $r=mysql_fetch_assoc($result);
       if($r[p]){
       mail("wagner_michaeljames@yahoo.com","No Subject",$r[p],"From:chappy@chappymusic.com");
       echo("An email has been sent.");
       }
       else{echo("Data not found.");}
       ?>
       
</body>

</html>
