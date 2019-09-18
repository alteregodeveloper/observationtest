$(function () {
    $('#exampleModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('#categoryModal #add-category').on('click', function (ev) {
        ev.preventDefault()
        $.ajax({
                method: 'POST',
                url: window.location.href,
                dataType: 'JSON',
                data: {
                    category: $('#newcategory').val(),
                    action: 'addcategory'
                }
            })
            .done(function (data) {
                $('.alert.hide').html(data.message)
                $('.alert.hide').addClass('alert-' + data.status)
                $('.alert.hide').removeClass('hide')
                if (data.status === 'success') {
                    $('#category').append('<option value="' + data.idcategory + '" selected>' + data.category + '</option>')
                }
                setTimeout(function () {
                    $('#categoryModal .btn.btn-secondary').trigger('click')
                }, 1000)
            })
    })

    if ($('.alert-dismissible.fade.show').length > 0) {
        $('.alert-dismissible.fade.show').fadeOut(1500);
    }

    if ($('#newCase').length > 0) {
        $('select[name="category"]').on('change', function () {
            $('input[name="categoryname"]').val($(this).find(":selected").text())
        })
        $('select[name="complexity"]').on('change', function () {
            $('input[name="complexityname"]').val($(this).find(":selected").text())
        })
    }

    if ($('#newQuestion').length > 0) {
        add_answer()
        post_question()
    }
})


function add_answer() {
    $('.addanswer').on('click', function (ev) {
        ev.preventDefault()
        var elementNumber = parseInt($(this).data('element')) + 1
        $(this).remove();
        $('#answers').append('<div class="row mb-3"><div class="col-10"><div class="input-group mb-3"><div class="input-group-prepend"><div class="input-group-text"><input type="checkbox" name="correctansw_' + elementNumber + '" aria-label="Checked is the correct answer"></div></div><input type="text" class="form-control" name="answ_' + elementNumber + '" aria-label="Text input with checkbox"></div></div><div class="col-1"><a href="" class="btn btn-primary addanswer" title="Add new answer" data-element="' + elementNumber + '"><i class="fas fa-plus"></i></a></div></div>')
        $('input[name="answ_' + elementNumber + '"]').focus()
        add_answer()
    })
}

function post_question() {
    $('.addquestion').on('click', function (ev) {
        ev.preventDefault()
        $.ajax({
                method: 'POST',
                url: window.location.href,
                dataType: 'JSON',
                data: {
                    caseid: $('input[name="caseid"]').val(),
                    question: $('textarea[name="question"]').val(),
                    action: 'addquestion'
                }
            })
            .done(function (data) {
                var questionStatus = data.status
                $('#question .alert.hide').html(data.message)
                $('#question .alert.hide').addClass('alert-' + questionStatus)
                $('#question .alert.hide').removeClass('hide')
                if (questionStatus === 'success') {
                    $('textarea[name="question"]').val('')
                    var answersNumber = 0
                    $('.input-group.mb-3').each(function (index) {
                        var correct = $(this).find('input[type="checkbox"]')
                        var intro = $(this).find('input[type="text"]')
                        var correctValue = 0
                        var introValue = intro.val()
                        
                        $(this).find('input[type="text"]').val('')
                        if(correct.prop("checked") == true) {
                            correctValue = 1
                            correct.prop('checked', false);
                        }
                        $.ajax({
                                method: 'POST',
                                url: window.location.href,
                                dataType: 'JSON',
                                data: {
                                    questionid: data.questionid,
                                    correct: correctValue,
                                    intro: introValue,
                                    action: 'addanswer'
                                }
                            })
                            .done(function (data) {
                                if (data.status === 'success') {
                                    answersNumber ++
                                } else {
                                    $('#question .alert').html('An error occurred while saving the answers. It was only possible to store the first ' + answersNumber)
                                    $('#question .alert').removeClass('alert-' + questionStatus)
                                    $('#question .alert.hide').addClass('alert-' + data.status)
                                    return false
                                }
                            })
                        answersNumber ++
                    })
                    console.log('Answers: ' + answersNumber)
                }
                setTimeout(function () {
                    $('#question .alert').html('')
                    $('#question .alert').addClass('hide')
                    $('#question .alert').removeClass('alert-' + questionStatus)
                }, 2000)
            })
    })
}