function __jnp_load_page_noRegister(i, a) {

/*
Esta funÃ§Ã£o Ã© uma cÃ³pia modificada da parte JS de AdiantiCoreApplication::loadPage localizada sob link https://www.adianti.com.br/apis-framework_fsource_core__coreAdiantiCoreApplication.php.html#a304 (os devidos crÃ©ditos ao autor)
*/

    if (typeof i !== "undefined") {
        $(".modal-backdrop").remove();
        var e = i;
        e = e.replace("index.php", "engine.php");
        if (e.indexOf("engine.php") == -1) {
            e = "xhr-" + e
        }
        __adianti_run_before_loads(e);
        if (__adianti_can_open_iframe(e)) {
            var t = e.replace("engine.php", "index.php");
            Adianti.requestURL = t;
            Adianti.requestData = null;
            __adianti_goto_iframe(t);
            __adianti_load_tab(t, "");
            __adianti_run_after_loads(t, "")
        } else {
            if (e.indexOf("&static=1") > 0 || e.indexOf("?static=1") > 0) {
                $.get(e).done(function(t) {
                    Adianti.requestURL = e;
                    Adianti.requestData = null;
                    __adianti_load_tab(i, t);
                    __adianti_parse_html(t);
                    if (typeof a == "function") {
                        a()
                    }
                    __adianti_run_after_loads(e, t)
                }).fail(function(t, i, a) {
                    __adianti_failure_request(t, i, a);
                    loading = false
                })
            } else {
                $.get(e).done(function(t) {
                    Adianti.requestURL = e;
                    Adianti.requestData = null;
                    __adianti_load_tab(i, t);
                    __adianti_load_html(t, __adianti_run_after_loads, e);
                    if (typeof a == "function") {
                        a()
                    }
                    if (e.indexOf("register_state=false") < 0 && history.pushState && t.indexOf('widget="TWindow"') < 0) {
                        if (!Adianti.useTabs || !t.match('adianti_target_container\\s?=\\s?"([0-z-]*)"')) {
                            // __adianti_register_state(e, "adianti");
                            Adianti.currentURL = e
                        }
                    }
                }).fail(function(t, i, a) {
                    __adianti_failure_request(t, i, a);
                    loading = false
                })
            }
        }
    }
}

function bdaterange_start(b, a) {
    let c = document.getElementById(a.id_start),
        e = document.getElementById(a.id_end);
    $(c).data("range_type", "normal");
    let f = {
        element: c,
        css: ["lib/independent/css/easepick.css"],
        header: a.title,
        zIndex: a.zIndex,
        grid: a.grid,
        calendars: a.calendars,
        readonly: !1,
        autoApply: a.autoApply,
        plugins: ["RangePlugin", "LockPlugin"],
        lang: a.language,
        format: a.format,
        locale: { cancel: "pt" == a.language ? "Cancelar" : "Cancel", apply: "pt" == a.language ? "Aplicar" : "Apply" },
        setup(d) {
            d.on("select", (h) => {
                a.changeaction && eval(a.changeaction);
            });

            d.on('show', (h) => {
                jn_autoPosition_easyPick(d.ui.container, a.id_start);
            });
        },
        RangePlugin: { delimiter: a.separator, tooltipNumber: (d) => d - 1, locale: a.locale ?? { one: "pt" == a.language ? "Dia" : "Day", other: "pt" == a.language ? "Dias" : "Days" } },
    };
    a.time && (f.plugins.push("TimePlugin"), (f.TimePlugin = { seconds: a.seconds, format: "HH:mm:ss", stepSeconds: a.stepSeconds, stepHours: a.stepHours, stepMinutes: a.stepMinutes }));
    a.id_end && ($(c).data("range_type", "start"), $(e).data("range_type", "end"), (f.RangePlugin.elementEnd = e));
    if (a.enableDates || a.disableDates)
        f.LockPlugin = {
            minDate: new Date(),
            minDays: 1,
            inseparable: !0,
            filter(d) {
                if (a.enableDates) return !a.enableDates.includes(d.format("YYYY-MM-DD"));
                if (a.disableDates) return a.disableDates.includes(d.format("YYYY-MM-DD"));
            },
        };
    const g = new easepick.create(f);
    $(c).on("change", function () {
        if (this.value) g.setStartDate(this.value);
        else {
            let d = $(e).val();
            g.clear();
            e && d && g.setEndDate(d);
        }
        a.changeaction && eval(a.changeaction);
    });
    $(e).on("change", function () {
        if (this.value) g.setEndDate(this.value);
        else {
            let d = $(c).val();
            g.clear();
            d && g.setStartDate(d);
        }
        a.changeaction && eval(a.changeaction);
    });
    $("#" + b).data("picker", g);
}

function jn_autoPosition_easyPick(container, inputField) {
    // Certifique-se de que inputField é um objeto jQuery
    var $inputField = $('#' + inputField);
    
    // Verifique se o inputField existe
    if ($inputField.length) {
        // Obtenha a posição do inputField
        var position = $inputField.offset();
        
        // Tamanho da tela
        var windowHeight = $(window).height();
        var windowWidth = $(window).width();

        // Calcula se o calendário (250px) cabe no espaço abaixo do elemento
        var spaceAbove = windowHeight - (position.top + $inputField.height());
        var isSpaceVisibleHeight = spaceAbove >= 250;
        var spaceAboveWidth = windowWidth - position.left;
        var isSpaceVisibleWidth = spaceAboveWidth >= 460;
        
        // Encontra o primeiro elemento de classe easepick-wrapper após o startElement
        var $easepickWrapper = $inputField.nextAll('.easepick-wrapper').first();

        $(container).css({
            top: 0,
            left: 0
        });

        
        if(!isSpaceVisibleHeight){
            $easepickWrapper.css({
                top: (position.top - 250) + 'px'
            });
        } else {
            $easepickWrapper.css({
                top: (position.top + $inputField.height()) + 'px'
            });
        }
        if(!isSpaceVisibleWidth){
            $easepickWrapper.css({
                left: (windowWidth - 460) + 'px'
            });
        } else {
            $easepickWrapper.css({
                left: position.left
            });
        }

    } else {
        console.log('O campo de entrada não foi encontrado.');
    }
}


function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            console.log('Texto copiado para a área de transferência');
        }).catch(function(err) {
            console.log('Erro ao copiar texto: ' + err);
        });
    } else {
        console.log('Clipboard API não suportada');
    }
}



function ttable_replace_row_by_id(a, b, c) {
    var tbody = $("#" + a + " tbody");
    var row = tbody.find("#" + b);
    var decodedContent = base64_decode(c);

    if (row.length > 0) {
        row.replaceWith(decodedContent);
    } else {
        tbody.prepend(decodedContent);
    }
}
