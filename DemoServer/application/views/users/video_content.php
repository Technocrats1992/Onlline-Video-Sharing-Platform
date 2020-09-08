<body>
    <div class="agileits-main container">
        <div class="wthree-row w3-agile">
            <div id="videoContainer">
                <video
                        id="my-video"
                        class="video-js"
                        controls
                        preload="auto"
                        data-setup="{}"
                        style="width: 100%; max-height: 100%;"
                >
                    <source src="<?php if (isset($video)) echo base_url().$video['url'] ?>" />
                    <!--                <source src="MY_VIDEO.webm" type="video/webm" />-->
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank"
                        >supports HTML5 video</a
                        >
                    </p>
                </video>
            </div>
            <script src="https://vjs.zencdn.net/7.7.6/video.js"></script>
            <div class="row">
                <div class="text-muted video-title-content col-lg-10">
                    <span class="glyphicon glyphicon-facetime-video"></span>
                    <?php echo $video['filename'];?>
                </div>
                <div class="col-lg-2" id="rating-container">
                    <span class="glyphicon glyphicon-thumbs-up" id="thumbs_up"></span>&nbsp;
                    <span class="counter" id="likes"><?php if (isset($video)) echo $video['likes'];?></span>&nbsp;&nbsp;&nbsp;
                    <span class="glyphicon glyphicon-thumbs-down" id="thumbs_down"></span>&nbsp;
                    <span class="counter" id="dislikes"><?php if (isset($video)) echo $video['dislikes'];?></span>
                </div>
            </div>
            <div class="text-muted video-user-content">
                <span class="glyphicon glyphicon-user"></span>
                <?php echo $video['username'];?></div>
            <div class="text-muted video-date-content">
                <span class="glyphicon glyphicon-calendar"></span>
                <?php echo $video['date'];?>
                <div class="pull-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#download-Modal" id="download-toggle">Download</button>
                    <div class="modal fade" id="download-Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width: 20%">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">
                                        <strong>Download Video</strong>
                                    </h4>
                                </div>
                                <?php echo form_open(site_url("videoContent/download_video")) ?>
                                    <div class="modal-body">
                                        <input type="hidden" name="url" id="url" value="<?php echo $video['url']?>" />
                                        <div class="form-group">
                                            <div style="padding: 1.5em 0 1.8em 0">
                                                <label class="col-lg-3" for="filename">Filename</label>
                                                <input class="col-lg-8" type="text" name="filename" id="filename" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Back
                                        </button>
                                        <input type="submit" class="btn btn-primary pull-right" onclick="$('#download-Modal').modal('hide');" id="download" value="Download Video">
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal -->
                    </div>
                </div>
            </div>
            <input type="hidden" id="status" value="<?php if (isset($status)) echo $status; ?>">
        </div>
        <div>
            <form method="POST" id="comment_form">
                <div class="form-group">
                    <label for="comment" class="pull-left">Comment :</label>
                    <textarea class="form-control" name="comment_body" id='comment' placeholder="Enter Comment" rows="6" required></textarea>
                </div>
                <div id='submit_button'>
                    <input type="hidden" name="comment_id" id="comment_id" value="0" />
                    <input class="btn btn-success" type="submit" name="submit" value="add comment" >
                </div>
            </form>
        </div>
        <div id="comment-container">
        </div>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function(){

        if ($("#status").val() == 2) {
            $("#thumbs_up").css("color", "#008000");
        } else if ($("#status").val() == 1) {
            $("#thumbs_down").css("color", "#E10000");
        }

        // Pass the rating input to the controller by Ajax
        $("#thumbs_up").on("click", function() {
            var videoId = "<?php echo $video['id']?>";
            var username = "<?php echo $this->session->userdata('username')?>";
            $.ajax({
                url: "<?php echo base_url()?>videoContent/set_rating",
                method: "POST",
                cache: false,
                data:{videoId: videoId, username: username, type: 1},
                success: function(data) {
                    var data = JSON.parse(data);
                    if (data['status'] === 0) {
                        alert("Login to thumbs-up");
                    } else {
                        $("#likes").html(data['likes']);
                        $("#dislikes").html(data['dislikes']);
                        if (data['status'] === 2) {
                            $("#thumbs_up").css("color", "#008000");
                        } else {
                            $("#thumbs_up").css("color", "#000000");
                        }
                        $("#thumbs_down").css("color", "#000000");
                    }
                }
            })
        });

        $("#thumbs_down").on("click", function() {
            var videoId = "<?php echo $video['id']?>";
            var username = "<?php echo $this->session->userdata('username')?>";
            $.ajax({
                url: "<?php echo base_url()?>videoContent/set_rating",
                method: "POST",
                cache: false,
                data:{videoId: videoId, username: username, type: 0},
                success: function(data) {
                    var data = JSON.parse(data);
                    if (data['status'] === 0) {
                        alert("Login to thumbs-down");
                    } else {
                        $("#likes").html(data['likes']);
                        $("#dislikes").html(data['dislikes']);
                        $("#thumbs_up").css("color", "#000000");
                        if (data['status'] === 1) {
                            $("#thumbs_down").css("color", "#E10000");
                        } else {
                            $("#thumbs_down").css("color", "#000000");
                        }
                    }
                }
            })
        });

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            var status = "<?php echo $this->session->userdata('logged')?>";
            if (!status) {
                alert('Please login to make comment')
            } else {
                var parentId = $('#comment_id').val();
                var comment_body = document.getElementById("comment").value;
                var videoId = parseInt("<?php echo $video['id']?>");
                $.ajax({
                    url: "<?php echo base_url()?>videoContent/add_comment",
                    method:"POST",
                    data: {comment_body: comment_body, videoId: videoId, parentId: parentId},
                    success:function(data)
                    {
                        $('#comment_form')[0].reset();
                        $('#comment_id').val('0');
                        load_comment();
                    }
                })
            }
        });

        load_comment();

        function load_comment() {
            var videoId = parseInt("<?php echo $video['id']?>");
            $.ajax({
                url: "<?php echo base_url()?>videoContent/generate_comments",
                method: "POST",
                cache: false,
                data:{videoId: videoId},
                success: function(data) {
                    $("#comment-container").html(data);
                }
            })
        }

        $(document).on('click', '.reply', function(){
            var comment_id = $(this).attr("id");
            $('#comment_id').val(comment_id);
            $('#comment').focus();
        });
    });
</script>



