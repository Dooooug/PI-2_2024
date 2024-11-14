
<?php
function validar_login($usuario, $senha) {
    $url = "http://191.252.110.154:3000/api/validar_login";
    $data = ["usuario" => $usuario, "senha" => $senha];
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $response = json_encode(["status" => false, "aut" => 0, "error" => curl_error($ch)]);
    }

    curl_close($ch);

    return ["response" => json_decode($response, true), "httpcode" => $httpcode];
}
?>
