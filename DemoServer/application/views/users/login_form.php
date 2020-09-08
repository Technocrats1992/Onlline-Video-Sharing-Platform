<body>
<div class="container" id="panel">
	<div class="form row" id="login_form">
		<?php $attributes = array(
			"class" => "form-horizontal col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6",
			"role" => "form", "id" => "login", "method" => "post");// HTTP request method "POST"
			echo form_open("users/login", $attributes)?>
			<div class="heading">Login</div>
			<div class="col-sm-12 col-md-12 form_rows">
				<div class="form-group">
					<span class="glyphicon glyphicon-user col-sm-1 login-icon"></span>
					<div class="col-sm-10">
						<input class="form-control required" type="text" placeholder="Username"
							   value = "<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; }
							   // If cookie is set, remember the username?>"
							   name="username" autofocus="autofocus" maxlength="20" />
					</div>
				</div>
				<div class="form-group">
					<span class="glyphicon glyphicon-lock col-sm-1 login-icon"></span>
					<div class="col-sm-10">
						<input class="form-control required" type="password" placeholder="Password"
							   value = "<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; }
							   // If cookie is set, remember the password?>"
							   name="password" maxlength="20"/>
					</div>
				</div>
                <div class="form-group">
                    <span id="captImg" class="col-sm-offset-1 col-sm-4"><?php if (isset($captchaImg)) echo $captchaImg; ?></span>
                    <div class="col-sm-6">
                        <input class="form-control required" type="text" placeholder="Captcha" maxlength="4" name="captcha">
                    </div>
                </div>
				<?php if(isset($flag)){ if($flag == "invalid username") // If the username is not existed
				{echo "<div class=\"inc-info col-sm-offset-1\">
                <span class='glyphicon glyphicon-remove-sign'></span>The username is not registered.</div>";}
				else if ($flag == 'invalid password')
				{echo "<div class=\"inc-info col-sm-offset-1\"> 
                <span class='glyphicon glyphicon-remove-sign'></span>Incorrect password.</div>";}
                else if ($flag == 'invalid captcha')
                {echo "<div class=\"inc-info col-sm-offset-1\"> 
                <span class='glyphicon glyphicon-remove-sign'></span>Incorrect captcha.</div>";}
				else {echo "<div class=\"inc-info col-sm-offset-1\"> 
                <span class='glyphicon glyphicon-remove-sign'></span>Email remains to be verified</div>";}};
				// If the password is not correct?>
				<div class="form-group" id="remember">
					<label class="checkbox">
						<input  <?php if(isset($_COOKIE["username"]))
						{ ?> checked="checked" <?php } ?>type="checkbox" name="remember" value="1"/> Remember me
					</label>
					<hr />
					<a href="javascript:" id="register_btn" class="">Create an account</a> /
                    <a href="javascript:" id="reset_password_btn" class="">Forgot Password</a>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success pull-right" value="Login "/>
				</div>
			</div>
		</form>
	</div>
	<div class="form row" id="register_form">
		<?php $attributes = array(
			"class" => "form-horizontal col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6",
			"role" => "form", "id" => "register", "method" => "post"); // HTTP request method "POST"
			echo form_open("users/register", $attributes)?>
			<div class="heading">Sign-up</div>
			<div class="col-sm-12 col-md-12 form_rows">
				<div class="form-group">
					<span class="glyphicon glyphicon-user col-sm-1 login-icon"></span>
					<div class="col-sm-10">
						<input class="form-control required" type="text" placeholder="Username"
							   name="register_username" autofocus="autofocus" maxlength="20"/>
					</div>
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
						<input class="form-control required" type="text" placeholder="Re-type Your Password"
							   name="rpassword" maxlength="20"/>
					</div>
				</div>
				<div class="form-group">
					<span class="glyphicon glyphicon-envelope col-sm-1 login-icon"></span>
					<div class="col-sm-10">
						<input class="form-control email" type="text" placeholder="Email" name="email"
                               maxlength="40"/>
					</div>
				</div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-phone col-sm-1 login-icon"></span>
                    <div class="col-sm-10">
                        <input class="form-control required" type="text" placeholder="Phone" name="phone" maxlength="20"/>
                    </div>
                </div>
				<?php if(isset($flag)){ if($flag == 'Duplicated username')
				{echo "<div class=\"inc-info col-sm-offset-1\">
                <span class='glyphicon glyphicon-remove-sign'></span>Duplicated username, try again!</div>";}
				else {echo "<div class=\"inc-info col-sm-offset-1\">
                <span class='glyphicon glyphicon-remove-sign'></span>Duplicated email, try again!</div>";}} ?>
				<div class="form-group">
					<input type="submit" class="btn btn-success pull-right" value="Sign Up "/>
					<input type="button" class="btn btn-primary pull-left" id="back_btn" value="Back"/>
				</div>
			</div>
		</form>
	</div>
    <div class="form row" id="forgot_password_form">
        <?php $attributes = array(
            "class" => "form-horizontal col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6",
            "role" => "form", "id" => "reset_password", "method" => "post");// HTTP request method "POST"
        echo form_open("users/reset_password_request", $attributes)?>
        <div class="heading">Reset Password</div>
        <div class="col-sm-12 col-md-12 form_rows">
            <div class="alert alert-warning text-left">
                <strong>Enter your email address below, and we will send you a link to reset your password</strong>
            </div>
            <div class="form-group">
                <span class="glyphicon glyphicon-envelope col-sm-1 login-icon"></span>
                <div class="col-sm-10">
                    <input class="form-control email" type="text" placeholder="Email" name="email"
                           maxlength="40"/>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success pull-right" value="Reset "/>
                <input type="button" class="btn btn-primary pull-left" id="back_btn_pass" value="Back"/>
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#captImg').on('click', function(){
            $.get('<?php echo base_url().'users/refresh_captcha'; ?>', function(data){
                $('#captImg').html(data);
            });
        });
    })

    $("#register_btn").click(function() {
        $("#register_form").css("display", "block");
        $("#login_form").css("display", "none");
    });

    $("#back_btn").click(function() {
        $("#register_form").css("display", "none");
        $("#login_form").css("display", "block");
    });

    $("#reset_password_btn").click(function() {
        $("#forgot_password_form").css("display", "block");
        $("#login_form").css("display", "none");
    });

    $("#back_btn_pass").click(function() {
        $("#forgot_password_form").css("display", "none");
        $("#login_form").css("display", "block");
    });

    $.ajax({
        url: "<?php echo base_url()?>users/flag",
        method: "GET",
        data: {},
        success: function(data) {
            if (data) {
                data = JSON.parse(data);
                if (data === "register_fail") {
                    $("#register_form").css("display", "block");
                    $("#login_form").css("display", "none");

                }
            }
        }
    });

    $(window).scroll(function() {
        localStorage.setItem('login_form', parseInt($(window).scrollTop()));
        console.log(localStorage.getItem('login_form'));
    });

    // Record the scroll position by localStorage in js
    if (localStorage.getItem('login_form') !== undefined) {
        window.scrollTo(0, localStorage.getItem('login_form'));
    }

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
</body>

