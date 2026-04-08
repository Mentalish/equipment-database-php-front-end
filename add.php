<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Advanced Software Engineering</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/templatemo-style.css">
</head>
<body>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
     <!-- MENU -->
     <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
          <div class="container">
               <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    <a href="#" class="navbar-brand">Add New Equipment</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                         <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                         <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                         <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>
                         <li><a href="modify-device-type.php" class="smoothScroll">Modify Device Type</a></li>
                         <li><a href="modify-manufacturer.php" class="smoothScroll">Modify Manufacturer</a></li>
                    </ul>
               </div>
          </div>
     </section>
 <!-- HOME -->
     <section id="home">
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                   <?php 
                        include("functions.php");
                        $dblink=db_connect("equipment");
                        $sql="Select `device_type_name`,`device_type_id` from `device_types` where `device_types`.`status_id` = '1'";
                        $result=$dblink->query($sql) or
                            die("<p>Something went wrong with $sql<br>".$dblink->error);
                        $devices=array();
                        $manufacturers=array();
                        while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                           $devices[$data['device_type_id']]=$data['device_type_name'];
                        }
                        $sql="Select `manufacturer_name`,`manufacturer_id` from `manufacturers` where `manufacturers`.`status_id`='1'";
                        $result=$dblink->query($sql) or 
                           die("<p>Something went wrong with $sql<br>".$dblink->error);
                        while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                           $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                        }
                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="DeviceExists")
                        {
                            echo '<div class="alert alert-danger" role="alert">Serial Number already exists in database!</div>';
                        }
                     ?>
                    <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Device:</label>
                        <select class="form-control" name="device">
                            <?php
                                foreach($devices as $key=>$value)
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleManufacturer">Manufacturer:</label>
                        <select class="form-control" name="manufacturer">
                            <?php
                                foreach($manufacturers as $key=>$value)
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSerial">Serial Number:</label>
                        <input type="text" class="form-control" id="serialInput" name="serialnumber">
                    </div>
                        <button type="submit" class="btn btn-primary" name="submit" value="submit">Add Equipment</button>
                   </form>
               </div>
          </div>
      </section>
</body>
</html>
<?php
    if (isset($_POST['submit']))
    {
        $device=$_POST['device'];
        $manufacturer=$_POST['manufacturer'];
        $serialNumber=trim($_POST['serialnumber']);

        validateSerialNumber($prefix, $body, $serialNumber);

        $sql="Select `device_id` from `devices` where `serial_number_body`='$body' and `serial_number_prefix`='$prefix'";
        $rst=$dblink->query($sql) or
             die("<p>Something went wrong with $sql<br>".$dblink->error);
        if ($rst->num_rows<=0)//sn not previously found
        {
            $sql="Insert into `devices` (`device_type_id`,`manufacturer_id`, `serial_number_prefix`, `serial_number_body`) values ('$device','$manufacturer','$prefix','$body')";
            $dblink->query($sql) or
                 die("<p>Something went wrong with $sql<br>".$dblink->error);
            redirect("index.php?msg=EquipmentAdded");
        }
        else
            redirect("add.php?msg=DeviceExists");
    }
?>
