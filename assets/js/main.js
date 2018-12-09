$(document).ready(function () {


    $("#submitAPI").click(function (ev) {

        ev.preventDefault()

        let form = $("#contactForm")
        let url = form.attr("action")
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize() + "&api=1", // serializes the form's elements.

            success: function (data) {
                let jdata = JSON.parse(data)
                console.log(data)
                console.log(jdata) // show response from the php script.

                if (jdata.error) {
                    $('[class="text-center error"]').remove()
                    $('[class="text-center success"]').remove()
                    $("#return-info").append('<p class="text-center error">'+jdata.error+'</p>');
                } else {
                    $('[class="text-center error"]').remove()
                    $('[class="text-center success"]').remove()
                    $("#return-info").append('<p class="text-center success">Merci le formlaire est bien valide via l\'api</p>');
                    /* Pas vraiment besoin de remettre les champs vu qu'il n'y a pas de rechargement de page
                    $('#fname').val(jdata.ok.fname)
                    $('#sname').val(jdata.ok.sname)
                    $('#email').val(jdata.ok.email)
                    $('#country').val(jdata.ok.country)
                    $('#message').val(jdata.ok.message)
                    */
                }
            }
        });
    });



    $('.input-group input[required], .input-group textarea[required], .input-group select[required]').on('keyup change', function () {
        let form = $(this).closest('form'),
            group = $(this).closest('.input-group'),
            addon = group.find('.input-group-addon'),
            icon = addon.find('i'),
            state = false;

        if (!group.data('validate'))
            state = $(this).val() ? true : false;
        else if (group.data('validate') == "email") // RFC 5322 (Internet Message Format) Regex type="email" use regex too
            state = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test($(this).val())
        else if (group.data('validate') == "string")
            state = /^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\.\-]{2,50}$/i.test($(this).val())
        else if (group.data('validate') == "number-genre")
            state = /1|2/.test($(this).val())

        if (state) {
            addon.removeClass('danger');
            addon.addClass('success');
            icon.attr('class', 'fas fa-check');
        } else {
            addon.removeClass('success');
            addon.addClass('danger');
            icon.attr('class', 'fas fa-times');
        }

        if (form.find('.input-group-addon.danger').length == 0) {
            form.find('[name="submit"]').prop('disabled', false);
        } else {
            form.find('[name="submit"]').prop('disabled', true);
        }
    });

    $('.input-group input[required], .input-group textarea[required], .input-group select[required]').trigger('change');
});

/*
$.extend($.validator, { // For french Error with jQuery
            messages: {
                required: "Ce champ est requis.",
                remote: "Merci corriger ce champ.",
                email: "Merci de mettre un E-Mail valide."
            }
        })

        jQuery.validator.setDefaults({
            success: "valid"
        })

        $("#contactForm").validate({
            rules: {
                fname: {
                    required: true,
                    normalizer: function (value) {
                        return $.trim(value)
                    }
                },
                sname: {
                    required: true,
                    normalizer: function (value) {
                        return $.trim(value)
                    }
                },
                email: {
                    required: true,
                    email: true,
                    normalizer: function (value) {
                        return $.trim(value)
                    }
                },
                country: {
                    required: true,
                    normalizer: function (value) {
                        return $.trim(value)
                    }
                },
                message: {
                    required: true,
                    normalizer: function (value) {
                        return $.trim(value)
                    }
                },
                genre: {
                    required: true,
                    normalizer: function (value) {
                        if (value == 1 || value == 2)
                            return $.trim(value)
                    }
                },
                check1: {
                    required: false,
                    normalizer: function (value) {
                        if (value == 0 || value == 1)
                            return $.trim(value)
                    }
                },
                check2: {
                    required: false,
                    normalizer: function (value) {
                        if (value == 0 || value == 1)
                            return $.trim(value)
                    }
                },
                check3: {
                    required: false,
                    normalizer: function (value) {
                        if (value == 0 || value == 1)
                            return $.trim(value)
                    }
                }
            }
        })
        */

/*
let error = 0

$("#submit").click(function() {
    let champs = ['#fname', '#sname', '#email', '#country', '#message', '#genre'] // Or new Array()
    error = 0

    champs.forEach(element => {
        //$(element).removeClass('error')

        if ($(element).is(':empty')) {
            //$(element).addClass('error')
            error += 1
        }

        if (element == "#email" && $(element).val )
            $(element).addClass('error')
    })

})
*/