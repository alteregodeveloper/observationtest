$(function () {
    if($('#exampleModal').length > 0) {
        $('#exampleModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    }
    
    if($('#categoryModal').length > 0) {
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
    }    

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

    if ($('#start-test').length > 0) {
        run_test()
    }
})


function add_answer() {
    $('.addanswer').unbind().on('click', function (ev) {
        ev.preventDefault()
        var elementNumber = parseInt($(this).data('element')) + 1
        if (elementNumber === 2) {
            $(this).hide()
        } else {
            $(this).remove()
        }
        $('#answers').append('<div class="row mb-3 remove-answer"><div class="col-10"><div class="input-group mb-3"><div class="input-group-prepend"><div class="input-group-text"><input type="checkbox" name="correctansw_' + elementNumber + '" aria-label="Checked is the correct answer"></div></div><input type="text" class="form-control" name="answ_' + elementNumber + '" aria-label="Text input with checkbox"></div></div><div class="col-1"><a href="" class="btn btn-primary addanswer" title="Add new answer" data-element="' + elementNumber + '"><i class="fas fa-plus"></i></a></div></div>')
        $('input[name="answ_' + elementNumber + '"]').focus()
        add_answer()
    })
}

function post_question() {
    var questionText = ''
    $('.addquestion').on('click', function (ev) {
        ev.preventDefault()
        questionText = $('textarea[name="question"]').val()
        $.ajax({
                method: 'POST',
                url: window.location.href,
                dataType: 'JSON',
                data: {
                    caseid: $('input[name="caseid"]').val(),
                    question: questionText,
                    action: 'addquestion'
                }
            })
            .done(function (data) {
                var questionStatus = data.status
                $('#question .alert.hide').html(data.message)
                $('#question .alert.hide').addClass('alert-' + questionStatus)
                $('#question .alert.hide').removeClass('hide')
                if (questionStatus === 'success') {
                    $('#question-container').append('<div class="row align-items-center p-1 bg-light mb-3 rounded"><div class="col">' + questionText + '</div><div class="col anserws"></div></div>');
                    $('textarea[name="question"]').val('')
                    var answersNumber = 0
                    $('.input-group.mb-3').each(function (index) {
                        var correct = $(this).find('input[type="checkbox"]')
                        var intro = $(this).find('input[type="text"]')
                        var correctValue = 0
                        var introValue = intro.val()

                        if (introValue !== '') {
                            $(this).find('input[type="text"]').val('')
                            if (correct.prop("checked") == true) {
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
                                        var anwserText = ''
                                        if (correctValue) {
                                            anwserText = '<div class="row m-1 p-2 bg-success rounded text-white">' + introValue + '</div>'
                                        } else {
                                            anwserText = '<div class="row m-1 p-2 border-bottom">' + introValue + '</div>'
                                        }
                                        $('.row.align-items-center.p-1.bg-light.mb-3.rounded').last().find('.anserws').append(anwserText)
                                        answersNumber++
                                    } else {
                                        $('#question .alert').html(data.message + answersNumber)
                                        $('#question .alert').removeClass('alert-' + questionStatus)
                                        $('#question .alert.hide').addClass('alert-' + data.status)
                                        return false
                                    }
                                })
                        }
                    })
                    $('.remove-answer').remove()
                    $('.addanswer').show()
                }
                setTimeout(function () {
                    $('#question .alert').html('')
                    $('#question .alert').addClass('hide')
                    $('#question .alert').removeClass('alert-' + questionStatus)
                }, 2000)
            })
    })
}

function run_test() {
    $('#start-test').on('click', function (ev) {
        ev.preventDefault()
        $(this).closest('.row').addClass('hide')
        $('#case-content').removeClass('hide')
        var timeOut = $('input[name="complexity"]').val() * 1000
        setTimeout(function () {
            $('#case-content').addClass('hide')
            $('#case-questions').removeClass('hide')
            set_radio()
            check_test()
        }, timeOut)
    })
}

function set_radio() {
    $('.answer').unbind().on('click', function (ev) {
        ev.preventDefault()
        $(this).closest('.question').find('input.quiz-value').val($(this).data('correct'))
        $(this).closest('.question').find('i.fa-check-square').addClass('fa-square')
        $(this).closest('.question').find('i.fa-check-square').removeClass('fa-check-square')
        $(this).find('i').removeClass('fa-square')
        $(this).find('i').addClass('fa-check-square')
    })
}

function check_test() {
    $('#case-questions form').on('submit', function (ev) {
        ev.preventDefault()
        var quizResult = 0
        $('input.quiz-value').each(function (i) {
            quizResult += parseInt($(this).val())
        })
        var resultQuiz = (quizResult * 10) / ($('input.quiz-value').length)
        $.ajax({
                method: 'POST',
                url: window.location.href,
                dataType: 'JSON',
                data: {
                    testid: $('input[name="testid"]').val(),
                    caseid: $('input[name="caseid"]').val(),
                    exercise: $('input[name="exercise"]').val(),
                    result: resultQuiz,
                    action: 'addresult'
                }
            })
            .done(function (data) {
                if (data.status === 'success') {
                    var alertColor = '';
                    if (parseInt(data.result) < 6) {
                        alertColor = 'alert-danger';
                    } else if (parseInt(data.result) >= 6 && parseInt(data.result) < 8) {
                        alertColor = 'alert-warning';
                    } else if (parseInt(data.result) > 8) {
                        alertColor = 'alert-success';
                    }
                    $('#case-questions').find('.alert').addClass(alertColor);
                    $('#case-questions').find('.alert').html(data.message)
                    $('#case-questions').find('.alert').removeClass('hide')
                    $('#case-questions button').remove()
                } else {
                    $('#case-questions').find('.alert').addClass(data.status);
                    $('#case-questions').find('.alert').html(data.message);
                }
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
            })
    })
}