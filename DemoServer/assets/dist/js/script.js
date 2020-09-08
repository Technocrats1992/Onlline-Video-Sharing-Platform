$().ready(function() {
	$("#login").validate({
		rules: {
			username: {
				required: true,
				minlength: 8,
				maxlength: 20
			},
			password: {
				required: true,
				minlength: 8,
				maxlength: 20
			},
		},
		messages: {
			username: {
				required: "Please input your username",
				minlength: "Username should be no less than {0} chars"
			},
			password: {
				required: "Please input your password",
				minlength: "Password should be no less than {0} chars"
			},
		}
	});
	$("#register").validate({
		rules: {
			register_username: {
				required: true,
				minlength: 8,
				maxlength: 20
			},
			register_password: {
				required: true,
				minlength: 8,
				maxlength: 20
			},
			rpassword: {
				equalTo: "#register_password"
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				minlength: 8,
				maxlength: 20
			}
		},
		messages: {
			register_username: {
				required: "Please input your username",
				minlength: "Username should be no less than {0} chars.",
			},
			register_password: {
				required: "Please input your password",
				minlength: "Password should be no less than {0} chars."
			},
			rpassword: {
				equalTo: "Password is not the same as above."
			},
			email: {
				required: "Please input your email.",
				email: "Please input a valid email address."
			},
			phone: {
				required: "Please input your phone number.",
				minlength: "Password should be no less than {0} chars."
			}
		}
	});
});

$(window).scroll(function () {
	if ($(".navbar").offset().top > 50) {
		$(".navbar-fixed-top").addClass("top-nav");
	} else {
		$(".navbar-fixed-top").removeClass("top-nav");
	}
});






