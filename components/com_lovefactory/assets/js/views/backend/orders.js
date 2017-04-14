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
};
