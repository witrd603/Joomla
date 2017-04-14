(function ($) {
    $.widget('ui.LoveFactorySlider', $.ui.slider, {
        _create: function () {
            var slider = this;

            // Mootools fix.
            this.element.prop('slide', null);

            // Set options from inline attributes.
            this.options = $.extend(this.options, $.parseJSON(slider.element.attr('data-slider')));

            this.elements = {};
            this.element.wrap('<div class="lovefactory-slider-wrapper" >');
            this.elements.wrapper = this.element.parent();

            var htmlInput = '<input type="hidden" class="value" name="" value="" />';
            var htmlValue = '<span class="value"></span>';

            if (this.options.values) {
                htmlInput += htmlInput;
                htmlValue += ' <span class="separator">-</span> ' + htmlValue;
            }

            this.elements.wrapper.prepend('<div class="selected">' + htmlValue + ' <span class="reset">(<a href="#">x</a>)</span></div>' + htmlInput);
            this.elements.reset = this.elements.wrapper.find('.reset');
            this.elements.input = this.elements.wrapper.find('input.value');
            this.elements.value = this.elements.wrapper.find('span.value');
            this.elements.separator = this.elements.wrapper.find('.separator');

            // Set default values.
            if (this.options.values) {
                $(this.elements.input[0]).val(this.options.values[0]);
                $(this.elements.input[0]).attr('name', this.options.inputName + '[0]');

                $(this.elements.input[1]).val(this.options.values[1]);
                $(this.elements.input[1]).attr('name', this.options.inputName + '[1]');

                if (this.options.choices) {
                    $(this.elements.value[0]).html(this.prepareDisplayValue(this.options.choices[this.options.values[0]]));
                    $(this.elements.value[1]).html(this.prepareDisplayValue(this.options.choices[this.options.values[1]]));
                }
                else {
                    //this.setDisplayValues(this.options.values);
                    $(this.elements.value[0]).html(this.prepareDisplayValue(this.options.values[0]));
                    $(this.elements.value[1]).html(this.prepareDisplayValue(this.options.values[1]));
                }
            }
            else {
                this.elements.input.val(this.options.value);
                this.elements.input.attr('name', this.options.inputName);

                if (this.options.choices) {
                    this.elements.value.html(this.options.choices[this.options.value]);
                }
                else {
                    this.elements.value.html(this.prepareDisplayValue(this.options.value));
                }
            }

            // Parent create.
            $.ui.slider.prototype._create.apply(this, arguments);

            // Hide handler if no value is selected.
            if (this.options.values) {
                if ('' === this.options.values[0] || '' === this.options.values[1]) {
                    this._selectBlank();
                }
            }
            else {
                if ('' === this.options.value) {
                    this._selectBlank();
                }
            }

            // Reset link.
            this.elements.reset.find('a').click(function (event) {
                event.preventDefault();

                slider._selectBlank();
            });
        },

        selectBlank: function () {
            this._selectBlank();
        },

        _selectBlank: function () {
            this.handles.hide();
            this.range.hide();

            this.elements.reset.hide();
            this.elements.input.val('');

            if (this.options.values) {
                $(this.elements.value[0]).html(this.options.labelBlank);
                $(this.elements.value[1]).hide();
                this.elements.separator.hide();
            }
            else {
                this.elements.value.html(this.options.labelBlank);
            }
        },

        _change: function (event, index) {
            // Parent change.
            $.ui.slider.prototype._change.apply(this, arguments);

            var hiddenRange = this.options.values && !this.range.is(':visible');

            this.handles.show();
            this.range.show();
            this.elements.separator.show();

            this.elements.reset.show();

            var label = this.values(index);
            if (this.options.choices) {
                label = this.options.choices[this.values(index)];
            }

            if (this.options.values) {
                $(this.elements.input[index]).val(this.values(index));
                $(this.elements.value[index]).show().html(this.prepareDisplayValue(label));
            }
            else {
                this.elements.input.val(this.values(index));
            }

            if (hiddenRange) {
                this.values([this.options.min, this.options.max]);
            }
        },

        _slide: function (event, index, newVal) {
            // Parent slide.
            $.ui.slider.prototype._slide.apply(this, arguments);

            if (this.options.choices) {
                newVal = this.options.choices[newVal];
            }

            if (this.options.values) {
                $(this.elements.value[index]).html(this.prepareDisplayValue(newVal));
            }
            else {
                this.elements.value.html(this.prepareDisplayValue(newVal));
            }
        },

        prepareDisplayValue: function (value) {
            if (this.options.prepareDisplayValue) {
                var result = this.options.prepareDisplayValue(value, this.options);

                if (undefined != result) {
                    return result;
                }
            }

            return this._prepareDisplayValue(value);
        },

        _prepareDisplayValue: function (value) {
            if (!this.options.labelAfter) {
                return value;
            }

            return value + ' ' + this.options.labelAfter;
        }
    });
})(jQuery);
