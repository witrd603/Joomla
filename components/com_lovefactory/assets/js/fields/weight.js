jQuery(document).ready(function ($) {
    $('.lovefactory-slider-weight').LoveFactorySlider({
        prepareDisplayValue: function (value, options) {

            if ('imperial' == options.unit) {
                return Math.floor(parseInt(value) * 2.2) + ' ' + options.labelAfter;
            }
        }
    });
});
