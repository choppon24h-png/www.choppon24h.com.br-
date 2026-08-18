<?php
header('Content-Type: application/json');

// Configurações
$data_file = 'franchise_data.json';
$email_to = 'choppon24h@gmail.com';

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Coletar dados do formulário
$data = [
    'timestamp' => date('Y-m-d H:i:s'),
    'dados_pessoais' => [
        'nome' => $_POST['nome'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefone' => $_POST['telefone'] ?? '',
        'idade' => $_POST['idade'] ?? '',
        'cpf' => $_POST['cpf'] ?? '',
        'cidade' => $_POST['cidade'] ?? '',
        'estado' => $_POST['estado'] ?? ''
    ],
    'perfil_profissional' => [
        'resumo_perfil' => $_POST['resumo_perfil'] ?? '',
        'experiencias_anteriores' => $_POST['experiencias_anteriores'] ?? '',
        'formacao' => $_POST['formacao'] ?? ''
    ],
    'experiencia_negocios' => [
        'motivo_bebidas' => $_POST['motivo_bebidas'] ?? '',
        'experiencia_bebidas' => $_POST['experiencia_bebidas'] ?? '',
        'detalhes_bebidas' => $_POST['detalhes_bebidas'] ?? '',
        'experiencia_franquia' => $_POST['experiencia_franquia'] ?? '',
        'detalhes_franquia' => $_POST['detalhes_franquia'] ?? '',
        'possui_empresas' => $_POST['possui_empresas'] ?? '',
        'lista_empresas' => $_POST['lista_empresas'] ?? ''
    ],
    'interesse_franquia' => [
        'cidade_interesse' => $_POST['cidade_interesse'] ?? '',
        'estado_interesse' => $_POST['estado_interesse'] ?? '',
        'bairro_interesse' => $_POST['bairro_interesse'] ?? '',
        'tipo_local' => $_POST['tipo_local'] ?? '',
        'ja_possui_ponto' => $_POST['ja_possui_ponto'] ?? '',
        'detalhes_ponto' => $_POST['detalhes_ponto'] ?? ''
    ],
    'capacidade_investimento' => [
        'capital_disponivel' => $_POST['capital_disponivel'] ?? '',
        'prazo_implantacao' => $_POST['prazo_implantacao'] ?? '',
        'dedicacao' => $_POST['dedicacao'] ?? ''
    ],
    'informacoes_adicionais' => [
        'como_conheceu' => $_POST['como_conheceu'] ?? '',
        'expectativas' => $_POST['expectativas'] ?? '',
        'observacoes' => $_POST['observacoes'] ?? ''
    ],
    'aceite_lgpd' => isset($_POST['aceite_lgpd']) ? 'sim' : 'nao'
];

// Salvar em arquivo JSON
$existing_data = [];
if (file_exists($data_file)) {
    $existing_data = json_decode(file_get_contents($data_file), true);
    if (!is_array($existing_data)) {
        $existing_data = [];
    }
}

$existing_data[] = $data;
file_put_contents($data_file, json_encode($existing_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Enviar e-mail
$subject = "Novo Cadastro de Franqueado - " . $data['dados_pessoais']['nome'];
$message = "Novo cadastro de interessado em franquia Chopp ON\n\n";
$message .= "=== DADOS PESSOAIS ===\n";
$message .= "Nome: " . $data['dados_pessoais']['nome'] . "\n";
$message .= "E-mail: " . $data['dados_pessoais']['email'] . "\n";
$message .= "Telefone: " . $data['dados_pessoais']['telefone'] . "\n";
$message .= "Idade: " . $data['dados_pessoais']['idade'] . "\n";
$message .= "CPF: " . $data['dados_pessoais']['cpf'] . "\n";
$message .= "Cidade/Estado: " . $data['dados_pessoais']['cidade'] . "/" . $data['dados_pessoais']['estado'] . "\n\n";

$message .= "=== PERFIL PROFISSIONAL ===\n";
$message .= "Resumo: " . $data['perfil_profissional']['resumo_perfil'] . "\n";
$message .= "Experiências: " . $data['perfil_profissional']['experiencias_anteriores'] . "\n";
$message .= "Formação: " . $data['perfil_profissional']['formacao'] . "\n\n";

$message .= "=== EXPERIÊNCIA EM NEGÓCIOS ===\n";
$message .= "Motivo bebidas: " . $data['experiencia_negocios']['motivo_bebidas'] . "\n";
$message .= "Experiência em bebidas: " . $data['experiencia_negocios']['experiencia_bebidas'] . "\n";
if ($data['experiencia_negocios']['detalhes_bebidas']) {
    $message .= "Detalhes: " . $data['experiencia_negocios']['detalhes_bebidas'] . "\n";
}
$message .= "Experiência em franquia: " . $data['experiencia_negocios']['experiencia_franquia'] . "\n";
if ($data['experiencia_negocios']['detalhes_franquia']) {
    $message .= "Franquias anteriores: " . $data['experiencia_negocios']['detalhes_franquia'] . "\n";
}
$message .= "Possui empresas: " . $data['experiencia_negocios']['possui_empresas'] . "\n";
if ($data['experiencia_negocios']['lista_empresas']) {
    $message .= "Empresas:\n" . $data['experiencia_negocios']['lista_empresas'] . "\n";
}
$message .= "\n";

$message .= "=== INTERESSE NA FRANQUIA ===\n";
$message .= "Cidade: " . $data['interesse_franquia']['cidade_interesse'] . "/" . $data['interesse_franquia']['estado_interesse'] . "\n";
$message .= "Bairro: " . $data['interesse_franquia']['bairro_interesse'] . "\n";
$message .= "Tipo de local: " . $data['interesse_franquia']['tipo_local'] . "\n";
$message .= "Possui ponto: " . $data['interesse_franquia']['ja_possui_ponto'] . "\n";
if ($data['interesse_franquia']['detalhes_ponto']) {
    $message .= "Detalhes do ponto: " . $data['interesse_franquia']['detalhes_ponto'] . "\n";
}
$message .= "\n";

$message .= "=== CAPACIDADE DE INVESTIMENTO ===\n";
$message .= "Capital disponível: " . $data['capacidade_investimento']['capital_disponivel'] . "\n";
$message .= "Prazo implantação: " . $data['capacidade_investimento']['prazo_implantacao'] . "\n";
$message .= "Dedicação: " . $data['capacidade_investimento']['dedicacao'] . "\n\n";

$message .= "=== INFORMAÇÕES ADICIONAIS ===\n";
$message .= "Como conheceu: " . $data['informacoes_adicionais']['como_conheceu'] . "\n";
$message .= "Expectativas: " . $data['informacoes_adicionais']['expectativas'] . "\n";
if ($data['informacoes_adicionais']['observacoes']) {
    $message .= "Observações: " . $data['informacoes_adicionais']['observacoes'] . "\n";
}

$headers = "From: noreply@choppon24h.com.br\r\n";
$headers .= "Reply-To: " . $data['dados_pessoais']['email'] . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($email_to, $subject, $message, $headers);

echo json_encode(['success' => true, 'message' => 'Cadastro enviado com sucesso']);
?>
