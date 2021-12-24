$(function () {
    let effecttime = 200;

    /*
     * MOBILE MENU
     */
    $("[data-mobilemenu]").click(function (e) {
        let clicked = $(this);
        let action = clicked.data("mobilemenu");

        if (action === 'open') {
            $(".app_sidebar").slideDown(effecttime);
        }

        if (action === 'close') {
            $(".app_sidebar").slideUp(effecttime);
        }
    });

    // scroll animate
    $("[data-go]").click(function (e) {
        e.preventDefault();

        let goto = $($(this).data("go")).offset().top;
        $("html, body").animate({scrollTop: goto}, goto / 2);
    });

    /*
     * APP MODAL
     */
    $("[data-modalopen]").click(function (e) {
        let clicked = $(this);
        let modal = clicked.data("modalopen");
        $(".app_modal").fadeIn(effecttime).css("display", "flex");
        $(modal).fadeIn(effecttime);
    });

    $("[data-modalclose]").click(function (e) {
        if (e.target === this) {
            $(this).fadeOut(effecttime);
            $(this).children().fadeOut(effecttime);
        }
    });

    /*
     * FROM CHECKBOX
     */
    $("[data-checkbox]").click(function (e) {
        let checkbox = $(this);
        checkbox.parent().find("label").removeClass("check");
        if (checkbox.find("input").is(':checked')) {
            checkbox.addClass("check");
        } else {
            checkbox.removeClass("check");
        }
    });

    /*
     * FADE
     */
    $("[data-fadeout]").click(function (e) {
        let clicked = $(this);
        let fadeout = clicked.data("fadeout");
        $(fadeout).fadeOut(effecttime, function (e) {
            if (clicked.data("fadein")) {
                $(clicked.data("fadein")).fadeIn(effecttime);
            }
        });
    });

    $("[data-fadein]").click(function (e) {
        let clicked = $(this);
        let fadein = clicked.data("fadein");
        $(fadein).fadeIn(effecttime, function (e) {
            if (clicked.data("fadeout")) {
                $(clicked.data("fadeout")).fadeOut(effecttime);
            }
        });
    });

    /*
     * SLIDE
     */
    $("[data-slidedown]").click(function (e) {
        let clicked = $(this);
        let slidedown = clicked.data("slidedown");
        $(slidedown).slideDown(effecttime);
    });

    $("[data-slideup]").click(function (e) {
        let clicked = $(this);
        let slideup = clicked.data("slideup");
        $(slideup).slideUp(effecttime);
    });

    /*
     * TOOGLE CLASS
     */
    $("[data-toggleclass]").click(function (e) {
        let clicked = $(this);
        let toggle = clicked.data("toggleclass");
        clicked.toggleClass(toggle);
    });

    /*
     * jQuery MASK
     */
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-card").mask('0000  0000  0000  0000', {reverse: true});

    /*
     * AJAX FORM
     */
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let load = $(".ajax_load");
        let flashClass = "ajax_response";
        let flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            uploadProgress: function (event, position, total, completed) {
                let loaded = completed;
                let load_title = $(".ajax_load_box_title");
                load_title.text("Enviando (" + loaded + "%)");

                form.find("input[type='file']").val(null);
                if (completed >= 100) {
                    load_title.text("Aguarde, carregando...");
                }
            },
            success: function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    load.fadeOut(200);
                }

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            },
            error: function () {
                let message = "<div class='message error icon-warning'>Desculpe mas não foi possível processar a requisição. Favor tente novamente!</div>";

                if (flash.length) {
                    flash.html(message).fadeIn(100).effect("bounce", 300);
                } else {
                    form.prepend("<div class='" + flashClass + "'>" + message + "</div>")
                        .find("." + flashClass).effect("bounce", 300);
                }

                load.fadeOut();
            }
        });
    });

    /*
     * APP ON PAID
     */
    $("[data-onpaid]").click(function (e) {
        let clicked = $(this);
        let dataset = clicked.data();

        $.post(clicked.data("onpaid"), dataset, function (response) {
            //reload by error
            if (response.reload) {
                window.location.reload();
            }

            //Balance
            $(".j_total_paid").text("R$ " + response.onpaid.paid);
            $(".j_total_unpaid").text("R$ " + response.onpaid.unpaid);
        }, "json");
    });

    /*
     * IMAGE RENDER
     */
    $("[data-image]").change(function (e) {
        let changed = $(this);
        let file = this;

        if (file.files && file.files[0]) {
            let render = new FileReader();

            render.onload = function (e) {
                $(changed.data("image")).fadeTo(100, 0.1, function () {
                    $(this).css("background-image", "url('" + e.target.result + "')")
                        .fadeTo(100, 1);
                });
            };
            render.readAsDataURL(file.files[0]);
        }
    });

    /*
     * APP INVOICE REMOVE
     */
    $("[data-invoiceremove]").click(function (e) {
        let remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse lançamento?");

        if (remove === true) {
            $.post($(this).data("invoiceremove"), function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    });

    /*
     * WALLET FILTER
     */
    $(".app_header_widget .wallet").mouseenter(function () {
        $(this).find("ul").slideDown(200);
    }).mouseleave(function () {
        $(this).find("ul").slideUp(200);
    });

    $("[data-walletfilter]").click(function (e) {
        let wallet = $(this).data("wallet");
        let endpoint = $(this).data("walletfilter");

        $(".ajax_load")
            .fadeIn(200)
            .css("display", "flex")
            .find(".ajax_load_box_title")
            .text("Aguarde, abrindo carteira...");

        $.post(endpoint, {wallet: wallet}, function (e) {
            window.location.reload();
        }, "json");
    });

    /*
     * WALLET EDIT
     */
    $("[data-walletedit]").change(function () {
        let wallet = $(this).val();
        let endpoint = $(this).data("walletedit");
        $.post(endpoint, {wallet_edit: wallet}, "json");
    });

    /*
     * WALLET DELETE
     */
    $(".wallet_action").click(function () {
        $(this).parent().find(".wallet_overlay").fadeIn(200).css("display", "flex");
    });

    $(".wallet_overlay_close").click(function () {
        $(this).parents(".wallet").find(".wallet_overlay").fadeOut(200);
    });

    $("[data-walletremove]").click(function () {
        let wallet = $(this).data("wallet");
        let endpoint = $(this).data("walletremove");

        $(".ajax_load").fadeIn(200).css("display", "flex").find(".ajax_load_box_title").text("Removendo carteira...");
        $.post(endpoint, {wallet_remove: wallet}, function (e) {
            window.location.reload();
        });
    });
});