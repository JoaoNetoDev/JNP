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
    
    public static function jnp_load_page($class, $method = NULL, $parameters = NULL){
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, $parameters);
        TScript::create(" __jnp_load_page_noRegister('{$query}'); ");
    }
    
    public static function tentryInt($obj_tentry){
        $obj_tentry->id = "tentry_libUtil_" . uniqid();
        $obj_tentry->inputmode = "numeric";
        TScript::create("
            $('#{$obj_tentry->id}').mask('000.000.000.000', { reverse: true });
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
        $decimal_mask = str_repeat("0", 3);
        if ($precisao > 0) {
            $decimal_mask = "0," . str_repeat("0", $precisao);
        }
        TScript::create("
            $('#{$obj_tentry->id}').mask('000.000.000.000{$decimal_mask}', { reverse: true });
            $('#{$obj_tentry->id}').css('text-align', 'right');
            $('#{$obj_tentry->id}').on('click', function() {
                var len = $(this).val().length;
                $(this)[0].setSelectionRange(len, len);
            });
        ");
        return $obj_tentry->id;
    }
    
    public static function converterData($data, $formatoEntrada, $formatoSaida, $includeTime = false) {
        $dataObjeto = DateTime::createFromFormat($formatoEntrada, $data);
        if (!$dataObjeto) return false;
        if ($includeTime) $dataObjeto->setTime(0, 0, 0);
        return $dataObjeto->format($formatoSaida);
    }
    
    public static function consoleJson($param){
        $jsonParam = is_array($param) || is_object($param) ? json_encode($param) : json_encode(['json' => $param]);
        self::consoleLog($jsonParam);
    }
    
    public static function consoleLog($log){
        $log = is_array($log) || is_object($log) ? json_encode($log) : base64_encode($log);
        $logJs = is_array($log) || is_object($log) ? $log : "atob('$log')";
        TScript::create("console.log($logJs);");
    }
    
    public static function isMobile() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $isMobile = preg_match('/Mobile|Android|Silk|Kindle|BlackBerry|Opera Mini|Opera Mobi/', $userAgent);
        return (bool) $isMobile;
    }
    
    public static function UUID() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    public static function is_uuid($uuid) {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }
    
    public static function mask_CPF_CNPJ($formName, $name) {
        TScript::create("
            function JNP_CHANGE_MASK_CPFCNPJ(element) {
                var cpfcnpj = $(element).val().replace(/[^0-9]/g, '');
                var mask = (cpfcnpj.length > 11) ? '00.000.000/0000-00' : '000.000.000-00';
                $(element).mask(mask, { reverse: true });
            }
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() { JNP_CHANGE_MASK_CPFCNPJ(this); });
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input paste', function() { JNP_CHANGE_MASK_CPFCNPJ(this); });
        ");
    }
    
    public static function mask_fone_br($formName, $name) {
        TScript::create("
            function JNP_APPLY_MASK_FONE(element) {
                var numero = $(element).val().replace(/\D/g, '');
                var newMask = (numero.length <= 10) ? '(99) 9999-9999' : '(99) 99999-9999';
                $(element).mask(newMask);
            }
            $('form[name=\"$formName\"] [name=\"$name\"]').each(function() { JNP_APPLY_MASK_FONE(this); });
            $('form[name=\"$formName\"] [name=\"$name\"]').on('keyup change input', function() { JNP_APPLY_MASK_FONE(this); });
        ");
    }
    
    public static function registerURL($url){
        TScript::create(" __adianti_register_state('$url', 'adianti'); ");
    }
    
    public static function get_URL_atual($returnArray = false) {
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = "$protocolo://$host$uri";
        return $returnArray ? compact('protocolo', 'host', 'uri') : $url;
    }
    
    public static function open_blank($class, $method = 'onShow', $param = []){
        unset($param['class'], $param['method']);
        $query = AdiantiCoreApplication::buildHttpQuery($class, $method, array_merge($param, ['hideMenu' => 'true']));
        $query = str_replace(['index.php', 'engine.php'], 'semMenu.php', $query);
        TScript::create("
            var larguraTela = screen.width;
            var alturaTela = screen.height;
            __adianti_block_ui('carregando');
            window.open('$query', '_blank', 'width=' + larguraTela + ',height=' + alturaTela);
        ");
    }
    
    public static function logTxt($class){
        $directory = 'app/resources/log/';
        if (!is_dir($directory)) mkdir($directory, 0755, true);
        TTransaction::setLogger(new TLoggerTXT($directory . 'log_' . $class . '_' . date('Ymd') . '.txt'));
    }
    
    public static function cpfCnpjHideParcial($value) {
        $numeroLimpo = preg_replace('/[^0-9]/', '', $value);
        $tamanho = strlen($numeroLimpo);
        if ($tamanho === 11) {
            $inicio = substr($numeroLimpo, 0, 3);
            $fim = substr($numeroLimpo, -2);
        } elseif ($tamanho === 14) {
            $inicio = substr($numeroLimpo, 0, 2);
            $fim = substr($numeroLimpo, -4);
        } else return '';
        return $inicio . str_repeat('#', $tamanho - strlen($inicio) - strlen($fim)) . $fim;
    }
    
    public static function retornarNumeros($string) {
        return preg_replace('/\D/', '', $string);
    }
    
    public static function verificaCriaPasta($pasta) {
        if (!file_exists($pasta)) {
            if (!mkdir($pasta, 0777, true)) return false;
        }
        return is_dir($pasta) && is_writable($pasta);
    }
    
    public static function deletarPasta($dir) {
        if (!is_dir($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? self::deletarPasta($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    public static function renderApplication($param) {
        $original_request = $_REQUEST;
        $_REQUEST = $_GET = $param;
        $_POST = [];
        ob_start();
        try {
            AdiantiCoreApplication::run();
        } catch (Exception $e) {
            ob_clean();
            new TMessage('error', $e->getMessage());
        }
        $html = ob_get_clean();
        $_REQUEST = $original_request;
        return $html;
    }
    
    public static function d(...$args) {
        $bt = debug_backtrace();
        while (count($bt) && strpos($bt[0]['file'], __FILE__) !== false) array_shift($bt);
        foreach ($args as $arg) {
            echo "<pre>";
            highlight_string("<?php\n" . var_export($arg, true) . "\n?>");
            echo "</pre>";
        }
    }
}
