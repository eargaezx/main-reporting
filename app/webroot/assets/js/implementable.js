(function ($) {
    $.fn.composableWizard = function () {
        var form = this;

        $(form).bootstrapWizard({
            'tabClass': 'nav nav-pills',
            'nextSelector': '.next',
            'previousSelector': '.previous',
            onNext: function (tab, navigation, index) {
                var currentStep = $(form).find('.tab-pane.active');
                var inputs = currentStep.find('input, select');
                var isValid = true;

                inputs.each(function () {
                    if (!this.checkValidity()) {
                        isValid = false;
                        this.reportValidity();
                    }
                });

                if (!isValid) {
                    return false;
                }{//valid form
                    //$(form).find('.tab-pane.active').find('form').first().submit();
                }

                var totalTabs = navigation.find('li').length;
                var currentTabIndex = index;

                if (isValid && currentTabIndex === (totalTabs)) {
                    form.parent().submit();
                }
            },
            onTabShow: function (tab, navigation, index) {
                var totalTabs = navigation.find('li').length;
                var currentTabIndex = index + 1;

                if (currentTabIndex ==  totalTabs ) {
                    $(form).find('a.next').text('Save').attr('type', 'button').addClass('btn-primary next').removeClass('submit btn-success disabled');
                }
            },
        });

        // Custom removal of buttons based on text
        $(this).find('button, a, input').filter(function () {
            return $(this).text() === 'Cancel' || $(this).text() === 'Save' || $(this).val() === 'Save';
        }).remove();

        return this;
    };
})(jQuery);