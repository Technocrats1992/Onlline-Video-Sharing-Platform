<html>
<body>
    <div class="container col-lg-offset-1 col-lg-10" id="frame">
        <div id="load_data" class="row"></div>
        <div id="load_data_message"></div>
    </div>
</body>
<script>
    $(document).ready(function(){
        var limit = 9;
        var start = 0;
        var action = "inactive";

        function load_previous_position() {
            if (localStorage.getItem('index-page') !== null) {
                document.querySelector('#frame').innerHTML = localStorage.getItem('index-page');
                window.scrollTo(0, localStorage.getItem('index-y'));
                start = localStorage.getItem('index-start');
            } else {
                if (action === "inactive") {
                    action = "active";
                    load_data(limit, start);
                }
            }
        }

        load_previous_position();

        if (action === "inactive") {
            action = "active";
            load_data(limit, start);
        }

        // Continuously load data
        function lazzy_loader(limit)
        {
            var output = "";
            for(var count=0; count<limit; count++)
            {
                output += '<div class="post_data">';
                output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
                output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
                output += '</div>';
            }
            $('#load_data_message').html(output);
        }

        lazzy_loader(4);

        function load_data(limit, start) {
            $.ajax({
                url: "<?php echo base_url()?>profile/fetch_videos",
                method: "POST",
                cache: false,
                data:{limit:limit, start:start},
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.length === 0) {
                        $("#load_data_message").html('<div class="alert alert-warning text-left"><h3>' +
                            '<span class="glyphicon glyphicon-stop" style="margin-right: 12px">' +
                            '</span>No more results found</h3></div>');
                        action = "active";
                    } else {

                        console.log(data);
                        var output = "";
                        for(var count=0; count<data.length; count++)
                        {
                            output += '<a href="<?php echo base_url()?>videoContent/load_video/' + data[count].id + '"><div class="col-sm6 col-md-3 video-box">';
                            output += '<div class="index-videos">';
                            output += '<img src="https://infs3202-b51d6e13.uqcloud.net/Demo/' + data[count].thumbnail +
                                '" class="img-responsive img-rounded video-thumbnail"';
                            output += 'id="' + data[count].filename + '">';
                            output += '</div>';
                            output += '<div class="text-muted video-title">' +
                                '<span class="glyphicon glyphicon-facetime-video">' +
                                '</span>' + data[count].filename + '</div>';
                            output += '<div><div class="text-muted video-user">' +
                                '<span class="glyphicon glyphicon-user">' +
                                '</span>' + data[count].username + '</div>';
                            output += '<div class="text-muted video-date">' +
                                '<span class="glyphicon glyphicon-calendar">' +
                                '</span>' + data[count].date + '</div></div>';
                            output += '</div></a>'
                        }
                        $("#load_data").append(output);
                        $("#load_data_message").html("");
                        action = "inactive";
                    }
                }
            })
        }

        $(window).scroll(function() {
            localStorage.setItem('index-page', document.querySelector('#frame').innerHTML);
            localStorage.setItem('index-start', start);
            localStorage.setItem('index-y', $(window).scrollTop());
            if($(window).scrollTop() + $(window).height() > ($("#frame").height() + 50) && action !== 'active')
            {
                lazzy_loader(4);
                action = 'active';
                start = start + limit;
                setTimeout(function() {
                    load_data(limit, start);
                }, 400);
            }
        });
    });
</script>
</html>


