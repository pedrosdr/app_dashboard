$(document).ready(() => {
	
    $('#documentacao').on('click', e => {
        //$('#pagina').load('documentacao.html')
        $.get('documentacao.html', responseText => {
            $('#pagina').html(responseText)
        })
    })

    $('#suporte').on('click', e => {
        //$('#pagina').load('suporte.html')
        $.post('suporte.html', responseText => {
            $('#pagina').html(responseText)
        })
    })

    $("#competencia").on('change', e => {
        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: 'competencia=' + $(e.target).val(),
            dataType: 'json',
            success: dados => {
                $('#numero-vendas').html(dados.numeroVendas)
                $('#total-vendas').html(dados.totalVendas)
            },
            error: erro => console.log('requisição mal sucedida: ' + erro)
        })
    })
})

