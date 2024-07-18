<?php

class LibHtmlElement
{
    
    public static $select_placeholder = '';
    
    public function __construct($param)
    {
        
    }
    
    public static function myFunction($param)
    {
        
    }
    
    public static function myBtn($paramBtn, $param = []){
        
        // Valores padrão
        $paramBtn['name'] = empty($paramBtn['name']) ? 'btn_ativaEste' : $paramBtn['name'];
        $paramBtn['btn_class'] = empty($paramBtn['btn_class']) ? 'btn-success font-weight-bold' : $paramBtn['btn_class'];
        $paramBtn['actionForm'] = empty($paramBtn['actionForm']) ? self::$formName : $paramBtn['actionForm'];
        $paramBtn['actionClass'] = empty($paramBtn['actionClass']) ? 'ResultadoFormView' : $paramBtn['actionClass'];
        $paramBtn['actionMethod'] = empty($paramBtn['actionMethod']) ? 'onAtivaEste' : $paramBtn['actionMethod'];
        if($paramBtn['descricao'] != 0){
            $paramBtn['descricao'] = empty($paramBtn['descricao']) ? 'Ativar Este Resultado' : $paramBtn['descricao'];
        }
        $paramBtn['fontAwesome'] = empty($paramBtn['fontAwesome']) ? ' ' : $paramBtn['fontAwesome'];
        $paramBtn['color'] = empty($paramBtn['color']) ? '#FFFFFF' : $paramBtn['color'];
        $paramBtn['styleOthers'] = empty($paramBtn['styleOthers']) ? '' : $paramBtn['styleOthers'];
        
        $objParam = (object)$paramBtn;
        $url_param = http_build_query($param);

        // de($objParam);

        $id_unico = uniqid();
        
        if(!empty(trim($objParam->fontAwesome))){
            $fontAw = "<i class='{$objParam->fontAwesome} ' style='; color: {$objParam->color};padding-right:4px'></i>";
        } else {
            $fontAw = "";
        }

        return "

       <button id='tbutton_{$objParam->name}_{$id_unico}' name='{$objParam->name}' class=' btn {$objParam->btn_class}' onclick=\"Adianti.waitMessage = 'Carregando';__adianti_post_data('$objParam->actionForm', 'class=$objParam->actionClass&amp;method=$objParam->actionMethod&$url_param');return false;\" aria-label='$objParam->descricao' style='{$objParam->styleOthers}'>
       <span>
        {$fontAw}
        {$objParam->descricao}</span>
        </button> 

";

    }
    
    public static function mySelect2($paramSelect, $options = [])
    {
        // Valores padrão
        $paramSelect['id'] = empty($paramSelect['id']) ? 'my_select2_' . uniqid() : $paramSelect['id'];
        $paramSelect['name'] = empty($paramSelect['name']) ? 'mySelect2' : $paramSelect['name'];
        $paramSelect['class'] = empty($paramSelect['class']) ? 'form-control select2-custom-class' : $paramSelect['class'];
        $paramSelect['placeholder'] = empty($paramSelect['placeholder']) ? 'Selecione uma opção' : $paramSelect['placeholder'];

        $htmlOptions = "";
        foreach ($options as $value => $text) {
            $htmlOptions .= "<option value='$value'>$text</option>\n";
        }

        // Geração do Select HTML
        $htmlSelect = "
        <select id='{$paramSelect['id']}' name='{$paramSelect['name']}' class='{$paramSelect['class']}' style='width: 100%;' data-placeholder='{$paramSelect['placeholder']}'>
            <option></option>
            $htmlOptions
        </select>";

        // Script para aplicar o Select2 ao campo
        $script = "
        <script>
            tcombo_enable_search('#{$paramSelect['id']}', '{$paramSelect['placeholder']}');
        </script>
        ";

        return $htmlSelect . $script;
    }
    
