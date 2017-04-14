jQuery(document).ready(function ($) {
    $('.lovefactory-slider-height').LoveFactorySlider({
        prepareDisplayValue: function (value, options) {

            if ('imperial' == options.unit) {
                var inches = parseInt(value) * .3937008;
                return Math.floor(inches / 12) + '\'' + Math.floor(inches % 12) + '"';
            }
        }
    });
});
