<body>
<div class="container bootstrap snippet col-lg-offset-2">
    <div class="row" id="user-profile">
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="main-box clearfix image-column">
                <div id="profile-image" style="padding: 1em 0;">
                    <img src="<?php if (isset($user)) echo $user['profile_image']?>" alt=""
                         class="profile-img img-rounded img-responsive center-block">
                </div>
                <div class="row text-center"><span class="glyphicon glyphicon-pencil" id="image-icon"></span><strong>Profile Image Area</strong></div>
                <div class="profile-details">
                    <ul class="fa-ul">
                        <li><i class="fa-li fa fa-tasks"></i>Videos: <span>11</span></li>
                        <li><i class="fa-li fa fa-tasks"></i>Videos: <span>11</span></li>
                        <li><i class="fa-li fa fa-tasks"></i>Videos: <span>11</span></li>
                    </ul>
                </div>
                <div class="profile-message-btn center-block text-center" id="upload-image">
                    <?php $attribute = array('method' => 'post', 'role' => 'form', 'id' => 'image-form');
                    echo form_open_multipart("profile/upload_image")?>
                        <div class="form-group">
                            <div class="drop-area">Drop Images Here</div>
                        </div>
                        <div class="form-group">
                            <input type="file" name="files[]" multiple="" id="image">
                        </div>
                        <div class="form-group" style="height: 50px;">
                            <div class="pull-left">