    public static function myDbSelect($name, $database, $model, $key, $value, $ordercolumn = NULL, TCriteria $criteria = NULL)
    {
        TTransaction::open($database); // Abre a conexão com o banco
        $repository = new TRepository($model);
    
        // Define critérios se nenhum for passado
        if (is_null($criteria)) {
            $criteria = new TCriteria;
        }
        if (!is_null($ordercolumn)) {
            $criteria->setProperty('order', $ordercolumn);
        }
    
        // Carrega todos os objetos que satisfazem o critério
        $collection = $repository->load($criteria);
        TTransaction::close(); // Fecha a conexão com o banco
    
        $options = [];
        foreach ($collection as $object) {
            // Remove as chaves para acessar a propriedade diretamente
            $fieldValue = trim($value, '{}'); 
            $options[$object->$key] = $object->$fieldValue;
        }
        
        $placeholder = !empty(self::$select_placeholder) ? self::$select_placeholder : 'Selecione uma opção';
    
        // Chama mySelect2 passando os parâmetros necessários
        $params = [
            'name' => $name,
            'placeholder' => $placeholder
        ];
    
        return self::mySelect2($params, $options);
    }
    
    public static function btnContextGroupAction($nomeListagem, $param = []){
        
        extract($param);
        
        $topDrop_script = !empty($topDrop) ? " top: '$topDrop' " : " ";
        
        echo "
        
        <script>
        
            $('#{$nomeListagem} tbody tr').on('contextmenu', function(e) {
            
                e.preventDefault();
        
                var dropdownMenu = $(this).find('.dropdown-menu');
                var dropdownButton = $(this).find('button.dropdown-toggle');
                dropdownButton.trigger('click');
                
                var posX = e.pageX {$corrigeLeft};
                
                var trHeight = $(this).outerHeight();
                var trOffsetTop = $(this).offset().top;
                var trAbsoluteBottom = trOffsetTop + trHeight;

                
                setTimeout(function() {
                    dropdownMenu.css({
                        left: posX + 'px'
                    });
                    if (dropdownMenu.attr('x-placement') === 'bottom-start') {
                    
                        dropdownMenu.css({
                        
                            $topDrop_script
                        
                        });
                    
                    }
                }, 1);
            });
            
        </script>
        <style>
        
            .dropdown-menu.show {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
                border: 1px solid silver;
                max-height: 320px;
                overflow: auto;
            }
            
            .dropdown-header{
                display: none;
            }
        
        </style>
        
        ";
        
    }
    
    public static function myTTime(){
        
        // Em desuso por enquanto
        
        $ttime = "
        
            <div class='fb-inline-field-container form-line' style='display: inherit;vertical-align:top;;width: 110px' wrapped-widget='ttime'>
                <script type='text/javascript'>
                    tentry_new_mask( 'ttime_1878782743', '99:99');
                </script>
                <div class='tdate-group tdatetimepicker input-append date' style='width: 100%;'>
                    <input class='form-control tfield' widget='ttime' type='text' name='testeTTime' value='' style='width:100%; id='ttime_1878782743' maxlength='5'>
                    <span class='add-on btn btn-default tdate-group-addon'>
                        <i class='far fa-clock icon-th'></i>
                    </span>
                </div>
                <script type=\"text/javascript\">
                    tdatetime_start( '#ttime_1878782743', 'hh:ii', 'pt', '100%', '{\"startView\":1,\"pickDate\":false,\"formatViewType\":\"time\",\"fontAwesome\":true}');
                </script>
            </div>
        
        ";
        
        return $ttime;
        
    }
    
