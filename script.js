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
})