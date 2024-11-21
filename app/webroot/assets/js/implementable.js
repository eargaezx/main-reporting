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



                // Validación personalizada: data-validate-equals
                inputs.filter('[data-validate-equals]').each(function () {
                    var targetId = $(this).data('validate-equals'); // ID del input objetivo
                    var targetElement = $(`#${targetId}`);
                    
                    if (targetElement.length && $(this).val() !== targetElement.val()) {
                        isValid = false;

                        // Marcar ambos inputs como inválidos
                        this.setCustomValidity('The values are not equals');
                        targetElement[0].setCustomValidity('The values are not equals');
                        
                        this.reportValidity();
                        targetElement[0].reportValidity();
                    } else {
                        // Restablecer validez en caso de que se corrija
                        this.setCustomValidity('');
                        if (targetElement.length) {
                            targetElement[0].setCustomValidity('');
                        }
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
                }else{
                    $(form).find('a.next').text('Next');
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