<?php

class JNP
{
    
    private static $router;
    private static $request_id;
    private static $debug;
    
    public function __construct($param)
    {
        
    }
    
    public static function myFunction($param)
    {
        
    }
    
    // Carrega a pÃ¡gina sem alterar a URL do navegador
    public static function jnp_load_page($class, $method = NULL, $parameters = NULL){

        // Esta funÃ§Ã£o Ã© uma cÃ³pia modificada de AdiantiCoreApplication::loadPage localizada sob link https://www.adianti.com.br/apis-framework_fsource_core__coreAdiantiCoreApplication.php.html#a304 (os devidos crÃ©ditos ao autor)
        
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, $parameters);
        // A funÃ§Ã£o JS existe no js em app/lib/include
        TScript::create("
        
            __jnp_load_page_noRegister('{$query}');
        
        ");
        
    }
    
    public static function tentryInt($obj_tentry){
        $obj_tentry->id = "tentry_libUtil_" . uniqid();
        $obj_tentry->inputmode = "numeric";
        TScript::create("
        
            $('#{$obj_tentry->id}').mask('000.000.000.000', {
                reverse: true
            });
        
            $('#{$obj_tentry->id}').css('text-align', 'right');
            
            $('#{$obj_tentry->id}').on('click', function() {
                var len = $(this).val().length;
                $(this)[0].setSelectionRange(len, len);
            });
        
        ");
        return $obj_tentry->id;
    }
    
    public static function tentryFloat($obj_tentry, $precisao = 2){
        $obj_tentry->id = "tentry_libUtil_" . uniqid();
        $obj_tentry->inputmode = "numeric";
        
        $decimal_mask = str_repeat("0", 3); // Mascara para casas decimais
        if ($precisao > 0) {
            $decimal_mask = "0," . str_repeat("0", $precisao); // Ajusta a mÃ¡scara para casas decimais conforme precisÃ£o
        }
    
        TScript::create("
                $('#{$obj_tentry->id}').mask('000.000.000.000" . $decimal_mask . "', {
                    reverse: true
                });
                
                $('#{$obj_tentry->id}').css('text-align', 'right');
    
                $('#{$obj_tentry->id}').on('click', function() {
                    var len = $(this).val().length;
                    $(this)[0].setSelectionRange(len, len);
                });
                
        ");
    
        return $obj_tentry->id;
    }
    
    public static function converterData($data, $formatoEntrada, $formatoSaida, $includeTime = false) {
        // Cria um objeto DateTime a partir da string de data e do formato de entrada
        $dataObjeto = DateTime::createFromFormat($formatoEntrada, $data);
        
        // Verifica se a data é válida
        if (!$dataObjeto) {
            return false;  // Retorna falso se a data for inválida
        }
    
        // Adiciona horário e fuso horário se necessário
        if ($includeTime) {
            $dataObjeto->setTime(0, 0, 0); // Define o horário para meia-noite, por exemplo
        }
    
        // Retorna a data no formato solicitado
        return $dataObjeto->format($formatoSaida);
    }

    
    public static function consoleJson($param){
        if(is_array($param)){
            $param['array'] = $param;
        }
        if(is_object($param)){
            $param->object = $param;
        }
        if(is_array($param) or is_object($param)){
            $jsonParam = json_encode($param);
        } else {
            $jsonParam['json'] = $param;
        }
        self::consoleLog($jsonParam);
    }
    
    public static function consoleLog($log){
        if(is_array($log) or is_object($log)){
            TScript::create("
    
                console.log($log);
    
            ");
        } else {
            $log = base64_encode( $log );
            
            TScript::create("
    
                console.log(atob('$log'));
    
            ");
        }
        
    }
    
    public static function isMobile() {
        $is_mobile = false;
 
        //Se tiver em branco, não é mobile
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;
 
        //Senão, se encontrar alguma das expressões abaixo, será mobile
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
                $is_mobile = true;
 
        //Senão encontrar nada, não será mobile
        } else {
            $is_mobile = false;
        }
 
        return $is_mobile;
    }
    
    public static function UUID() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante is DCE 1.1, ISO/IEC 11578:1996
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    public static function is_uuid($uuid)
    {
        // Regex para validar UUID no formato padrão (versão 1-5)
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return (bool) preg_match($pattern, $uuid);
    }
    
    public static function mask_CPF_CNPJ($formName, $name) {
        TScript::create("
            function JNP_CHANGE_MASK_CPFCNPJ(element) {
                    var cpfcnpj = $(element).val().replace(/[^0-9]/g, '');
                    
                    var mask = (cpfcnpj.length > 11) ? '00.000.000/0000-00' : '0000000.000.000-00';
                    
                    $(element).mask(mask, { reverse: true });
            }
    
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {
                JNP_CHANGE_MASK_CPFCNPJ(this);
            });
    
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input paste', function() {
                JNP_CHANGE_MASK_CPFCNPJ(this);
            });
        ");
    }



    
    public static function mask_fone_br($formName, $name){
        TScript::create("
            function JNP_APPLY_MASK_FONE(element) {
                var numero = $(element).val().replace(/\D/g,'');
                
                var newMask = (numero.length <= 10) ? '(99) 9999-999999' : '(99) 99999-9999';
    
                $(element).mask(newMask);
    
                $(element).val($(element).masked(numero));
            }
    
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {
                JNP_APPLY_MASK_FONE(this);
            });
    
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input', function() {
                JNP_APPLY_MASK_FONE(this);
            });
        ");
    }

    
    public static function registerURL($url){
        TScript::create("

            __adianti_register_state(\"{$url}\", \"adianti\");

        ");
    }
    
    public static function get_URL_atual($returnArray = false){
        
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
        
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        
        if($returnArray === true){
            return [
                'protocolo' => $protocolo,
                'host' => $host,
                'uri' => $uri,
            ];
        }
        
        $url_completa = "{$protocolo}://{$host}{$uri}";
        
        return $url_completa;
        
    }
    
    public static function open_blank($class, $method = 'onShow', $param = []){
        $cp_param = $param;
        unset($cp_param['class'], $cp_param['method']);
        
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, array_merge($cp_param, ['hideMenu' => 'true']));
        $query = str_replace('index.php', 'semMenu.php', $query);
        $query = str_replace('engine.php', 'semMenu.php', $query);
        
        TScript::create("
        
            var larguraTela = screen.width;
            var alturaTela = screen.height;
        
            __adianti_block_ui('carregando');
            var newWindow = window.open(
                '$query', 
                '_blank', 
                'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,' +
                'width=' + larguraTela + ',' +
                'height=' + alturaTela
            );
        
        ");
    }
    
    public static function logTxt($class){
        $directory = 'app/resources/log/';
        
        // Verifica se o diretório existe
        if (!is_dir($directory)) {
            // Cria o diretório, se não existir
            mkdir($directory, 0755, true); // 0755 é a permissão, 'true' cria diretórios recursivamente
        }
    
        // Define o logger
        TTransaction::setLogger(new TLoggerTXT($directory . 'log_' . $class . '_' . date('Ymd') . '.txt'));
    }
    
    public static function cpfCnpjHideParcial($value) {
        // Verificar se o valor está vazio
        if (!empty($value)) {
            // Remover todos os caracteres não numéricos
            $numeroLimpo = preg_replace("/[^0-9]/", "", $value);
            $tamanho = strlen($numeroLimpo);
    
            // Verificar se é CPF ou CNPJ
            if ($tamanho === 11) {
                // CPF
                $mascara = '###.###.###-##';
                $inicioVisivel = 3; // Primeiros 3 dígitos
                $fimVisivel = 2; // Últimos 2 dígitos
            } elseif ($tamanho === 14) {
                // CNPJ
                $mascara = '##.###.###/####-##';
                $inicioVisivel = 2; // Primeiros 2 dígitos
                $fimVisivel = 4; // Últimos 4 dígitos
            } else {
                return ''; // Retorna vazio se não for CPF nem CNPJ válido
            }
    
            // Obter início e fim visíveis
            $inicio = substr($numeroLimpo, 0, $inicioVisivel);
            $fim = substr($numeroLimpo, -$fimVisivel);
    
            // Ocultar o meio
            $meioOculto = str_repeat('#', $tamanho - $inicioVisivel - $fimVisivel);
    
            // Combinar início, meio oculto e fim
            $numeroOculto = $inicio . $meioOculto . $fim;
    
            // Aplicar a máscara
            $mascaraAtual = '';
            $indiceNumero = 0;
    
            for ($i = 0; $i < strlen($mascara); $i++) {
                if ($mascara[$i] === '#') {
                    if (isset($numeroOculto[$indiceNumero])) {
                        $mascaraAtual .= $numeroOculto[$indiceNumero];
                        $indiceNumero++;
                    }
                } else {
                    $mascaraAtual .= $mascara[$i];
                }
            }
    
            return $mascaraAtual;
        }
        return '';
    }
    
    public static function jnp_run($debug = FALSE)
    {
        $retorno = '';
        
        self::$request_id = uniqid();
        self::$debug = $debug;
        
        $ini = AdiantiApplicationConfig::get();
        $service = isset($ini['general']['request_log_service']) ? $ini['general']['request_log_service'] : '\SystemRequestLogService';
        $class   = isset($_REQUEST['class'])    ? $_REQUEST['class']   : '';
        $static  = isset($_REQUEST['static'])   ? $_REQUEST['static']  : '';
        $method  = isset($_REQUEST['method'])   ? $_REQUEST['method']  : '';
        
        $content = '';
        set_error_handler(array('AdiantiCoreApplication', 'errorHandler'));
        
        if (!empty($ini['general']['request_log']) && $ini['general']['request_log'] == '1')
        {
            if (empty($ini['general']['request_log_types']) || strpos($ini['general']['request_log_types'], 'web') !== false)
            {
                self::$request_id = $service::register( 'web');
            }
        }
        
        AdiantiCoreApplication::filterInput();
        
        $rc = new ReflectionClass($class);
        
        if (in_array(strtolower($class), array_map('strtolower', AdiantiClassMap::getInternalClasses()) ))
        {
            ob_start();
            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );
            $content = ob_get_contents();
            ob_end_clean();
        }
        else if (!$rc->isUserDefined())
        {
            ob_start();
            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );
            $content = ob_get_contents();
            ob_end_clean();
        }
        else if (class_exists($class))
        {
            if ($static)
            {
                $rf = new ReflectionMethod($class, $method);
                if ($rf-> isStatic ())
                {
                    call_user_func(array($class, $method), $_REQUEST);
                }
                else
                {
                    call_user_func(array(new $class($_REQUEST), $method), $_REQUEST);
                }
            }
            else
            {
                try
                {
                    $page = new $class( $_REQUEST );
                    
                    ob_start();
                    $page->show( $_REQUEST );
                    $content = ob_get_contents();
                    ob_end_clean();
                }
                catch (Exception $e)
                {
                    ob_start();
                    if ($debug)
                    {
                        new TExceptionView($e);
                        $content = ob_get_contents();
                    }
                    else
                    {
                        new TMessage('error', $e->getMessage());
                        $content = ob_get_contents();
                    }
                    ob_end_clean();
                }
                catch (Error $e)
                {
                    
                    ob_start();
                    if ($debug)
                    {
                        new TExceptionView($e);
                        $content = ob_get_contents();
                    }
                    else
                    {
                        new TMessage('error', $e->getMessage());
                        $content = ob_get_contents();
                    }
                    ob_end_clean();
                }
            }
        }
        else if (!empty($class))
        {
            new TMessage('error', AdiantiCoreTranslator::translate('Class ^1 not found', " <b><i><u>{$class}</u></i></b>") . '.<br>' . AdiantiCoreTranslator::translate('Check the class name or the file name').'.');
        }
        
        if (!$static)
        {
            $retorno .= TPage::getLoadedCSS();
        }
        $retorno .= TPage::getLoadedJS();
        
        $retorno .= $content;
        
        return $retorno;
    }
    
    
    
    // dump
    public static function d(...$args)
    {
        $bt = debug_backtrace();
        while(count($bt) and strpos($bt[0]['file'], __FILE__) !== false) array_shift($bt);
        $title = ['d' => 'DUMP', 'dd' => 'DUMP AND DIE', 'de' => 'DUMP AND END'][$bt[0]['function']] . " {$bt[0]['file']}:{$bt[0]['line']}";
        
        ob_start();
        foreach($args as $arg){
            echo "<pre>";
                $type = gettype($arg);
                $typeName = $type; // Assume o tipo primitivo inicialmente.
                
                // Cálculo de tamanho para os tipos.
                if ($type === 'object') {
                    $typeName = get_class($arg) . ' (object)';
                    $size = count((array)$arg);  // Conta o número de propriedades públicas do objeto.
                } elseif ($type === 'array') {
                    $typeName = 'Array (array)';
                    $size = count($arg); // Conta o número de elementos no array.
                } elseif ($type === 'string') {
                    $typeName = 'String';
                    $size = mb_strlen($arg); // Conta o número de caracteres na string.
                } elseif ($type === 'integer' || $type === 'double' || $type === 'boolean' || $type === 'NULL') {
                    // $size = serialize($arg); // Calcula o tamanho aproximado em bytes.
                    $size = false;
                }
        
                // Tratamento do output para cada tipo de dado.
                $output = var_export($arg, true);
                $txt_size = ($size !== false) ? "(size={$size})" : "";
                if ($type === 'object' || $type === 'array') {
                    $output = "{$typeName} {$txt_size}\n" . $output;
                } else {
                    $output = "{$typeName} {$txt_size} (" . $output . ")";
                }
                
                highlight_string("<?php\n" . $output . "\n?>");
                
            echo "</pre>";
        }
        // $win = TWindow::create($title, 0.9999, 0.9999);
        // $win->add(ob_get_clean());
        // $win->show();
        TSession::setValue('debugDump_string', base64_encode( ob_get_clean() ));
        self::open_blank('debugDump', 'onShow', [] /* ['dump' => base64_encode( ob_get_clean() )] */);
        TScript::create("__adianti_unblock_ui(); __adianti_unblock_ui();");
    }
    
    // dump and die
    public static function dd(...$args) {
        d(...$args);
        TScript::create("
        
            __adianti_unblock_ui();
            __adianti_unblock_ui();
        
        ");
        die();
    }
    
    // dump and exception
    public static function de(...$args) {
        d(...$args);
        TScript::create("
        
            __adianti_unblock_ui();
            __adianti_unblock_ui();
        
        ");
        throw new Exception("Stop");
    }
    
    public static function retornarNumeros($string)
    {
        // Utiliza expressão regular para manter apenas os números
        return preg_replace('/\D/', '', $string);
    }
    
    public static function verificaCriaPasta($pasta)
    {
        if (!file_exists($pasta)) {
            // Tenta criar a pasta e suas subpastas (se necessário)
            if (!mkdir($pasta, 0777, true)) {
                // error_log("Erro ao criar a pasta '$pasta'. Verifique as permissões.");
                return false;
            }
        }

        // Verifica se a pasta existe e é gravável
        if (!is_dir($pasta) || !is_writable($pasta)) {
            // error_log("A pasta '$pasta' não é gravável ou não existe.");
            return false;
        }

        return true;
    }
    
    public static function deletarPasta($dir) {
        if (!is_dir($dir)) {
            return;
        }
    
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                self::deletarPasta($path);
            } else {
                unlink($path);
            }
        }
    
        rmdir($dir);
    }
    
    public static function renderApplication($param)
    {
        // Salva as superglobais originais
        $original_request = $_REQUEST;
        $original_get = $_GET;
        $original_post = $_POST;
        
        // Configura $_REQUEST, $_GET e $_POST com os parâmetros necessários
        $_REQUEST = $param;
        $_GET = $param;
        $_POST = []; // Supondo que seja uma requisição GET
        
        // Inicia o buffer de saída
        ob_start();
        
        try
        {
            // Executa a aplicação para processar a requisição
            AdiantiCoreApplication::run();
        }
        catch (Exception $e)
        {
            ob_clean(); // Limpa qualquer saída anterior
            new TMessage('error', $e->getMessage());
        }
        
        // Captura o conteúdo do buffer
        $html = ob_get_contents();
        ob_end_clean();
        
        // Restaura as superglobais originais
        $_REQUEST = $original_request;
        $_GET = $original_get;
        $_POST = $original_post;
        
        // Retorna o HTML capturado
        return $html;
    }    
    public static function renomearAtributo(&$object, $atributoAntigo, $atributoNovo){
        if ($object->{$atributoAntigo} ?? false) {
            $object->{$atributoNovo} = $object->{$atributoAntigo};
            unset($object->{$atributoAntigo});
        }
    }
    
    public static function TEntry2TLabel($entrada)
    {
        // Captura o valor do TEntry
        $valor = $entrada->getValue();
        
        // Cria um TLabel com o valor capturado
        $label = new TLabel($valor);
        
        // Retorna o TLabel criado
        return $label;
    }

    public static function combinarArrayDinamico($arrays) {
        $resultado = array();

        $chaves = array_keys($arrays);
        $tamanho = count($arrays[$chaves[0]]);

        for ($i = 0; $i < $tamanho; $i++) {
            $item = array();
            foreach ($arrays as $chave => $valores) {
                $item[$chave] = $valores[$i];
            }
            $resultado[] = $item;
        }

        // LibUtil::meu_vardump($resultado);
        return $resultado;
    }

    public static function post_data($formName, $class, $method, $param = []){
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, $param);
        $query = str_replace('index.php', 'engine.php', $query);
        $command = "__jnp_post_data('{$formName}', '{$query}');";
        // echo $command;
        TScript::create($command, true, 1);
    }

    
    public static function combinarFormaValor($arrForma, $arrValor) {
        $resultado = array();

        foreach ($arrForma as $k => $v){
            $resultado[$k] = array(
                'tipo_pagamento_id' => $arrForma[$k],
                'valor_pago' => $arrValor[$k],
            );
        }
        return $resultado;
    }

    
    
}
