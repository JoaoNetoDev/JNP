<?php<?php

class JNPclass JNP
{{
        
    private static $router;    private static $router;
    private static $request_id;    private static $request_id;
    private static $debug;    private static $debug;
        
    public function __construct($param)    public function __construct($param)
    {    {
                
    }    }
        
    public static function myFunction($param)    public static function myFunction($param)
    {    {
                
    }    }
        
    // Carrega a pÃ¡gina sem alterar a URL do navegador    // Carrega a pÃ¡gina sem alterar a URL do navegador
    public static function jnp_load_page($class, $method = NULL, $parameters = NULL){    public static function jnp_load_page($class, $method = NULL, $parameters = NULL){

        // Esta funÃ§Ã£o Ã© uma cÃ³pia modificada de AdiantiCoreApplication::loadPage localizada sob link https://www.adianti.com.br/apis-framework_fsource_core__coreAdiantiCoreApplication.php.html#a304 (os devidos crÃ©ditos ao autor)        // Esta funÃ§Ã£o Ã© uma cÃ³pia modificada de AdiantiCoreApplication::loadPage localizada sob link https://www.adianti.com.br/apis-framework_fsource_core__coreAdiantiCoreApplication.php.html#a304 (os devidos crÃ©ditos ao autor)
                
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, $parameters);        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, $parameters);
        // A funÃ§Ã£o JS existe no js em app/lib/include        // A funÃ§Ã£o JS existe no js em app/lib/include
        TScript::create("        TScript::create("
                
            __jnp_load_page_noRegister('{$query}');            __jnp_load_page_noRegister('{$query}');
                
        ");        ");
                
    }    }
        
    public static function tentryInt($obj_tentry){    public static function tentryInt($obj_tentry){
        $obj_tentry->id = "tentry_libUtil_" . uniqid();        $obj_tentry->id = "tentry_libUtil_" . uniqid();
        $obj_tentry->inputmode = "numeric";        $obj_tentry->inputmode = "numeric";
        TScript::create("        TScript::create("
                
            $('#{$obj_tentry->id}').mask('000.000.000.000', {            $('#{$obj_tentry->id}').mask('000.000.000.000', {
                reverse: true                reverse: true
            });            });
                
            $('#{$obj_tentry->id}').css('text-align', 'right');            $('#{$obj_tentry->id}').css('text-align', 'right');
                        
            $('#{$obj_tentry->id}').on('click', function() {            $('#{$obj_tentry->id}').on('click', function() {
                var len = $(this).val().length;                var len = $(this).val().length;
                $(this)[0].setSelectionRange(len, len);                $(this)[0].setSelectionRange(len, len);
            });            });
                
        ");        ");
        return $obj_tentry->id;        return $obj_tentry->id;
    }    }
        
    public static function tentryFloat($obj_tentry, $precisao = 2){    public static function tentryFloat($obj_tentry, $precisao = 2){
        $obj_tentry->id = "tentry_libUtil_" . uniqid();        $obj_tentry->id = "tentry_libUtil_" . uniqid();
        $obj_tentry->inputmode = "numeric";        $obj_tentry->inputmode = "numeric";
                
        $decimal_mask = str_repeat("0", 3); // Mascara para casas decimais        $decimal_mask = str_repeat("0", 3); // Mascara para casas decimais
        if ($precisao > 0) {        if ($precisao > 0) {
            $decimal_mask = "0," . str_repeat("0", $precisao); // Ajusta a mÃ¡scara para casas decimais conforme precisÃ£o            $decimal_mask = "0," . str_repeat("0", $precisao); // Ajusta a mÃ¡scara para casas decimais conforme precisÃ£o
        }        }
        
        TScript::create("        TScript::create("
                $('#{$obj_tentry->id}').mask('000.000.000.000" . $decimal_mask . "', {                $('#{$obj_tentry->id}').mask('000.000.000.000" . $decimal_mask . "', {
                    reverse: true                    reverse: true
                });                });
                                
                $('#{$obj_tentry->id}').css('text-align', 'right');                $('#{$obj_tentry->id}').css('text-align', 'right');
        
                $('#{$obj_tentry->id}').on('click', function() {                $('#{$obj_tentry->id}').on('click', function() {
                    var len = $(this).val().length;                    var len = $(this).val().length;
                    $(this)[0].setSelectionRange(len, len);                    $(this)[0].setSelectionRange(len, len);
                });                });
                                
        ");        ");
        
        return $obj_tentry->id;        return $obj_tentry->id;
    }    }
        
    public static function converterData($data, $formatoEntrada, $formatoSaida, $includeTime = false) {    public static function converterData($data, $formatoEntrada, $formatoSaida) {
        // Cria um objeto DateTime a partir da string de data e do formato de entrada        // Cria um objeto DateTime a partir da string de data e do formato de entrada
        $dataObjeto = DateTime::createFromFormat($formatoEntrada, $data);        $dataObjeto = DateTime::createFromFormat($formatoEntrada, $data);
            
        // Verifica se a data é válida        // Verifica se a data é válida
        if (!$dataObjeto) {        if (!$dataObjeto) {
            return false;  // Retorna falso se a data for inválida            return false;  // Retorna falso se a data for inválida
        }        }
        
        // Adiciona horário e fuso horário se necessário        // Formata a data para o novo formato e retorna
        if ($includeTime) {        return $dataObjeto->format($formatoSaida);
            $dataObjeto->setTime(0, 0, 0); // Define o horário para meia-noite, por exemplo    }
        }    
        // Valida se é uma data ou datetime
        // Retorna a data no formato solicitado    public static function isDateOrDateTime($value): bool
        return $dataObjeto->format($formatoSaida);    {
    }        return self::isDate($value) || self::isDateTime($value);
    }
    
    public static function consoleJson($param){    public static function isDate($value): bool
        if(is_array($param)){    {
            $param['array'] = $param;        $format = 'Y-m-d';
        }        $dateTime = DateTime::createFromFormat($format, $value);
        if(is_object($param)){        return $dateTime && $dateTime->format($format) === $value;
            $param->object = $param;    }
        }
        if(is_array($param) or is_object($param)){    public static function isDateTime($value): bool
            $jsonParam = json_encode($param);    {
        } else {        $format = 'Y-m-d H:i:s';
            $jsonParam['json'] = $param;        $dateTime = DateTime::createFromFormat($format, $value);
        }        return $dateTime && $dateTime->format($format) === $value;
        self::consoleLog($jsonParam);    }
    }    
        public static function consoleJson($param){
    public static function consoleLog($log){        if(is_array($param)){
        if(is_array($log) or is_object($log)){            $param['array'] = $param;
            TScript::create("        }
            if(is_object($param)){
                console.log($log);            $param->object = $param;
            }
            ");        if(is_array($param) or is_object($param)){
        } else {            $jsonParam = json_encode($param);
            $log = base64_encode( $log );        } else {
                        $jsonParam['json'] = $param;
            TScript::create("        }
            self::consoleLog($jsonParam);
                console.log(atob('$log'));    }
        
            ");    public static function consoleLog($log){
        }        if(is_array($log) or is_object($log)){
                    TScript::create("
    }    
                    console.log($log);
    public static function isMobile() {    
        $is_mobile = false;            ");
         } else {
        //Se tiver em branco, não é mobile            $log = base64_encode( $log );
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {            
            $is_mobile = false;            TScript::create("
     
        //Senão, se encontrar alguma das expressões abaixo, será mobile                console.log(atob('$log'));
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false    
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false            ");
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false        }
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false        
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false    }
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false    
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {    public static function isMobile() {
                $is_mobile = true;        $is_mobile = false;
  
        //Senão encontrar nada, não será mobile        //Se tiver em branco, não é mobile
        } else {        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;            $is_mobile = false;
        } 
         //Senão, se encontrar alguma das expressões abaixo, será mobile
        return $is_mobile;        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
    }            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
                || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
    public static function UUID() {            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        $data = random_bytes(16);            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante is DCE 1.1, ISO/IEC 11578:1996            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));                $is_mobile = true;
    } 
            //Senão encontrar nada, não será mobile
    public static function is_uuid($uuid)        } else {
    {            $is_mobile = false;
        // Regex para validar UUID no formato padrão (versão 1-5)        }
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i'; 
        return (bool) preg_match($pattern, $uuid);        return $is_mobile;
    }    }
        
    public static function mask_CPF_CNPJ($formName, $name) {    public static function UUID() {
        TScript::create("        $data = random_bytes(16);
            function JNP_CHANGE_MASK_CPFCNPJ(element) {        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4
                    var cpfcnpj = $(element).val().replace(/[^0-9]/g, '');        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante is DCE 1.1, ISO/IEC 11578:1996
                            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
                    var mask = (cpfcnpj.length > 11) ? '00.000.000/0000-00' : '0000000.000.000-00';    }
                        
                    $(element).mask(mask, { reverse: true });    public static function is_uuid($uuid)
            }    {
            // Regex para validar UUID no formato padrão (versão 1-5)
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
                JNP_CHANGE_MASK_CPFCNPJ(this);        return (bool) preg_match($pattern, $uuid);
            });    }
        
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input paste', function() {    public static function mask_CPF_CNPJ($formName, $name) {
                JNP_CHANGE_MASK_CPFCNPJ(this);        TScript::create("
            });            function JNP_CHANGE_MASK_CPFCNPJ(element) {
        ");                    var cpfcnpj = $(element).val().replace(/[^0-9]/g, '');
    }                    
                    var mask = (cpfcnpj.length > 11) ? '00.000.000/0000-00' : '0000000.000.000-00';
                    
                    $(element).mask(mask, { reverse: true });
                }
    public static function mask_fone_br($formName, $name){    
        TScript::create("            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {
            function JNP_APPLY_MASK_FONE(element) {                JNP_CHANGE_MASK_CPFCNPJ(this);
                var numero = $(element).val().replace(/\D/g,'');            });
                    
                var newMask = (numero.length <= 10) ? '(99) 9999-999999' : '(99) 99999-9999';            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input paste', function() {
                    JNP_CHANGE_MASK_CPFCNPJ(this);
                $(element).mask(newMask);            });
            ");
                $(element).val($(element).masked(numero));    }
            }
    
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {
                JNP_APPLY_MASK_FONE(this);    
            });    public static function mask_fone_br($formName, $name){
            TScript::create("
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input', function() {            function JNP_APPLY_MASK_FONE(element) {
                JNP_APPLY_MASK_FONE(this);                var numero = $(element).val().replace(/\D/g,'');
            });                
        ");                var newMask = (numero.length <= 10) ? '(99) 9999-999999' : '(99) 99999-9999';
    }    
                $(element).mask(newMask);
        
    public static function registerURL($url){                $(element).val($(element).masked(numero));
        TScript::create("            }
    
            __adianti_register_state(\"{$url}\", \"adianti\");            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() {
                JNP_APPLY_MASK_FONE(this);
        ");            });
    }    
                $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input', function() {
    public static function get_URL_atual($returnArray = false){                JNP_APPLY_MASK_FONE(this);
                    });
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";        ");
            }
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];    
            public static function registerURL($url){
        if($returnArray === true){        TScript::create("
            return [
                'protocolo' => $protocolo,            __adianti_register_state(\"{$url}\", \"adianti\");
                'host' => $host,
                'uri' => $uri,        ");
            ];    }
        }    
            public static function get_URL_atual($returnArray = false){
        $url_completa = "{$protocolo}://{$host}{$uri}";        
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
        return $url_completa;        
                $host = $_SERVER['HTTP_HOST'];
    }        $uri = $_SERVER['REQUEST_URI'];
            
    public static function open_blank($class, $method = 'onShow', $param = []){        if($returnArray === true){
        $cp_param = $param;            return [
        unset($cp_param['class'], $cp_param['method']);                'protocolo' => $protocolo,
                        'host' => $host,
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, array_merge($cp_param, ['hideMenu' => 'true']));                'uri' => $uri,
        $query = str_replace('index.php', 'semMenu.php', $query);            ];
        $query = str_replace('engine.php', 'semMenu.php', $query);        }
                
        TScript::create("        $url_completa = "{$protocolo}://{$host}{$uri}";
                
            var larguraTela = screen.width;        return $url_completa;
            var alturaTela = screen.height;        
            }
            __adianti_block_ui('carregando');    
            var newWindow = window.open(    public static function open_blank($class, $method = 'onShow', $param = []){
                '$query',         $cp_param = $param;
                '_blank',         unset($cp_param['class'], $cp_param['method']);
                'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,' +        
                'width=' + larguraTela + ',' +        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, array_merge($cp_param, ['hideMenu' => 'true']));
                'height=' + alturaTela        $query = str_replace('index.php', 'semMenu.php', $query);
            );        $query = str_replace('engine.php', 'semMenu.php', $query);
                
        ");        TScript::create("
    }        
                var larguraTela = screen.width;
    public static function logTxt($class){            var alturaTela = screen.height;
        $directory = 'app/resources/log/';        
                    __adianti_block_ui('carregando');
        // Verifica se o diretório existe            var newWindow = window.open(
        if (!is_dir($directory)) {                '$query', 
            // Cria o diretório, se não existir                '_blank', 
            mkdir($directory, 0755, true); // 0755 é a permissão, 'true' cria diretórios recursivamente                'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,' +
        }                'width=' + larguraTela + ',' +
                    'height=' + alturaTela
        // Define o logger            );
        TTransaction::setLogger(new TLoggerTXT($directory . 'log_' . $class . '_' . date('Ymd') . '.txt'));        
    }        ");
        }
    public static function cpfCnpjHideParcial($value) {    
        // Verificar se o valor está vazio    public static function logTxt($class){
        if (!empty($value)) {        $directory = 'app/resources/log/';
            // Remover todos os caracteres não numéricos        
            $numeroLimpo = preg_replace("/[^0-9]/", "", $value);        // Verifica se o diretório existe
            $tamanho = strlen($numeroLimpo);        if (!is_dir($directory)) {
                // Cria o diretório, se não existir
            // Verificar se é CPF ou CNPJ            mkdir($directory, 0755, true); // 0755 é a permissão, 'true' cria diretórios recursivamente
            if ($tamanho === 11) {        }
                // CPF    
                $mascara = '###.###.###-##';        // Define o logger
                $inicioVisivel = 3; // Primeiros 3 dígitos        TTransaction::setLogger(new TLoggerTXT($directory . 'log_' . $class . '_' . date('Ymd') . '.txt'));
                $fimVisivel = 2; // Últimos 2 dígitos    }
            } elseif ($tamanho === 14) {    
                // CNPJ    public static function cpfCnpjHideParcial($value) {
                $mascara = '##.###.###/####-##';        // Verificar se o valor está vazio
                $inicioVisivel = 2; // Primeiros 2 dígitos        if (!empty($value)) {
                $fimVisivel = 4; // Últimos 4 dígitos            // Remover todos os caracteres não numéricos
            } else {            $numeroLimpo = preg_replace("/[^0-9]/", "", $value);
                return ''; // Retorna vazio se não for CPF nem CNPJ válido            $tamanho = strlen($numeroLimpo);
            }    
                // Verificar se é CPF ou CNPJ
            // Obter início e fim visíveis            if ($tamanho === 11) {
            $inicio = substr($numeroLimpo, 0, $inicioVisivel);                // CPF
            $fim = substr($numeroLimpo, -$fimVisivel);                $mascara = '###.###.###-##';
                    $inicioVisivel = 3; // Primeiros 3 dígitos
            // Ocultar o meio                $fimVisivel = 2; // Últimos 2 dígitos
            $meioOculto = str_repeat('#', $tamanho - $inicioVisivel - $fimVisivel);            } elseif ($tamanho === 14) {
                    // CNPJ
            // Combinar início, meio oculto e fim                $mascara = '##.###.###/####-##';
            $numeroOculto = $inicio . $meioOculto . $fim;                $inicioVisivel = 2; // Primeiros 2 dígitos
                    $fimVisivel = 4; // Últimos 4 dígitos
            // Aplicar a máscara            } else {
            $mascaraAtual = '';                return ''; // Retorna vazio se não for CPF nem CNPJ válido
            $indiceNumero = 0;            }
        
            for ($i = 0; $i < strlen($mascara); $i++) {            // Obter início e fim visíveis
                if ($mascara[$i] === '#') {            $inicio = substr($numeroLimpo, 0, $inicioVisivel);
                    if (isset($numeroOculto[$indiceNumero])) {            $fim = substr($numeroLimpo, -$fimVisivel);
                        $mascaraAtual .= $numeroOculto[$indiceNumero];    
                        $indiceNumero++;            // Ocultar o meio
                    }            $meioOculto = str_repeat('#', $tamanho - $inicioVisivel - $fimVisivel);
                } else {    
                    $mascaraAtual .= $mascara[$i];            // Combinar início, meio oculto e fim
                }            $numeroOculto = $inicio . $meioOculto . $fim;
            }    
                // Aplicar a máscara
            return $mascaraAtual;            $mascaraAtual = '';
        }            $indiceNumero = 0;
        return '';    
    }            for ($i = 0; $i < strlen($mascara); $i++) {
                    if ($mascara[$i] === '#') {
    public static function jnp_run($debug = FALSE)                    if (isset($numeroOculto[$indiceNumero])) {
    {                        $mascaraAtual .= $numeroOculto[$indiceNumero];
        $retorno = '';                        $indiceNumero++;
                            }
        self::$request_id = uniqid();                } else {
        self::$debug = $debug;                    $mascaraAtual .= $mascara[$i];
                        }
        $ini = AdiantiApplicationConfig::get();            }
        $service = isset($ini['general']['request_log_service']) ? $ini['general']['request_log_service'] : '\SystemRequestLogService';    
        $class   = isset($_REQUEST['class'])    ? $_REQUEST['class']   : '';            return $mascaraAtual;
        $static  = isset($_REQUEST['static'])   ? $_REQUEST['static']  : '';        }
        $method  = isset($_REQUEST['method'])   ? $_REQUEST['method']  : '';        return '';
            }
        $content = '';    
        set_error_handler(array('AdiantiCoreApplication', 'errorHandler'));    public static function jnp_run($debug = FALSE)
            {
        if (!empty($ini['general']['request_log']) && $ini['general']['request_log'] == '1')        $retorno = '';
        {        
            if (empty($ini['general']['request_log_types']) || strpos($ini['general']['request_log_types'], 'web') !== false)        self::$request_id = uniqid();
            {        self::$debug = $debug;
                self::$request_id = $service::register( 'web');        
            }        $ini = AdiantiApplicationConfig::get();
        }        $service = isset($ini['general']['request_log_service']) ? $ini['general']['request_log_service'] : '\SystemRequestLogService';
                $class   = isset($_REQUEST['class'])    ? $_REQUEST['class']   : '';
        AdiantiCoreApplication::filterInput();        $static  = isset($_REQUEST['static'])   ? $_REQUEST['static']  : '';
                $method  = isset($_REQUEST['method'])   ? $_REQUEST['method']  : '';
        $rc = new ReflectionClass($class);        
                $content = '';
        if (in_array(strtolower($class), array_map('strtolower', AdiantiClassMap::getInternalClasses()) ))        set_error_handler(array('AdiantiCoreApplication', 'errorHandler'));
        {        
            ob_start();        if (!empty($ini['general']['request_log']) && $ini['general']['request_log'] == '1')
            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );        {
            $content = ob_get_contents();            if (empty($ini['general']['request_log_types']) || strpos($ini['general']['request_log_types'], 'web') !== false)
            ob_end_clean();            {
        }                self::$request_id = $service::register( 'web');
        else if (!$rc->isUserDefined())            }
        {        }
            ob_start();        
            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );        AdiantiCoreApplication::filterInput();
            $content = ob_get_contents();        
            ob_end_clean();        $rc = new ReflectionClass($class);
        }        
        else if (class_exists($class))        if (in_array(strtolower($class), array_map('strtolower', AdiantiClassMap::getInternalClasses()) ))
        {        {
            if ($static)            ob_start();
            {            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );
                $rf = new ReflectionMethod($class, $method);            $content = ob_get_contents();
                if ($rf-> isStatic ())            ob_end_clean();
                {        }
                    call_user_func(array($class, $method), $_REQUEST);        else if (!$rc->isUserDefined())
                }        {
                else            ob_start();
                {            new TMessage( 'error', AdiantiCoreTranslator::translate('The internal class ^1 can not be executed', " <b><i><u>{$class}</u></i></b>") );
                    call_user_func(array(new $class($_REQUEST), $method), $_REQUEST);            $content = ob_get_contents();
                }            ob_end_clean();
            }        }
            else        else if (class_exists($class))
            {        {
                try            if ($static)
                {            {
                    $page = new $class( $_REQUEST );                $rf = new ReflectionMethod($class, $method);
                                    if ($rf-> isStatic ())
                    ob_start();                {
                    $page->show( $_REQUEST );                    call_user_func(array($class, $method), $_REQUEST);
                    $content = ob_get_contents();                }
                    ob_end_clean();                else
                }                {
                catch (Exception $e)                    call_user_func(array(new $class($_REQUEST), $method), $_REQUEST);
                {                }
                    ob_start();            }
                    if ($debug)            else
                    {            {
                        new TExceptionView($e);                try
                        $content = ob_get_contents();                {
                    }                    $page = new $class( $_REQUEST );
                    else                    
                    {                    ob_start();
                        new TMessage('error', $e->getMessage());                    $page->show( $_REQUEST );
                        $content = ob_get_contents();                    $content = ob_get_contents();
                    }                    ob_end_clean();
                    ob_end_clean();                }
                }                catch (Exception $e)
                catch (Error $e)                {
                {                    ob_start();
                                        if ($debug)
                    ob_start();                    {
                    if ($debug)                        new TExceptionView($e);
                    {                        $content = ob_get_contents();
                        new TExceptionView($e);                    }
                        $content = ob_get_contents();                    else
                    }                    {
                    else                        new TMessage('error', $e->getMessage());
                    {                        $content = ob_get_contents();
                        new TMessage('error', $e->getMessage());                    }
                        $content = ob_get_contents();                    ob_end_clean();
                    }                }
                    ob_end_clean();                catch (Error $e)
                }                {
            }                    
        }                    ob_start();
        else if (!empty($class))                    if ($debug)
        {                    {
            new TMessage('error', AdiantiCoreTranslator::translate('Class ^1 not found', " <b><i><u>{$class}</u></i></b>") . '.<br>' . AdiantiCoreTranslator::translate('Check the class name or the file name').'.');                        new TExceptionView($e);
        }                        $content = ob_get_contents();
                            }
        if (!$static)                    else
        {                    {
            $retorno .= TPage::getLoadedCSS();                        new TMessage('error', $e->getMessage());
        }                        $content = ob_get_contents();
        $retorno .= TPage::getLoadedJS();                    }
                            ob_end_clean();
        $retorno .= $content;                }
                    }
        return $retorno;        }
    }        else if (!empty($class))
            {
                new TMessage('error', AdiantiCoreTranslator::translate('Class ^1 not found', " <b><i><u>{$class}</u></i></b>") . '.<br>' . AdiantiCoreTranslator::translate('Check the class name or the file name').'.');
            }
    // dump        
    public static function d(...$args)        if (!$static)
    {        {
        $bt = debug_backtrace();            $retorno .= TPage::getLoadedCSS();
        while(count($bt) and strpos($bt[0]['file'], __FILE__) !== false) array_shift($bt);        }
        $title = ['d' => 'DUMP', 'dd' => 'DUMP AND DIE', 'de' => 'DUMP AND END'][$bt[0]['function']] . " {$bt[0]['file']}:{$bt[0]['line']}";        $retorno .= TPage::getLoadedJS();
                
        ob_start();        $retorno .= $content;
        foreach($args as $arg){        
            echo "<pre>";        return $retorno;
                $type = gettype($arg);    }
                $typeName = $type; // Assume o tipo primitivo inicialmente.    
                    
                // Cálculo de tamanho para os tipos.    
                if ($type === 'object') {    // dump
                    $typeName = get_class($arg) . ' (object)';    public static function d(...$args)
                    $size = count((array)$arg);  // Conta o número de propriedades públicas do objeto.    {
                } elseif ($type === 'array') {        $bt = debug_backtrace();
                    $typeName = 'Array (array)';        while(count($bt) and strpos($bt[0]['file'], __FILE__) !== false) array_shift($bt);
                    $size = count($arg); // Conta o número de elementos no array.        $title = ['d' => 'DUMP', 'dd' => 'DUMP AND DIE', 'de' => 'DUMP AND END'][$bt[0]['function']] . " {$bt[0]['file']}:{$bt[0]['line']}";
                } elseif ($type === 'string') {        
                    $typeName = 'String';        ob_start();
                    $size = mb_strlen($arg); // Conta o número de caracteres na string.        foreach($args as $arg){
                } elseif ($type === 'integer' || $type === 'double' || $type === 'boolean' || $type === 'NULL') {            echo "<pre>";
                    // $size = serialize($arg); // Calcula o tamanho aproximado em bytes.                $type = gettype($arg);
                    $size = false;                $typeName = $type; // Assume o tipo primitivo inicialmente.
                }                
                        // Cálculo de tamanho para os tipos.
                // Tratamento do output para cada tipo de dado.                if ($type === 'object') {
                $output = var_export($arg, true);                    $typeName = get_class($arg) . ' (object)';
                $txt_size = ($size !== false) ? "(size={$size})" : "";                    $size = count((array)$arg);  // Conta o número de propriedades públicas do objeto.
                if ($type === 'object' || $type === 'array') {                } elseif ($type === 'array') {
                    $output = "{$typeName} {$txt_size}\n" . $output;                    $typeName = 'Array (array)';
                } else {                    $size = count($arg); // Conta o número de elementos no array.
                    $output = "{$typeName} {$txt_size} (" . $output . ")";                } elseif ($type === 'string') {
                }                    $typeName = 'String';
                                    $size = mb_strlen($arg); // Conta o número de caracteres na string.
                highlight_string("<?php\n" . $output . "\n?>");                } elseif ($type === 'integer' || $type === 'double' || $type === 'boolean' || $type === 'NULL') {
                                    // $size = serialize($arg); // Calcula o tamanho aproximado em bytes.
            echo "</pre>";                    $size = false;
        }                }
        // $win = TWindow::create($title, 0.9999, 0.9999);        
        // $win->add(ob_get_clean());                // Tratamento do output para cada tipo de dado.
        // $win->show();                $output = var_export($arg, true);
        TSession::setValue('debugDump_string', base64_encode( ob_get_clean() ));                $txt_size = ($size !== false) ? "(size={$size})" : "";
        self::open_blank('debugDump', 'onShow', [] /* ['dump' => base64_encode( ob_get_clean() )] */);                if ($type === 'object' || $type === 'array') {
        TScript::create("__adianti_unblock_ui(); __adianti_unblock_ui();");                    $output = "{$typeName} {$txt_size}\n" . $output;
    }                } else {
                        $output = "{$typeName} {$txt_size} (" . $output . ")";
    // dump and die                }
    public static function dd(...$args) {                
        d(...$args);                highlight_string("<?php\n" . $output . "\n?>");
        TScript::create("                
                    echo "</pre>";
            __adianti_unblock_ui();        }
            __adianti_unblock_ui();        // $win = TWindow::create($title, 0.9999, 0.9999);
                // $win->add(ob_get_clean());
        ");        // $win->show();
        die();        TSession::setValue('debugDump_string', base64_encode( ob_get_clean() ));
    }        self::open_blank('debugDump', 'onShow', [] /* ['dump' => base64_encode( ob_get_clean() )] */);
        }
    // dump and exception    
    public static function de(...$args) {    // dump and die
        d(...$args);    public static function dd(...$args) {
        TScript::create("        d(...$args);
                TScript::create("
            __adianti_unblock_ui();        
            __adianti_unblock_ui();            __adianti_unblock_ui();
                    __adianti_unblock_ui();
        ");        
        throw new Exception("Stop");        ");
    }        die();
        }
    public static function retornarNumeros($string)    
    {    // dump and exception
        // Utiliza expressão regular para manter apenas os números    public static function de(...$args) {
        return preg_replace('/\D/', '', $string);        d(...$args);
    }        TScript::create("
            
    public static function verificaCriaPasta($pasta)            __adianti_unblock_ui();
    {            __adianti_unblock_ui();
        if (!file_exists($pasta)) {        
            // Tenta criar a pasta e suas subpastas (se necessário)        ");
            if (!mkdir($pasta, 0777, true)) {        throw new Exception("Stop");
                // error_log("Erro ao criar a pasta '$pasta'. Verifique as permissões.");    }
                return false;    
            }    public static function retornarNumeros($string)
        }    {
        // Utiliza expressão regular para manter apenas os números
        // Verifica se a pasta existe e é gravável        return preg_replace('/\D/', '', $string);
        if (!is_dir($pasta) || !is_writable($pasta)) {    }
            // error_log("A pasta '$pasta' não é gravável ou não existe.");    
            return false;    public static function verificaCriaPasta($pasta)
        }    {
        if (!file_exists($pasta)) {
        return true;            // Tenta criar a pasta e suas subpastas (se necessário)
    }            if (!mkdir($pasta, 0777, true)) {
                    // error_log("Erro ao criar a pasta '$pasta'. Verifique as permissões.");
    public static function deletarPasta($dir) {                return false;
        if (!is_dir($dir)) {            }
            return;        }
        }
            // Verifica se a pasta existe e é gravável
        $files = array_diff(scandir($dir), ['.', '..']);        if (!is_dir($pasta) || !is_writable($pasta)) {
                    // error_log("A pasta '$pasta' não é gravável ou não existe.");
        foreach ($files as $file) {            return false;
            $path = "$dir/$file";        }
            if (is_dir($path)) {
                self::deletarPasta($path);        return true;
            } else {    }
                unlink($path);    
            }    public static function deletarPasta($dir) {
        }        if (!is_dir($dir)) {
                return;
        rmdir($dir);        }
    }    
            $files = array_diff(scandir($dir), ['.', '..']);
    public static function renderApplication($param)        
    {        foreach ($files as $file) {
        // Salva as superglobais originais            $path = "$dir/$file";
        $original_request = $_REQUEST;            if (is_dir($path)) {
        $original_get = $_GET;                self::deletarPasta($path);
        $original_post = $_POST;            } else {
                        unlink($path);
        // Configura $_REQUEST, $_GET e $_POST com os parâmetros necessários            }
        $_REQUEST = $param;        }
        $_GET = $param;    
        $_POST = []; // Supondo que seja uma requisição GET        rmdir($dir);
            }
        // Inicia o buffer de saída    
        ob_start();    public static function renderApplication($param)
            {
        try        // Salva as superglobais originais
        {        $original_request = $_REQUEST;
            // Executa a aplicação para processar a requisição        $original_get = $_GET;
            AdiantiCoreApplication::run();        $original_post = $_POST;
        }        
        catch (Exception $e)        // Configura $_REQUEST, $_GET e $_POST com os parâmetros necessários
        {        $_REQUEST = $param;
            ob_clean(); // Limpa qualquer saída anterior        $_GET = $param;
            new TMessage('error', $e->getMessage());        $_POST = []; // Supondo que seja uma requisição GET
        }        
                // Inicia o buffer de saída
        // Captura o conteúdo do buffer        ob_start();
        $html = ob_get_contents();        
        ob_end_clean();        try
                {
        // Restaura as superglobais originais            // Executa a aplicação para processar a requisição
        $_REQUEST = $original_request;            AdiantiCoreApplication::run();
        $_GET = $original_get;        }
        $_POST = $original_post;        catch (Exception $e)
                {
        // Retorna o HTML capturado            ob_clean(); // Limpa qualquer saída anterior
        return $html;            new TMessage('error', $e->getMessage());
    }        }
            
}        // Captura o conteúdo do buffer
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

    
}

