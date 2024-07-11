$(document).ready(function() {
    "use strict";
    $("#basicwizard").bootstrapWizard();

    $("#progressbarwizard").bootstrapWizard({
        onTabShow: function(t, r, a) {
            var o = (a + 1) / r.find("li").length * 100;
            $("#progressbarwizard").find(".bar").css({
                width: o + "%"
            });
        }
    });

    $("#btnwizard").bootstrapWizard({
        nextSelector: ".button-next",
        previousSelector: ".button-previous",
        firstSelector: ".button-first",
        lastSelector: ".button-last"
    });

    $("#rootwizard").bootstrapWizard({
        onNext: function(t, r, a) {
            var o = $($(t).data("targetForm"));
            if (o) {
                o.addClass("was-validated");
                if (!o[0].checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
            }
        }
    });
});