    public static function myIndicator($param = null){
        
        try{
        
            if(!empty($param) and is_array($param)){
                $obj_param = (object)$param;
            } else {
                throw new Exception("Requisição: Falta parâmetros para gerar a conta!");
            }
            
            $obj_param->cor_icone = empty($obj_param->cor_icone) ? $obj_param->cor_texto : $obj_param->cor_icone;
            
            $alvo="";
            if($obj_param->ativa_alvo !== false){
                
                if(empty($obj_param->cor_progresso_concluido) or empty($obj_param->cor_progresso_falta) or !isset($obj_param->alvo) or !is_numeric($obj_param->alvo)){
                    throw new Exception("HtmlElement: Para ativar o alvo é preciso informar (cor_progresso_concluido, cor_progresso_falta e alvo)");
                }
                
                $valor_alcancado = 0;
                $obj_param->ativa_porcentagem = empty($obj_param->ativa_porcentagem) ? false : $obj_param->ativa_porcentagem;
                if($obj_param->ativa_porcentagem !== false){
                    // Calcula a porcentagem truncada do valor em relação ao alvo
                    if (isset($obj_param->valor, $obj_param->alvo) && is_numeric($obj_param->valor) && is_numeric($obj_param->alvo)) {
                        if ($obj_param->alvo != 0) { // Verifica se alvo não é zero para evitar divisão por zero
                            $valor_alcancado = intval(($obj_param->valor / $obj_param->alvo) * 100) . "%";
                        } else {
                            $valor_alcancado = "0%"; // Se alvo for zero, a porcentagem alcançada é 0%
                        }
                    } else {
                        throw new Exception("HtmlElement: É preciso que 'valor' e 'alvo' sejam numéricos e estejam definidos para calcular a porcentagem.");
                    }
                } else {
                    $valor_alcancado = ($obj_param->alvo >= $obj_param->valor) ? $obj_param->alvo - $obj_param->valor : 0;
                    $valor_alcancado = !empty($valor_alcancado) ? $obj_param->monetario . " " . number_format($valor_alcancado, 2, ',', '.') : $valor_alcancado;
                    $valor_alcancado = "Falta <b>{$valor_alcancado}</b>";
                }
                
                $barra_progresso = (!empty($obj_param->valor) and !empty($obj_param->alvo)) ? intval(($obj_param->valor / $obj_param->alvo) * 100) . "%" : "0%";
                
                if(!empty($obj_param->monetario)){
                    $alvo_formatado = $obj_param->monetario . " " . number_format($obj_param->alvo, 2, ',', '.');
                }
                    
                $alvo = "
                      <div class=\"progress\">
                        <div class=\"progress-bar\" style=\"width: {$barra_progresso};background-color: {$obj_param->cor_progresso_concluido};\"></div>
                      </div>
                      <span class=\"progress-description\" style=\"line-height: 1;font-size: 80%;color: {$obj_param->cor_progresso_falta}\">
                        {$valor_alcancado} de {$alvo_formatado}
                      </span>
                      
                ";
            }
            
            if(!empty($obj_param->monetario)){
                $valor_formatado = $obj_param->monetario . " " . number_format($obj_param->valor, 2, ',', '.');
            }
            
            // LibUtil::meu_vardump([$obj_param->valor, $valor_formatado]);
            
            $id_indicator = "my_indicator_" . uniqid();
            
            if($obj_param->icone_completo !== true){
                $param_icone = "fas {$obj_param->icone}";
            } else {
                $param_icone = $obj_param->icone;
            }
            
            $altura = !empty($obj_param->altura) ? $obj_param->altura : '95px';
            if (substr($altura, -2) !== 'px') {
                $altura_lh_icon = $altura - 10;
                $altura .= 'px';
            } else {
                $altura = explode('px', $altura)[0];
                $altura_lh_icon = $altura - 10;
                $altura .= 'px';
            }
            $altura_lh_icon .= 'px';
            
            $obj_param->fonte_size_vl = !empty($obj_param->fonte_size_vl) ? $obj_param->fonte_size_vl : 20;
            if (substr($obj_param->fonte_size_vl, -2) !== 'px') {
                $obj_param->fonte_size_vl .= 'px';
            }
            
            $obj_param->tamanho_icone = !empty($obj_param->tamanho_icone) ? $obj_param->tamanho_icone : 45;
            if (substr($obj_param->tamanho_icone, -2) !== 'px') {
                $obj_param->tamanho_icone .= 'px';
            }
            
            return "
            
              <div id=\"{$id_indicator}\" class=\"info-box\" style=\"background-color: {$obj_param->cor_fundo_direita};height: {$altura};width: 100%;min-height: {$altura};\">
                <span class=\"info-box-icon\" style=\"background-color: {$obj_param->cor_fundo_esquerda};height: {$altura} !important; line-height: {$altura_lh_icon} !important;margin-right: 0 !important;\">
                  <i class=\"{$param_icone} \" style=\"; color: {$obj_param->cor_icone};font-size: {$obj_param->tamanho_icone};\"></i>
            
                </span>
                <div class=\"info-box-content\" style=\"flex: 1;padding: 5px 10px;height: {$altura};margin-left: ; text-align: left;\">
                  <span class=\"info-box-text\" style=\"color: {$obj_param->cor_texto}; font-size: {$obj_param->fonte_size_vl};\" title=\"\" data-original-title=\"{$obj_param->titulo_grafico}\">{$obj_param->titulo_grafico}</span>
                  <span class=\"info-box-number\" style=\"font-weight: normal;word-wrap: initial;text-overflow: ellipsis; width:
                    100%;white-space: nowrap; overflow: hidden;font-weight: bold;color: {$obj_param->cor_texto}; font-size: {$obj_param->fonte_size_vl}
                    !important;\" popover=\"true\" popcontent=\"{$valor_formatado}\">{$valor_formatado}</span>
                    {$alvo}
                </div>
                <script>
                
                    $('#{$id_indicator}').parent().parent().css({'width': '100%', 'padding-right': '10px'});
                
                </script>
              </div>
            
            ";
            
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    
    public static function get_colorCombinations(){
        $colorCombinations = [
            [
                'cor_texto' => '#FFFFFF',
                'cor_progresso_concluido' => '#4CAF50',
                'cor_progresso_falta' => '#C8E6C9',
                'cor_fundo_direita' => '#1B5E20',
                'cor_fundo_esquerda' => '#2E7D32',
                'cor_icone' => '#A5D6A7'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#2196F3',
                'cor_progresso_falta' => '#BBDEFB',
                'cor_fundo_direita' => '#0D47A1',
                'cor_fundo_esquerda' => '#1976D2',
                'cor_icone' => '#82B1FF'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#FFC107',
                'cor_progresso_falta' => '#FFECB3',
                'cor_fundo_direita' => '#FF6F00',
                'cor_fundo_esquerda' => '#FFA000',
                'cor_icone' => '#FFE57F'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#E91E63',
                'cor_progresso_falta' => '#F8BBD0',
                'cor_fundo_direita' => '#880E4F',
                'cor_fundo_esquerda' => '#C2185B',
                'cor_icone' => '#FF80AB'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#9C27B0',
                'cor_progresso_falta' => '#E1BEE7',
                'cor_fundo_direita' => '#4A148C',
                'cor_fundo_esquerda' => '#7B1FA2',
                'cor_icone' => '#EA80FC'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#00BCD4',
                'cor_progresso_falta' => '#B2EBF2',
                'cor_fundo_direita' => '#006064',
                'cor_fundo_esquerda' => '#0097A7',
                'cor_icone' => '#84FFFF'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#CDDC39',
                'cor_progresso_falta' => '#F0F4C3',
                'cor_fundo_direita' => '#827717',
                'cor_fundo_esquerda' => '#C0CA33',
                'cor_icone' => '#F4FF81'
            ],
            [
                'cor_texto' => '#000',
                'cor_progresso_concluido' => '#FFEB3B',
                'cor_progresso_falta' => '#FFF9C4',
                'cor_fundo_direita' => '#F57F17',
                'cor_fundo_esquerda' => '#FBC02D',
                'cor_icone' => '#FFFF8D'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#FF5722',
                'cor_progresso_falta' => '#FFCCBC',
                'cor_fundo_direita' => '#BF360C',
                'cor_fundo_esquerda' => '#E64A19',
                'cor_icone' => '#FF9E80'
            ],
            [
                'cor_texto' => '#FFF',
                'cor_progresso_concluido' => '#3F51B5',
                'cor_progresso_falta' => '#C5CAE9',
                'cor_fundo_direita' => '#1A237E',
                'cor_fundo_esquerda' => '#303F9F',
                'cor_icone' => '#B3E5FC'
            ]
        ];

        return $colorCombinations;
    }
    
    public static function getContrastingColor($backgroundColor) {
        // Remover o '#' se estiver presente na cor de fundo
        $color = ltrim($backgroundColor, '#');
    
        // Convertendo hex para RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
    
        // Calculando a luminância da cor
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    
        // Escolhendo a cor do texto baseada na luminância
        // Limiar de luminância, valores abaixo dele usam texto branco, caso contrário, preto.
        // Este valor pode variar. 0.5 é um bom ponto de partida, mas você pode ajustá-lo conforme necessário.
        if ($luminance > 0.6) {
            return '#000000'; // Cor de texto preto para fundos claros
        } else {
            return '#FFFFFF'; // Cor de texto branco para fundos escuros
        }
    }

}
