<?php
include_once 'functions.php';

    $locations = getAllLocations();

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Location</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <script type="text/javascript" src="js/jquery172.js"></script>
        <script type="text/javascript" src="js/maps.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfL4Nemx_Ryt7Qlw_F_V521B1Oeeix7AU&callback=initMap"
        async defer></script>

    </head>
    <body id="index" class="home">
        
        <header id="banner" class="body">
            <nav><ul>
                <li><a href="#">home</a></li>
                <li><a href="#">posts</a></li>
                <li><a href="#">blog</a></li>
                <li><a href="#">contact</a></li>
                <li><a href="#"> <i class="fa fa-cog"></i></a></li>
            </ul></nav>
        </header>
        
        
        
        <div id="map_container">
          <div id="map_canvas"></div>
        </div>

        <p>
            <label for="search_new_places">New Places</label>
            <input type="text" placeholder="Search New Places" id="search_new_places" autofocus/>
        </p>

        <p>
            <label for="search_ex_places">Saved Places</label>
            <input type="text" placeholder="Search Saved Places" id="search_ex_places" list="saved_places">
        </p>

            <input type="hidden" name="place_id" id="place_id"/>

        <p>
            <label for="place">Name</label>
            <input type="text" name="n_place" id="n_place"/>  
        </p>

        <p>
            <label for="description">Description</label>
            <input type="text" name="n_description" id="n_description"/>  
        </p>

        <p> 
            <input type="button" id="btn_save" value="save place"/>
            <input type="button" id="plot_marker" value="plot marker"/>  
        </p>

        <datalist id="saved_places">
            <!--loop through the places saved in the database and store their data into each of the data attribute in the options-->  
            <?php 
                foreach ($locations as $location) {
                    $desc = $location['description'];
                    $lat= $location['lat'];
                    $long= $location['lng'];
                    echo '<option value="' . $desc. '">';
                }            
            ?>  
        </datalist>
    
    </body>
</html>
