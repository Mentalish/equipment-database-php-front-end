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
                    <a href="#" class="navbar-brand">View Equipment</a>
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
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                  <?php
                  include("functions.php");
                  if(!isset($_GET['edit_mode']) || $_GET['edit_mode'] == 'false')
                  {
                     $dblink=db_connect("equipment");
                        $sql = 'SELECT
                        d.device_id, 
                        m.manufacturer_name, 
                        dt.device_type_name, 
                        d.serial_number_prefix, 
                        d.serial_number_body,
                        s.status_name
                        FROM devices AS d';

                        $sql .= ' JOIN manufacturers AS m ON d.manufacturer_id = m.manufacturer_id
                        JOIN device_types AS dt ON d.device_type_id = dt.device_type_id
                        JOIN status AS s ON d.status_id = s.status_id
                        WHERE d.device_id=' . $_GET['item_id'];
                        $result=$dblink->query($sql) or
                           die("<p>Something went wrong with $sql<br>".$dblink->error);
                        $data=$result->fetch_array(MYSQLI_ASSOC);
                        echo '<h4>Device Type:</h4>
                              <p>' . $data['device_type_name'] . '</p>
                              <h4>Manufacturer:</h4>
                              <p>' . $data['manufacturer_name'] . '</p>
                              <h4>Status:</h4>
                              <p>' . $data['status_name'] . '</p>
                              <h4>Status:</h4>
                              <p>' . $data['serial_number_prefix'] . '-' . $data['serial_number_body'] . '</p>';
                        echo '<div class = col>
                           <form method="post" action="">
                              <button type="submit" class="btn btn-primary" name="modify" value="Search">Modify</button>
                              <button type="submit" class="btn btn-danger" name="delete" value="Search">Delete</button>
                           </form>
                        </div>';

                  }else if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 'true') {
                        $dblink=db_connect("equipment");
                        $sql="Select `device_type_name`,`device_type_id` from `device_types` where `device_types`.`status_id` = '1'";
                        $result=$dblink->query($sql) or
                            die("<p>Something went wrong with $sql<br>".$dblink->error);
                        $devices=array();
                        $manufacturers=array();
                        $statuses=array();
                        while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                           $devices[$data['device_type_id']]=$data['device_type_name'];
                        }
                        $sql="Select `manufacturer_name`,`manufacturer_id` from `manufacturers` where `manufacturers`.`status_id`='1'";
                        $result=$dblink->query($sql) or 
                           die("<p>Something went wrong with $sql<br>".$dblink->error);
                        while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                           $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                        }
                     $sql="Select `status_name`,`status_id` from `status`";
                     $result=$dblink->query($sql) or 
                        die("<p>Something went wrong with $sql<br>".$dblink->error);
                     while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                        $statuses[$data['status_id']]=$data['status_name'];
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
                        <label for="exampleStatus">Status:</label>
                        <select class="form-control" name="status">
                            <?php
                                   foreach($statuses as $key=>$value)
                                       echo '<option value="'.$key.'">'.$value.'</option>';
                               ?>
                       </select>
                   </div>
                   <div class="form-group">
                        <label for="exampleSerial">Serial Number:</label>
                        <input type="text" class="form-control" id="serialInput" name="serialnumber">
                   </div>
                        <button type="submit" class="btn btn-success" name="search" value="Search">Save</button>
                        <button type="submit" class="btn btn-primary" name="view" value="Search">View</button>
               </form>
               <?php  }?>
            </div>
          </div>
      </section>
</body>
</html>
<?php
    if (isset($_POST['modify']))
    {
        redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=true");
    }
    if (isset($_POST['view']))
    {
        redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=false");
    }
?>
