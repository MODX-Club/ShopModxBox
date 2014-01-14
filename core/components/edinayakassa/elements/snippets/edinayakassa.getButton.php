<?php
//Секретный ключ интернет-магазина
$secret_key = $modx->getOption('edinayakassa.secret_key');
  

// Добавление полей формы в ассоциативный массив
//Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
//$fields["WMI_PTENABLED"]      = array("ContactRUB", "UnistreamRUB", "SberbankRUB", "RussianPostRUB");

$fields = array_merge(
    array (
        'WMI_DESCRIPTION' => "BASE64:".base64_encode($description),
        "order_id"        => $order_id,
        "uid"             => $modx->user->id,
    ),
    (array)$scriptProperties
);


$options = array();
$modx->getParser();
$maxIterations= intval($modx->getOption('parser_max_iterations', $options, 10));


//Сортировка значений внутри полей
foreach($fields as $name => $val){
    if (is_array($val)){
        usort($val, "strcasecmp");
        $fields[$name] = $val;
    }
    else{
        // На случай наличия в значениях MODX-тегов, парсим их сразу, иначе нарушится цифровая подпись
        $modx->parser->processElementTags('', $fields[$name], true, false, '[[', ']]', array(), $maxIterations);
        $modx->parser->processElementTags('', $fields[$name], true, true, '[[', ']]', array(), $maxIterations); 
    }
}


// Формирование сообщения, путем объединения значений формы, 
// отсортированных по именам ключей в порядке возрастания.
uksort($fields, "strcasecmp");
$fieldValues = "";


foreach($fields as $value) 
{
    if (is_array($value)){
        foreach($value as $v){
            $fieldValues .= $v;
        }
    }
    else{
        
        $fieldValues .= $value;
    }
}
 

// Формирование значения параметра WMI_SIGNATURE, путем 
// вычисления отпечатка, сформированного выше сообщения, 
// по алгоритму MD5 и представление его в Base64

$signature = base64_encode(pack("H*", md5($fieldValues . $secret_key)));

//Добавление параметра WMI_SIGNATURE в словарь параметров формы

$fields["WMI_SIGNATURE"] = $signature;

// Формирование HTML-кода платежной формы в Smarty-шаблоне

$modx->smarty->assign('fields', $fields); 
return $modx->smarty->fetch('edinayakassa/button.tpl');