<!--                            Upload the image file here    -->
                                <input type="submit" id="submit-image" value="Upload Image" class="btn btn-success">
                            </div>
                        </div>
                        <div class="inc-info"><?php echo $this->session->flashdata('image-info')?></div>
                    </form>
                </div>
                <div class="profile-message-btn center-block text-center">
                    <?php $attribute = array('method' => 'post', 'role' => 'form', 'id' => 'video-form');
                    echo form_open_multipart("profile/upload_video")?>
                        <div class="form-group">
                            <input type="file" name="videos[]" multiple="" id="video">
                        </div>
                        <div class="form-group" style="height: 50px;">
                            <div class="pull-left">
                                <input type="submit" id="submit-video" value="Upload Video" class="btn btn-success">
                            </div>
                        </div>
                    <div class="inc-info row"><?php echo $this->session->flashdata('video-info')?></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-lg-offset-3 col-md-7 col-sm-7">
            <div class="main-box clearfix">
                <div class="profile-header">
                    <div id="user-info"><h3><span><a>User Info</a></span></h3></div>
                    <a href="javascript:" class="btn btn-primary edit-profile" id="edit-profile">
                        <i class="fa fa-pencil-square fa-lg"></i> Edit profile
                    </a>
                </div>
                <div id="profile-user-info">
                    <ul class="list-group">
                        <div class="subheader text-left"><h4><span>General Info</span></h4></div>
                        <br>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Username</strong></span><?php if (isset($user) )echo $user['username'];?>
                        </li>
                        <li class="list-group-item text-right row">
                            <span class="pull-left"><strong>Email</strong></span><?php if (isset($user) )echo $user['email'];?>
                        </li>
                        <br>
                        <div class="subheader text-left"><h4><span>Elective Info</span></h4></div>
                        <br>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Contact Name</strong></span><?php if (isset($user) )echo $user['contactname'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Birthday</strong></span><?php if (isset($user) )echo $user['birthday'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Gender</strong></span><?php if (isset($user) )echo $user['gender'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Address</strong></span><?php if (isset($user) )echo $user['address'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Mobile Phone</strong></span><?php if (isset($user) )echo $user['phone'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>Website</strong></span><?php if (isset($user) )echo $user['website'];?>
                        </li>
                        <li class="list-group-item text-right row"><span class="pull-left">
                                <strong>State</strong></span><?php if (isset($user) )echo $user['state'];?>
                        </li>
                    </ul>
                </div>
                <?php $attributes = array(
                    "class" => "form-horizontal",
                    "role" => "form", "id" => "edit-panel", "method" => "post");
                    echo form_open("users/update_profile", $attributes)?>
                <!--Update the user profile-->
                    <fieldset>
                        <legend>Complete Your Profile</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="contactname">Contact Name</label>
                            <div class="col-md-7">
                                <input class="form-control" id="contactname" name="contactname" type="text" maxlength="20"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="birthday">Birthday</label>
                            <div class="col-md-7">
                                <input class="form-control" id="birthday" name="birthday" type="date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="gender">Gender</label>
                            <div class="col-md-7">
                                <div class="radio-inline">
                                    <input type="radio" name="gender" id="sex-man" value="man" checked/>Man
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" name="gender" id="sex-woman" value="woman"/>Woman
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="address">Address</label>
                            <div class="col-md-7">
                                <input class="form-control" id="address" name="address" type="text" maxlength="40"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="phone">Mobile Phone</label>
                            <div class="col-md-7">
                                <input class="form-control" id="phone" name="phone" type="text" maxlength="20"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="website">Website</label>
                            <div class="col-md-7">
                                <input class="form-control" id="website" name="website" type="text" maxlength="30"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="state">State</label>
                            <div class="col-md-7">
                                <select id="state" name="state" class="form-control">
                                    <option value="QLD">QLD</option>
                                    <option value="NWS">NWS</option>
                                    <option value="VIC">VIC</option>
                                    <option value="TAS">TAS</option>
                                    <option value="WA">WA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <input class="btn btn-success" type="submit" value="sumbit" />
                                <input class="btn btn-primary" id="reset" value="reset" type="reset"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div class="tab-wrapper profile-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-videos" data-toggle="tab">Videos</a></li>
                        <li><a href="#tab-images" data-toggle="tab">images</a></li>
                        <li><a href="#tab-events" data-toggle="tab">Events</a></li>
                        <li><a href="#tab-favorite" data-toggle="tab">Favorite</a></li>
                    </ul>
                    <div class="tab-content">
                        <br>
                        <div class="tab-pane fade  in active" id="tab-videos">
                            <?php if(! empty($videos)) foreach ($videos as $video):?>
                                <div class="col-sm-6 col-md-4">
                                    <div class="upload-videos">
                                        <div>
                                            <img src="<?php echo base_url(); echo $video['thumbnail'];?>"
                                                 class="img-responsive img-rounded video-thumbnail-profile"
                                                 id="<?php echo $video['filename'];?>">
                                        </div>
                                    </div>
                                    <div class="text-muted video-title-profile">
                                        <span class="glyphicon glyphicon-facetime-video"></span>
                                        <?php echo $video['filename'];?>
                                    </div>
                                    <div class="text-muted video-user-profile">
                                        <span class="glyphicon glyphicon-user"></span>
                                        <?php echo $video['username'];?></div>
                                    <div class="text-muted video-date-profile">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        <?php echo $video['date'];?>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="tab-pane fade" id="tab-images">
                            <div style="color: red;"><h4><strong><span class="glyphicon glyphicon-move"></span>
                                        Drag change head portrait</strong></h4>
                            </div>
                            <?php if(! empty($images)) foreach ($images as $image):?>
                                <div class="col-sm-6 col-md-4">
                                    <div class="upload-images">
                                        <img src="<?php echo base_url(); echo $image['url'];?>"
                                             class="img-responsive img-rounded upload-image"
                                             id="<?php echo $image['url'];?>" alt=""
                                             draggable="true" ondragstart="drag(event)">
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="tab-pane fade" id="tab-events" style="background-color: white; padding: 3em 2em;">
                            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Add Calendar Event</h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php echo form_open(site_url("profile/add_event"), array("class" => "form-horizontal", "id"=>"calendar-form")) ?>
                                            <div class="form-group">
                                                <label for="p-in" class="col-md-4 label-heading">Event Name</label>
                                                <div class="col-md-8 ui-front">
                                                    <input type="text" class="form-control" name="name" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="p-in" class="col-md-4 label-heading">Description</label>
                                                <div class="col-md-8 ui-front">
                                                    <input type="text" class="form-control" name="description">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="p-in" class="col-md-4 label-heading">Start Date</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="start_date" placeholder="Y/m/d H:i">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="p-in" class="col-md-4 label-heading">End Date</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="end_date" placeholder="Y/m/d H:i">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="submit" class="btn btn-primary" value="Add Event">
                                            <?php echo form_close() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_open(site_url("profile/generate_pdf")) ?>
                            <input type="submit" class="btn btn-primary" value="Generate Event PDF" style="margin-bottom: 3em;">
                            </form>
                            <div id="calendar">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-favorite">ccc</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 main-box clearfix image-column" id="processing">
            <div style="color: red;"><h4><strong></span>
                        Click target image before processing</strong></h4>
                <h4><strong>Filename should contain suffix</strong></h4>
            </div>
            <div id="processed-image" style="padding: 1em 0;">
            </div>
            <div class="tab-wrapper profile-tabs">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-rotate" data-toggle="tab">Rotate</a></li>
                    <li><a href="#tab-resize" data-toggle="tab">Resize</a></li>
                    <li><a href="#tab-watermark" data-toggle="tab">Watermark</a></li>
                </ul>
                <div class="tab-content">
                    <br>
                    <div class="tab-pane fade  in active" id="tab-rotate">
                        <?php $attribute = array('method' => 'post', 'role' => 'form', 'id' => 'rotate-form',
                            'class'=>"form-horizontal");
                        echo form_open_multipart("profile/rotate_image")?>
                            <div class="form-group">
                                <div>
                                    <label for="rotate-degree">rotate degree</label>
                                </div>
                                <input type="text" name="rotate-degree" id="rotate-degree">
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="filename">file name</label>
                                </div>
                                <input type="text" name="filename" id="filename">
                            </div>
                            <div class="form-group" style="height: 50px;">
                                <div class="pull-left">
                                    <!--                            Upload the image file here    -->
                                    <input type="submit" id="rotate-image" value="Rotate Image" class="btn btn-success">
                                    <input type="hidden" name="rotate-src" class="src" value="0" />
                                </div>
                            </div>
                            <div class="inc-info"><?php echo $this->session->flashdata('processing-info')?></div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tab-resize">
                        <?php $attribute = array('method' => 'post', 'role' => 'form', 'id' => 'resize-form');
                        echo form_open_multipart("profile/resize_image")?>
                        <div class="form-group">
                            <div>
                                <label for="width">width</label>
                            </div>
                            <input type="text" name="width" id="width">
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="height">height</label>
                            </div>
                            <input type="text" name="height" id="height">
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="filename">filename</label>
                            </div>
                            <input type="text" name="filename" id="filename">
                        </div>
                        <div class="form-group" style="height: 50px;">
                            <div class="pull-left">
                                <!--                            Upload the image file here    -->
                                <input type="submit" id="rotate-image" value="Resize Image" class="btn btn-success">
                                <input type="hidden" name="resize-src" class="src" value="0" />
                            </div>
                        </div>
                        <div class="inc-info"><?php echo $this->session->flashdata('processing-info')?></div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tab-watermark">
                        <?php $attribute = array('method' => 'post', 'role' => 'form', 'id' => 'watermark-form');
                        echo form_open_multipart("profile/add_watermark")?>
                            <div class="form-group">
                                <div>
                                    <label for="text">text</label>
                                </div>
                                <input type="text" name="text" id="text">
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="filename">filename</label>
                                </div>
                                <input type="text" name="filename" id="filename">
                            </div>
                            <div class="form-group" style="height: 50px;">
                                <div class="pull-left">
                                    <!--                            Upload the image file here    -->
                                    <input type="submit" id="rotate-image" value="Watermark Image" class="btn btn-success">
                                    <input type="hidden" name="watermark-src" class="src" value="0" />
                                </div>
                            </div>
                            <div class="inc-info"><?php echo $this->session->flashdata('processing-info')?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        var date_last_clicked = null;

        $('#calendar').fullCalendar({
            eventSources: [
                {
                    events: function(start, end, timezone, callback) {
                        $.ajax({
                            url: '<?php echo base_url() ?>profile/get_events',
                            dataType: 'json',
                            data: {
                                start: start.unix(),
                                end: end.unix()
                            },
                            success: function(msg) {
                                var events = msg.events;
                                callback(events);
                            }
                        });
                    }
                },
            ],
            dayClick: function(date, jsEvent, view) {
                date_last_clicked = $(this);
                $(this).css('background-color', '#bed7f3');
                $('#addModal').modal();
            },
        });
    });

    $(document).on('click', '.upload-image', function(e){
        var id = $(this).attr("id");
        document.getElementById("processed-image").innerHTML = "";
        var nodeCopy = document.getElementById(id).cloneNode(true);
        nodeCopy.id = 'newId';
        document.getElementById("processed-image").append(nodeCopy);
        $('.src').val(id);
        console.log(id);
        $('#processed-image').focus();
    });

    // Drag and drop image for uploading
    $(function() {
        var obj = $('.drop-area');
        var upload = function(files) {
            var formData = new FormData();
            var xhr = new XMLHttpRequest();
            var x;
            for(x = 0; x < files.length; x = x + 1) {
                formData.append("file[]", files[x]);
            }
            xhr.onload = function() {
                var data = this.responseText;
                console.log(data);
            };
            xhr.open("post", "<?php echo base_url()?>profile/drag_image");
            xhr.send(formData);
        };
        obj.on("dragover", function(e) {
           e.stopPropagation();
           e.preventDefault();
           $(this).css("border", "2px solid #16a085");
        });
        obj.on("drop", function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).css("border", "2px dotted #3498db");
            upload(e.originalEvent.dataTransfer.files);
        });
    });

    // Set profile image by dragging and dropping picture
    $(function() {
        var obj = $("#profile-image");
        obj.on("dragover", function(e) {
            e.stopPropagation();
            e.preventDefault();
        });
        obj.on("drop", function(e) {
            e.stopPropagation();
            e.preventDefault();
            var data = e.originalEvent.dataTransfer.getData("image");
            var src = document.getElementById(data).src;
            document.getElementById("profile-image").innerHTML = "";
            var nodeCopy = document.getElementById(data).cloneNode(true);
            nodeCopy.id = 'newId';
            e.currentTarget.append(nodeCopy);
            $.ajax({
                url: "<?php echo base_url();?>users/set_profile_image",
                method : "POST",
                data: {src: src},
                success: function(res) {
                    console.log('Update Success');
                }
            })
        });
    });

    function drag(e) {
        e.dataTransfer.setData("image", e.target.id);
    }

    $("#user-info").click(function() {
        $("#profile-user-info").css("display", "block");
        $("#edit-panel").css("display", "none");
    });

    $("#edit-profile").click(function() {
        $("#profile-user-info").css("display", "none");
        $("#edit-panel").css("display", "block");
    });

    // Record the scroll position by localStorage in js
    $(window).scroll(function() {
        var position = $(window).scrollTop();
        $.ajax({
            url: "<?php echo base_url();?>users/set_page_position",
            method : "POST",
            data: {position: position},
            success: function(res) {
            }
        })
    });
    var check = parseInt("<?php echo $this->input->cookie('profile_position');?>");
    if (!isNaN(check)) {
        window.scrollTo(0, check);
    }
</script>
</body>

