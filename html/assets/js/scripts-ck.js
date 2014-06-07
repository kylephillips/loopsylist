/*
* Toggle Mobile Nav
*/function searchLists(){var e=$("#searchform").attr("action"),t=$("#searchform").serialize();$.ajax({type:"POST",url:e,data:t,success:function(e){displayResults(e)}})}function displayResults(e){$("#searchresults").empty();jQuery.isEmptyObject(e)&&$("#searchresults").append('<li class="no-results">No Lists Found</li>');var t="";$.each(e,function(e,n){var r='<li><a href="http://localhost/~kyle/loopsylist/html/list/'+n.slug+'"><strong>'+n.name+"</strong>, <span>"+n.city+" "+n.state+"</a></li>";t+=r});$("#searchresults").append(t);$("#loading").hide();$(".search-results").show()}function geocodeSearch(){var e=$("#location").val();geocoder=new google.maps.Geocoder;geocoder.geocode({address:e},function(e,t){if(t==google.maps.GeocoderStatus.OK){var n=e[0].geometry.location.lat(),r=e[0].geometry.location.lng();$("#latitude").val(n);$("#longitude").val(r);searchLists()}})}function validateField(e,t,n){if(e=="password_shown")var e="password";t.length>0?$.ajax({type:"GET",url:n,data:{field:e,value:t},success:function(t){t.status==="error"?displayError(e):displaySuccess(e)}}):removeFeedback(e)}function removeFeedback(e){var t="#"+e,n=$(t).parent("div");$(n).removeClass("has-error");$(n).removeClass("has-success");$(n).find(".icon-feedback").remove()}function displayError(e){var t="#"+e,n=$(t).parent("div");removeFeedback(e);$(n).addClass("has-error");$(n).append('<i class="icon-remove icon-feedback"></i>')}function displaySuccess(e){var t="#"+e,n=$(t).parent("div");removeFeedback(e);$(n).addClass("has-success");$(n).append('<i class="icon-check icon-feedback"></i>')}function geocodeZip(e){geocoder=new google.maps.Geocoder;geocoder.geocode({address:e},function(e,t){if(t==google.maps.GeocoderStatus.OK){for(var n in e[0].address_components)for(var r in e[0].address_components[n].types){e[0]["address_components"][n]["types"][r]=="locality"&&(city=e[0].address_components[n].short_name);e[0]["address_components"][n]["types"][r]=="administrative_area_level_1"&&(state=e[0].address_components[n].long_name)}var i=e[0].geometry.location.lat(),s=e[0].geometry.location.lng();$("#latitude").val(i);$("#longitude").val(s);$("#city").val(city);$("#state").val(state);$("#addressUpdate").unbind("submit");$("#addressUpdate").submit()}else{$(".page-loading").hide();$("#addresserror").show();$("#addressform button").removeAttr("disabled")}})}$(".nav-toggle").on("click",function(e){e.preventDefault();$("body").toggleClass("open")});$(".login-trigger").on("click",function(e){e.preventDefault();var t=$(this).attr("href")+" "+"#login-form-cont";$("#modal-cont").addClass("open");$(".modal-body").addClass("loading");$("#modal-cont .modal-body").load(t,function(){$("#username").focus();$(".modal-body").removeClass("loading")})});$(".modal-body, .login-trigger, .showphoto").click(function(e){e.stopPropagation()});$(".modal").not(".modal-body, .showphoto, .login-trigger").on("click",function(){$(".modal").removeClass("open")});$(".switch a").on("click",function(e){e.preventDefault();var t=$(this).attr("href"),n=$(this).parents(".switch").find("span");$(".switch a").removeClass("active");$(this).addClass("active");if(t=="#name"){$(n).removeClass("right");$("#location").val("");$("#location").hide();$("#name").show();$("#type").val("name")}else{$(n).addClass("right");$("#name").val("");$("#name").hide();$("#location").show();$("#type").val("location")}});$("#searchform").on("submit",function(e){e.preventDefault();var t=$("#type").val();$(".search-results").hide();$("#loading").show();t=="name"?searchLists():geocodeSearch()});$(document).on("submit",".modal #login-form",function(e){e.preventDefault();$("#login-submit").prop("disabled","disabled");var t=$(this).attr("action"),n=$(this).serialize();$.ajax({type:"POST",url:t,data:n,success:function(e){if(e.status=="error"){$("#login-error").text(e.message);$("#login-error").show();$("#login-submit").prop("disabled",!1)}else location.reload()}})});$(document).on("keyup","#password",function(){var e=$("#password").val();$("#password_shown").val(e)});$(document).on("keyup","#password_shown",function(){var e=$("#password_shown").val();$("#password").val(e)});$("#toggle-password").on("click",function(e){e.preventDefault();if($("#password").is(":visible")){$(this).text("Hide");$("#password").hide();$("#password_shown").show()}else{$(this).text("Show");$("#password").show();$("#password_shown").hide()}});$("#addressUpdate").on("submit",function(e){var t=$("#zip").val();if(t.length>0){e.preventDefault();geocodeZip(t)}});