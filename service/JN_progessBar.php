<?php

class JN_progessBar
{
    private $current;
    private $total;
    private $additionalText;

    public function __construct($total = 100, $current = 0, $additionalText = '')
    {
        $this->total = $total;
        $this->current = $current;
        $this->additionalText = $additionalText;
    }

    public function setCurrent($current)
    {
        $this->current = $current;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function setAdditionalText($text)
    {
        $this->additionalText = $text;
    }

    public function render()
    {
        $percentage = ($this->total > 0) ? ($this->current / $this->total) * 100 : 0;
        $progressText = "{$this->current}/{$this->total}";
        
        TSession::setValue('percentage_JN_progressBar', $percentage);
        TSession::setValue('total_JN_progressBar', $this->total);
        
        ob_start();
        ?>
        <div style="width: 100%; border: 1px solid #ccc; padding: 3px; border-radius: 5px;">
            <div id="progress-bar" style="width: <?= $percentage ?>%; background: #4caf50; text-align: center; padding: 5px; color: white; border-radius: 5px;">
                <?= $progressText ?> <?= $this->additionalText ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function updateProgress($param)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        
        $current = $param['current'];
        $total = $param['total'];
        $additionalText = $param['additionalText'];

        $progress = new JN_progessBar($total, $current, $additionalText);
        echo "data: " . json_encode(['html' => $progress->render(), 'percentage' => TSession::getValue('percentage_JN_progressBar'), 'onFinish' => TSession::getValue(__CLASS__ . "_param")['onFinish'] ?? ' ']) . "\n\n";
        ob_flush();
        flush();
    }
    
    
    public static function start(){
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        
        echo str_pad('', 4096) . "\n";
        ob_flush();
        flush();
        
        if(!empty(TSession::getValue(__CLASS__ . "_param"))){
            return TSession::getValue(__CLASS__ . "_param");
        } else {
            return [];
        }
    }
    
    public static function finish(){
        // usleep(1000000);
        $data = [
            'current' => (TSession::getValue('total_JN_progressBar') * 2),
            'total' => TSession::getValue('total_JN_progressBar'),
            'additionalText' => 'Concluído!',
        ];
        self::updateProgress($data);
    }
    
    public static function call($classe, $metodo, $param = []) 
    {
        try
        {
            if(!empty($param)){
                TSession::setValue(__CLASS__ . "_param", $param);
            }
            
            TScript::create("JN_progessBar_classe='{$classe}';JN_progessBar_metodo='{$metodo}';p_b64='{$param_b64}';startProgress();console.log(p_b64);");

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

}
