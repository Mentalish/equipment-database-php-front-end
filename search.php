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
                    <a href="#" class="navbar-brand">Search Equipment Database</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                         <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                         <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                         <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>
                    </ul>
               </div>
          </div>
     </section>
 <!-- HOME -->
     <section id="home">
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
                     $devices[0]='any';
                     $manufacturers[0]='any';
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
                        <select class="form-control" name="deviceType">
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
                        <button type="submit" class="btn btn-primary" name="search" value="Search">Search</button>
               </form>
               <?php
                if (isset($_POST['search']))
                {
                    $deviceType=$_POST['deviceType'];
                    $manufacturer=$_POST['manufacturer'];
                    $serialNumber=trim($_POST['serialnumber']);
                    if($serialNumber) {
                        validateSerialNumber($prefix, $body, $serialNumber);
                    }
               $hasPreviousWhere = false;
                  
               $sql = 'SELECT 
                   m.manufacturer_name, 
                   dt.device_type_name, 
                   d.serial_number_prefix, 
                   d.serial_number_body 
               FROM devices AS d';
               
                    if($deviceType != 0) {
                       $sql .= 'WHERE d.device_type_id=' . $deviceType;
                       $hasPreviousWhere = true;
                    }

                    if($manufacturer != 0 && $hasPreviousWhere) {
                       $sql .= ' AND d.manufacturer_id=' . $manufacturer;
                    } else if($manufacturer != 0) {
                       $sql .= 'WHERE d.manufacturer_id=' . $manufacturer;
                       $hasPreviousWhere = true;
                    }

                    if($serialNumber && $hasPreviousWhere) {
                       $sql .= ' AND d.serial_number_body=' . $body . ' AND d.serial_number_body=' . $prefix;
                    } else if($serialNumber){
                       $sql .= 'WHERE d.serial_number_body=' . $body . ' AND d.serial_number_body=' . $prefix;
                       $hasPreviousWhere = true;
                    }

               $sql . 'JOIN manufacturers AS m ON d.manufacturer_id = m.manufacturer_id
               JOIN device_types as dt ON d.device_type_id = dt.device_type_id';
                    $result=$dblink->query($sql) or
                         die("<p>Something went wrong with $sql<br>".$dblink->error);
                        echo '<br><table class="table table-bordered">
                        <tr>
                           <td>Manufacturer</td>
                           <td>Device Type</td>
                           <td>Serial Number</td>
                           <td>Status</td>
                        </tr>';

                        while ($data = $result->fetch_assoc()) {
                           echo ' <tr>
                              <td>' . $data['manufacturer_name'] . '</td>
                              <td>' . $data['device_type'] . '</td>
                              <td>' . $data['serial_number_prefix'] . '-' . $data['serial_number_body'] . '</td>
                           </tr>';    
                        }

                     echo '</table>';
                }
               ?>
      </section>
</body>
</html>

