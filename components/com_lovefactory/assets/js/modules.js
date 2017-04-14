if ('undefined' === typeof LoveFactoryModuleMembersLoaded) {

    jQuery(document).ready(function ($) {
        // Toggle configuration
        $('.lovefactory-module-configuration-link a').click(function (event) {
            event.preventDefault();

            var link = $(this);
            var configuration = link.parents('.lovefactory-module').find('.lovefactory-module-configuration');
            var id = link.attr('rel');

            if ('none' == configuration.css('display') && '' == configuration.html()) {
                link.toggleClass('module-config-loading');
                configuration.load(
                    root + 'index.php?option=com_lovefactory&controller=module&task=config&format=raw&id=' + id, function () {
                        $(this).slideToggle();
                        link.css('visibility', 'hidden');
                        link.toggleClass('module-config-loading');
                    });
            }
            else {
                configuration.slideToggle();
                link.css('visibility', 'hidden');
            }
        });

        // Cancel button
        $(document).on('click', '.lovefactory-module-configuration-buttons a', function (event) {
            event.preventDefault();

            var parent = $(this).parents('.lovefactory-module');
            var configuration = parent.find('.lovefactory-module-configuration');
            var loader = parent.find('.lovefactory-module-configuration-loading');
            var link = parent.find('.lovefactory-module-configuration-link a:first');

            configuration.slideUp('normal', function () {
                loader.hide();
                link.css('visibility', 'visible');
            });
        });

        // Save button
        $(document).on('click', '.lovefactory-module-configuration-buttons input', function (event) {
            event.preventDefault();

            var parent = $(this).parents('.lovefactory-module');
            var form = parent.find('form');
            var configuration = parent.find('.lovefactory-module-configuration');
            var loader = parent.find('.lovefactory-module-configuration-loading');
            var module = parent.attr('id');
            var id = module.replace('lovefactory-module-', '');

            // Show the loader
            loader.show();

            // Save the configuration
            var json = JSON.stringify(form.serializeObject());
            $.cookie(module, json);

            // Reload the module
            $.get(
                root + 'index.php?option=com_lovefactory&controller=module&task=reload',
                {id: id, format: 'raw'},
                function (response) {
                    // Update the module results
                    parent.find('.update').html(response);

                    // Slide the configuration and hide the loader
                    parent.find('.lovefactory-module-configuration-buttons a').click();
                });
        });

        // Pagination links
        $(document).on('click', '.lovefactory-module .module-pagination a', function (event) {
            event.preventDefault();

            var element = $(this);
            var url = element.attr('href');

            if (undefined == url) {
                return false;
            }

            var parent = element.parents('.lovefactory-module');
            var pagination = element.parents('.module-pagination');
            var module = parent.attr('id').replace('lovefactory-module-', '');
            var update = parent.find('.update');

            pagination.toggleClass('pagination-loading');

            $.get(url, {id: module}, function (response) {
                update.html(response);
                pagination.toggleClass('pagination-loading');
            });

            return true;
        });
    });

    jQuery.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        jQuery.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    LoveFactoryModuleMembersLoaded = true;
}
