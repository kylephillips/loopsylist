/* 
* Base Scripts
*//*
* Toggle Mobile Nav
*/function validateField(e,t,n){if(e=="password_shown")var e="password";t.length>0?$.ajax({type:"GET",url:n,data:{field:e,value:t},success:function(t){t.status==="error"?displayError(e):displaySuccess(e)}}):removeFeedback(e)}function removeFeedback(e){var t="#"+e,n=$(t).parent("div");$(n).removeClass("has-error");$(n).removeClass("has-success");$(n).find(".form-control-feedback").remove()}function displayError(e){var t="#"+e,n=$(t).parent("div");removeFeedback(e);$(n).addClass("has-error");$(n).append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>')}function displaySuccess(e){var t="#"+e,n=$(t).parent("div");removeFeedback(e);$(n).addClass("has-success");$(n).append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>')}function geocodeZip(e){geocoder=new google.maps.Geocoder;geocoder.geocode({address:e},function(e,t){if(t==google.maps.GeocoderStatus.OK){var n=e[0].geometry.location.lat(),r=e[0].geometry.location.lng();$("#latitude").val(n);$("#longitude").val(r);$("#signup").unbind("submit");$("#signup").submit()}else{$(".page-loading").hide();$("#addresserror").show();$("#addressform button").removeAttr("disabled")}})}$(".nav-toggle").on("click",function(e){e.preventDefault();$("body").toggleClass("open")});$(".login-trigger").on("click",function(e){e.preventDefault();var t=$(this).attr("href")+" "+"#login-form-cont";$("#modal-cont").addClass("open");$("#modal-cont .modal-body").load(t,function(){$("#username").focus()})});$(".modal-body, .login-trigger").click(function(e){e.stopPropagation()});$(document).click(function(e){e.target.className!==".modal-body"&&$(".modal").removeClass("open")});$(document).on("submit","#login-form",function(e){e.preventDefault();var t=$(this).attr("action"),n=$(this).serialize();$.ajax({type:"POST",url:t,data:n,success:function(e){if(e.status=="error"){$("#login-error").text(e.message);$("#login-error").show()}else location.reload()}})});$(document).on("keyup","#password",function(){var e=$("#password").val();$("#password_shown").val(e)});$(document).on("keyup","#password_shown",function(){var e=$("#password_shown").val();$("#password").val(e)});$("#toggle-password").on("click",function(){var e=$(this).find("input"),t=$(this).find("span");if($(e).is(":checked")){$(t).text("Hide Password");$("#password").hide();$("#password_shown").show()}else{$(t).text("Show Password");$("#password").show();$("#password_shown").hide()}});$("#signup").on("submit",function(e){var t=$("#zip").val();if(t.length>0){e.preventDefault();geocodeZip(t)}});