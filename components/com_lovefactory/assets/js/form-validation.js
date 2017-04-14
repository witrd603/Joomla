function formValidation() {
    var valid = true;
    var scroll = false;
    var targetOffset = null;
    var password_id = null;
    var repeat_password_id = null;

    jQueryFactory("div[id^='error_field_']").each(function () {
        jQueryFactory(this).hide();
    });

    // Required validators
    for (var i = 0; i < conditions.length; i++) {
        var condition = conditions[i];
        var error_field = jQueryFactory("#error_field_" + condition.id);
        var error = false;

        switch (condition.type_id) {
            case 1:  // Text input
            case 2:  // Text area
            case 12: // Username
            case 18: // Email
                if ('' == jQueryFactory("#field_" + condition.id).val()) {
                    error = true;
                }
                break;

            case 7: // Birth date
                if ('' == jQueryFactory('#field_' + condition.id + 'day').val() ||
                    '' == jQueryFactory('#field_' + condition.id + 'month').val() ||
                    '' == jQueryFactory('#field_' + condition.id + 'year').val()) {
                    error = true;
                }
                break;

            case 19: // Password
            case 20: // Repeat password
                if ('' == jQueryFactory("#field_" + condition.id).val()) {
                    error = true;
                }

                if (19 == condition.type_id) {
                    password_id = condition.id;
                }
                else {
                    repeat_password_id = condition.id;
                }
                break;

            case 21: // Main photo upload
                if ('' == jQueryFactory("#main_photo_upload").val() && !jQueryFactory('#main_photo_upload').attr('disabled')) {
                    error = true;
                }

                if ('' == jQueryFactory("#main_photo_upload_classic").val() && !jQueryFactory('#main_photo_upload_classic').attr('disabled')) {
                    error = true;
                }
                break;

            case 26: // ReCaptcha
                if ('' == jQueryFactory("#recaptcha_response_field").val()) {
                    error = true;
                }
                break;

            case 4: // Drop down (multiple select)
                if (0 == jQueryFactory("#field_" + condition.id + " :selected").length) {
                    error = true;
                }
                break;

            case 5: // Checkboxes
            case 6: // Radio buttons
            case 28: // Terms and Conditions
                if (0 == jQueryFactory("input[name^='field_" + condition.id + "']:checked").length) {
                    error = true;
                }
                break;

            case 3:  // Singe select
            case 10: // Sex
            case 11: // Looking for
                if (-1 == jQueryFactory("#field_" + condition.id).val()) {
                    error = true;
                }
                break;

            case 23: // Google maps location
                if (0 == jQueryFactory("#gmap_lng").val() && 0 == jQueryFactory("#gmap_lat").val()) {
                    error = true;
                }
                break;
        }

        if (error) {
            error_field.fadeIn("fast").find("span").html(required_message);
            valid = false;

            if (error_field.offset().top < targetOffset || null == targetOffset) {
                targetOffset = error_field.offset().top;
            }
        }
        else {
            error_field.fadeOut("fast");
        }
    }

    // Text fields validators
    jQueryFactory("#lovefactory-form input:text[alt!=0]").each(function () {
        var input = jQueryFactory(this);
        var rel = input.attr("alt");
        var split = rel.split('___');
        var type = split[0];
        var error = false;

        if ('' != input.val()) {
            switch (type) {
                case "1":
                    if (isNaN(input.val())) {
                        var id = input.attr("id");
                        id = id.split('_');
                        id = id[1];

                        if ("none" == jQueryFactory("#error_field_" + id).css("display")) {
                            jQueryFactory("#error_field_" + id).fadeIn("fast").find("span").html(split[1]);
                        }

                        error = true;
                    }
                    break;

                case "2":
                    var vregex = split[2].replace(/\s+$/, "");
                    var re = new RegExp(vregex);

                    if (!re.test(input.val())) {
                        var id = input.attr("id");
                        id = id.split('_');
                        id = id[1];

                        if ("none" == jQueryFactory("#error_field_" + id).css("display")) {
                            jQueryFactory("#error_field_" + id).fadeIn("fast").find("span").html(split[1]);
                        }

                        error = true;
                    }
                    break;
            }
        }

        error_field = jQueryFactory("#error_field_" + id);
        if (error) {
            if (error_field.offset().top < targetOffset || null == targetOffset) {
                targetOffset = error_field.offset().top;
            }
            valid = false;
        }
    });

    // Password validators
    if ((null != password_id && null != repeat_password_id) &&
        (jQueryFactory("#field_" + password_id).val() != jQueryFactory("#field_" + repeat_password_id).val()) &&
        ("none" == jQueryFactory("#error_field_" + repeat_password_id).css("display"))) {
        var message = jQueryFactory("#field_" + repeat_password_id).attr("rel");
        jQueryFactory("#error_field_" + repeat_password_id).fadeIn("fast").find("span").html(message);

        error = true;

        error_field = jQueryFactory("#error_field_" + repeat_password_id);
        if (error_field.offset().top < targetOffset || null == targetOffset) {
            targetOffset = error_field.offset().top;
        }
        valid = false;
    }

    if (null != targetOffset) {
        jQueryFactory('html,body').animate({scrollTop: targetOffset}, "fast");
    }

    return valid;
}
