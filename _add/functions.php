<?php 

function generateUniqueCode(){
    
    $alfabeto = "23456789ABCDEFGHIJKLMNOPQRST";
    $tamanho = 12;
    $letra = "";
    $resultado = "";

    for($i = 1 ; $i < $tamanho ; $i++){
        $letra = substr($alfabeto,rand(0,20),1);
        $resultado .= $letra;
    }

    //echo $resultado . "</br>" ;

    date_default_timezone_set('America/Recife');
    $agora = getdate();
    $codigo_data = $agora['year'] . '_' . $agora['yday'] ;
    $codigo_data .= $agora['hours'] . $agora['minutes'] . $agora['seconds']; 
    
    return "photo_" . $codigo_data . '_' . $resultado;
    
}

function getExtension($nome){
    return strrchr($nome,".");
}


function getError($numero){
    
    $array_erro = array(
        UPLOAD_ERR_OK => "Sem erro.",
        UPLOAD_ERR_INI_SIZE => "O arquivo enviado excede o limite definido na diretiva upload_max_filesize do php.ini.",
        UPLOAD_ERR_FORM_SIZE => "O arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML",
        UPLOAD_ERR_PARTIAL => "O upload do arquivo foi feito parcialmente.",
        UPLOAD_ERR_NO_FILE => "Nenhum arquivo foi enviado.",
        UPLOAD_ERR_NO_TMP_DIR => "Pasta temporária ausente.",
        UPLOAD_ERR_CANT_WRITE => "Falha em escrever o arquivo em disco.",
        UPLOAD_ERR_EXTENSION => "Uma extensão do PHP interrompeu o upload do arquivo."
    ); 

    return $array_erro[$numero];

}


function publishImage($image){
    
        $arquivo_temporario = $image['tmp_name'];
        $nome_original = $image['name'];
        $novo_nome = generateUniqueCode() . getExtension($nome_original);
        $nome_completo = "images/profile/" . $novo_nome;
        
        if(move_uploaded_file($arquivo_temporario,$nome_completo )) {
            return array("Image published",$nome_completo);
        } else {
            return array(getError($image['error']),"");
        }
}

?>