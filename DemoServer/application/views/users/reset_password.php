<body>
<div class="container" id="panel">
    <div class="form row" id="reset_form">
        <?php $attributes = array(
            "class" => "form-horizontal col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6",
            "role" => "form", "id" => "register", "method" => "post"); // HTTP request method "POST"
        echo form_open("users/reset_password", $attributes)?>
        <div class="heading">Reset Password</div>
        <div class="col-sm-12 col-md-12 form_rows">
            <div class="alert alert-warning text-left">
                <strong>Hi <?php if(isset($username)) echo $username ?>! This is the link to set your password. Please complete with form. </strong>
            </div>
            <div class="form-group">
                <span class="glyphicon glyphicon-lock col-sm-1 login-icon"></span>
                <div class="col-sm-10">
                    <input class="form-control required" type="text" placeholder="Password"
                           id="register_password" name="register_password" maxlength="20"/>
                </div>
                <div class="col-sm-10 col-sm-offset-1">
                    <div id="pwd_container"><div id="psw-bar"></div></div>
                    <span id="PSTip"></span>
                </div>
            </div>
            <div class="form-group">
                <span class="glyphicon glyphicon-ok col-sm-1 login-icon"></span>
                <div class="col-sm-10">
                    <input type="hidden" name="username" value="<?php echo $username?>">
                    <input class="form-control required" type="text" placeholder="Re-type Your Password"
                           name="rpassword" maxlength="20"/>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success pull-right" value="Reset"/>
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        document.getElementById("register_password").onkeyup = function() {
            var regArr = [];
            regArr[0]=/[^a-zA-Z0-9_]/;
            regArr[1]=/[0-9]/;
            regArr[2]=/[a-z]/;
            regArr[3]=/[A-Z]/;
            var val=document.getElementById("register_password").value;
            var sec=0;
            if(val.length>=8) {
                for(var i=0;i<regArr.length;i++) {
                    if(val.match(regArr[i])) {
                        sec ++;
                    }
                }
                document.getElementById("pwd_container").style.display = "block";
            }
            if(sec===0) {
                document.getElementById("PSTip").innerHTML="";
                document.getElementById("psw-bar").className = "";
                document.getElementById("pwd_container").style.display = "none";
            }else if(sec===1) {
                document.getElementById("PSTip").innerHTML="Strength：Weak";
                document.getElementById("PSTip").style.color="red";
                $("#psw-bar").addClass("weak-id");
            }else if(sec===2) {
                document.getElementById("PSTip").innerHTML="Strength：Middle";
                document.getElementById("PSTip").style.color="orange";
                $("#psw-bar").addClass("middle-id");
            }else if(sec===3) {
                document.getElementById("PSTip").innerHTML="Strength：Strong";
                document.getElementById("PSTip").style.color="blue";
                $("#psw-bar").addClass("strong-id");
            }else if(sec===4) {
                document.getElementById("PSTip").innerHTML="Strength：Overstrong";
                document.getElementById("PSTip").style.color="green";
                $("#psw-bar").addClass("overstrong-id");
            }
        };
    });
</script>