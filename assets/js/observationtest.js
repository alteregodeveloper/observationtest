$(function () {
    $('#exampleModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('#categoryModal #add-category').on('click', function(ev) {
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
        .done(function(data){
            $('.alert.hide').html(data.message)
            $('.alert.hide').addClass('alert-' + data.status)
            $('.alert.hide').removeClass('hide')
            if(data.status === 'success') {
                $('#category').append('<option value="' + data.idcategory + '" selected>' + data.category + '</option>')
            }
            setTimeout(function(){
                $('#categoryModal .btn.btn-secondary').trigger('click')
            }, 1000)
        })
    })

    if($('#newCase').length > 0) {
        $('select[name="category"]').on('change', function() {
            $('input[name="categoryname"]').val($(this).find(":selected").text());
        })
        $('select[name="complexity"]').on('change', function() {
            $('input[name="complexityname"]').val($(this).find(":selected").text());
        })
    }
})