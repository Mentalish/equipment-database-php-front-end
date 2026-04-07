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

   function partSerialNumber(string $fullSerialNumber, &$prefix, &$delimeter, &$body) : void {
      if($fullSerialNumber == null) {
         return;
      }

      $prefix = substr($fullSerialNumber, 0,2);
      $delimeter = $fullSerialNumber[2];
      $body = substr($fullSerialNumber, 3);
   }

   function validateSerialNumber(&$prefix, &$body, $serialNumber) : bool {
         $delimeter = "";
         partSerialNumber($serialNumber, $prefix, $delimeter, $body);

         //incorect size
         if(strlen($body) != 64) {
            return true;
         }

         //incorect delimiter
         if($delimeter != '-') {
            return true;
         }

         return false;
   }
?>
