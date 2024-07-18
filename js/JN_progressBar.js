function startProgress() {
            
    __adianti_block_ui();
        
    $('#adianti_div_content').prepend('<div id="progress-container" style="display: none;width: 100%; border: 1px solid #ccc; padding: 3px; border-radius: 5px;">' +
    '<div id="progress-bar" style="width: 0%; background: #4caf50; text-align: center; padding: 5px; color: white; border-radius: 5px;">' +
    '</div></div>');
    
    $('#progress-container').fadeIn();

    const eventSource = new EventSource('engine.php?class=' + JN_progessBar_classe + '&method=' + JN_progessBar_metodo + '&p_b64=' + p_b64 + '&sse_enabled=1');

    eventSource.onmessage = function (event) {
        const data = JSON.parse(event.data);
        console.log(data);
        $('#progress-container').html(data.html);
        if(data.percentage > 100){
            // $('#progress-container').remove();
            $('#progress-bar').fadeOut().parent().fadeOut();
            $('#progress-container').fadeOut();
            __adianti_unblock_ui();
            eventSource.close();
            if(data.onFinish){
                eval(data.onFinish);
            }
        }
    };
    
    eventSource.onerror = function (event) {
        __adianti_unblock_ui();
        eventSource.close();
    };
}
