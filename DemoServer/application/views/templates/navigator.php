<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Form</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style.css">
        <link href="https://vjs.zencdn.net/7.7.6/video-js.css" rel="stylesheet" />
        <script src="<?php echo base_url();?>assets/dist/js/jquery-3.3.1.min.js"></script>
        <script src="<?php echo base_url();?>assets/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/dist/js/script.js"></script>
        <script src="<?php echo base_url();?>assets/dist/js/jquery.validate.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
        <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
        <script src='<?php echo base_url();?>scripts/fullcalendar-3.1.0/lib/moment.min.js'></script>
        <script src='<?php echo base_url();?>scripts/fullcalendar-3.1.0/fullcalendar.min.js'></script>
        <link rel="stylesheet" href='<?php echo base_url();?>scripts/fullcalendar-3.1.0/fullcalendar.min.css'>
    </head>
    <body>
    <nav class="navbar navbar-inverse navbar-fixed-top my-navbar" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#example-navbar-collapse">
                    <span class="sr-only">Switch Navbar</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php base_url()?>/Demo/users/index">Online Video Platform</a>
            </div>
            <div class="collapse navbar-collapse" id="example-navbar-collapse" style="text-align: center;">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php base_url()?>/Demo/users/index">Home</a></li>
                </ul>
                <ul class="nav navbar-nav" id="search">
                    <form method="post" action="<?php echo base_url()?>search/search_video" class="navbar-form">
                        <input class="form-control" type="text" id="searchbar" placeholder="Search"
                               aria-label="Search" name="search" style="width: 80%">
                        <button class="btn btn-default btn" type="submit">
                            <span class="glyphicon glyphicon-search" id="searchbtn"></span>
                        </button>
                    </form>
                </ul>
                <ul class="nav navbar-nav navbar-right" id="login-info">
                    <?php if(!$this->session->userdata('logged')) : ?>
                        <li class="login-label">
                            <a href="<?php echo base_url(); ?>users/login"> Log In </a>
                        </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata('logged')) : //If the status is logged, the profile link is accessible?>
                        <li class="login-label">
                            <a href="<?php echo base_url(); ?>profile">Hi <?php echo $this->session->userdata('username')?></a>
                        </li>
                        <li class="dropdown">
                            <a class="drop-toggle" data-toggle="dropdown">Current Location<b class="caret bottom-up"></b></a>
                            <ul class="dropdown-menu" id="map-container" style="background-color: #000">
                                <li><div id="user-location" class=""></div></li>
                                <li><div id="user-map"></div></li>
                            </ul>
                        </li>
                        <li class="login-label">
                            <a href="<?php echo base_url(); ?>users/logout"> Log Out </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
            <?php
            if($this->session->flashdata('message'))
            {
                echo '
                        <div class="alert alert-success" id="message" style="margin-bottom: 0">
                            '.$this->session->flashdata("message").'
                        </div>
                        ';
            }
            ?>
    </nav>
    <script type="text/javascript">
        // Configure the Google Map
        var map, marker;
        function initMap() {
            map = new google.maps.Map(document.getElementById('user-map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 16
            });

            marker = new google.maps.Marker({
                position: {lat: -34.397, lng: 150.644},
                map: map
            });

            // If current location is available, set the map to the current location.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    marker.setPosition(pos);
                    map.setCenter(pos);
                    // Visualize the position info on the navi bar.
                    var locationInfo = $("#user-location");
                    var reg = /\d{1,}.\d{1,5}/;
                    locationInfo.html("latitude: " + reg.exec(position.coords.latitude) +
                        "<br>" + "longitude: " + reg.exec(position.coords.longitude));
                }, function() {
                    handleLocationError(true, marker, map.getCenter());
                });
            } else {
                handleLocationError(false, marker, map.getCenter());
            }
        }

        // Prevent the toggle of dropdown menu with map when clicking
        $(document).on('click', '#map-container', function(e) {
            e.stopPropagation();
        });

        $(document).ready(function() {
            // Autocomplete function
            $("#searchbar").autocomplete({
                source: function (request,response) {
                    $.ajax({
                        url: "<?php echo base_url();?>users/fetch_search_info",
                        method: "POST",
                        data: { search: request.term
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $("#searchbar").val(ui.item.label);
                    return false;
                }
            });
        });
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaRSzAEV3AdABPuZZSiVyqD0BD3pEOLk8&callback=initMap">
    </script>
    </body>
</html>