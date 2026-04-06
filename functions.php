<?php
  function redirect($url)
{?>
  <script type="text/javascript">
    document.location.href="<?php echo $url;?>";
  </script>
<?php
  die;
  }
  
  function db_connect($db){    
   $hostname = "localhost";
   $username = "";
   $password = "";
   
   $dblink = new mysqli($hostname, $username, $password, $db);
   
   if (mysqli_connect_error())
   {
     die("<h2>Somethig went wrong with the DB connection".mysqli_connect_error()."</h2>");
   }
   
   return $dblink;
  }
?>
