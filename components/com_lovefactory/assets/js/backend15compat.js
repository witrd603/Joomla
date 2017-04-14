Joomla.submitform = function (task, form) {
    if (typeof(form) === 'undefined') {
        form = document.getElementById('adminForm');
        /**
         * Added to ensure Joomla 1.5 compatibility
         */
        if (!form) {
            form = document.adminForm;
        }
    }
    if (typeof(task) !== 'undefined') {
        if (task.indexOf('.')) {
            var split = task.split('.');

            form.task.value = split[1];

            if (form.controller == undefined) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'controller';
                form.appendChild(input);
            }
            form.controller.value = split[0];

        } else {
            form.task.value = task;
        }
    }
// Submit the form.
    if (typeof form.onsubmit == 'function') {
        form.onsubmit();
    }
    if (typeof form.fireEvent == "function") {
        form.fireEvent('submit');
    }
    form.submit();

    form.task.value = '';
    form.controller.value = '';
};

function listItemTask(id, task) {
    var f = document.adminForm;
    var cb = f[id];
    if (cb) {
        for (var i = 0; true; i++) {
            var cbx = f['cb' + i];
            if (!cbx)
                break;
            cbx.checked = false;
        } // for
        cb.checked = true;
        f.boxchecked.value = 1;
        Joomla.submitbutton(task);
    }
    return false;
}

function checkAll_button(n, task) {
    if (!task) {
        task = 'saveorder';
    }
    for (var j = 0; j <= n; j++) {
        var box = document.adminForm['cb' + j];
        if (box) {
            if (box.checked == false) {
                box.checked = true;
            }
        } else {
            alert("You cannot change the order of items, as an item in the list is `Checked Out`");
            return;
        }
    }

    Joomla.submitform(task);
}
