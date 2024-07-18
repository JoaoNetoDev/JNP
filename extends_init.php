<?php

// dump
function d(...$args)
{
    $bt = debug_backtrace();
    while(count($bt) and strpos($bt[0]['file'], __FILE__) !== false) array_shift($bt);
    $title = ['d' => 'DUMP', 'dd' => 'DUMP AND DIE'][$bt[0]['function']] . " {$bt[0]['file']}:{$bt[0]['line']}";
    
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
    JNP::open_blank('debugDump', 'onShow', [] /* ['dump' => base64_encode( ob_get_clean() )] */);
}

// dump and die
function dd(...$args) {
    d(...$args);
    die();
}

// dump and exception
function de(...$args) {
    d(...$args);
    throw new Exception("Stop");
}

// if($_GET['debug'] == '1'){
//     $ini = AdiantiApplicationConfig::get();
//     $ini['general']['debug'] = 1;
//     AdiantiApplicationConfig::load($ini);
//     AdiantiApplicationConfig::apply();
// }
