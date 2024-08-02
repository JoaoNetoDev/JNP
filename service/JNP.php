<?php

class JNP
{
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
    
    public static function converterData($data, $formatoEntrada, $formatoSaida) {
        // Cria um objeto DateTime a partir da string de data e do formato de entrada
        $dataObjeto = DateTime::createFromFormat($formatoEntrada, $data);
    
        // Verifica se a data é válida
        if (!$dataObjeto) {
            return false;  // Retorna falso se a data for inválida
        }
    
        // Formata a data para o novo formato e retorna
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
    
    public static function mask_CPF_CNPJ($formName, $name){
        
        TScript::create("
        
            var options = {
        		onKeyPress : function(cpfcnpj, e, field, options) {
        			var masks = ['000.000.000-000', '00.000.000/0000-00'];
        			var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
        			$('form[name=\"$formName\"] [name=\"$name\"]').mask(mask, options);
        		}
        	};
        
        	$('form[name=\"$formName\"] [name=\"$name\"]').mask('000.000.000-000', options);

        
        ");
        
    }
    
    public static function mask_fone_br($formName, $name){
        TScript::create("
            $('form[name=\"{$formName}\"] [name=\"$name\"]').on('keyup change', function() {
                var numero = $(this).val().replace(/\D/g,'');
                var newMask = (numero.length <= 10) ? '(99) 9999-99999' : '(99)99999-9999';

                $(this).val(numero);
                $(this).mask(newMask);
                $(this).data('mask', newMask);

                var newLength = $(this).val().length;
                this.setSelectionRange(newLength, newLength);
            
                $('form[name=\"{$formName}\"] [name=\"$name\"]').attr('autocomplete', 'on');
                
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
        
        $dirPath = 'app/resources/log';
        if (!is_dir($dirPath)) {
            // Tenta criar o diretório e define as permissões (0777 permite leitura, escrita e execução para todos os usuários)
            if (mkdir($dirPath, 0775, true)) {
                // echo "Diretório '$dirPath' criado com sucesso.";
            } else {
                // echo "Falha ao criar o diretório '$dirPath'.";
            }
        } else {
            // echo "O diretório '$dirPath' já existe.";
        }
        
        if (is_dir($dirPath)) {
            TTransaction::setLogger(new TLoggerTXT('app/resources/log/log_' . $class . '_' . date('Ymd') . '.txt'));
        }
        
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

    
}
