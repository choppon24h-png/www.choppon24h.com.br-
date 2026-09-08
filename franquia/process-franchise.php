<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

function respond(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function text_value(string $key, int $max = 800): string {
    $value = trim((string)($_POST[$key] ?? ''));
    $normalized = preg_replace('/\s+/u', ' ', $value) ?? '';
    return function_exists('mb_substr') ? mb_substr($normalized, 0, $max) : substr($normalized, 0, $max);
}

function valid_cpf(string $cpf): bool {
    $cpf = preg_replace('/\D/', '', $cpf) ?? '';
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1+$/', $cpf)) return false;
    for ($position = 9; $position < 11; $position++) {
        $sum = 0;
        for ($index = 0; $index < $position; $index++) $sum += (int)$cpf[$index] * (($position + 1) - $index);
        $digit = (($sum * 10) % 11) % 10;
        if ((int)$cpf[$position] !== $digit) return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, ['success' => false, 'message' => 'Método não permitido.']);
if (text_value('website', 100) !== '') respond(400, ['success' => false, 'message' => 'Não foi possível concluir o envio.']);

session_start();
$now = time();
if (isset($_SESSION['franchise_last_submit']) && $now - (int)$_SESSION['franchise_last_submit'] < 45) {
    respond(429, ['success' => false, 'message' => 'Aguarde alguns segundos antes de enviar novamente.']);
}

$allowed = [
    'capital_disponivel' => ['ate_17', '18', '35', '100', '120', '200'],
    'origem_recursos' => ['recursos_proprios', 'sociedade', 'credito', 'combinado'],
    'experiencia_negocios' => ['empreendedor', 'gestao', 'investidor', 'primeiro_negocio'],
    'dedicacao' => ['integral', 'parcial', 'gestor', 'sociedade'],
    'prazo_implantacao' => ['ate_3', '3_6', '6_12', 'pesquisa'],
    'objetivo' => ['novo_negocio', 'diversificar', 'renda', 'expandir'],
    'status_ponto' => ['nao_possui', 'mapeando', 'negociando', 'proprio'],
    'tipo_ponto' => ['rua', 'condominio', 'empresa', 'shopping', 'evento', 'delivery'],
    'fluxo' => ['baixo', 'medio', 'alto', 'nao_sei'],
    'metragem' => ['', 'compacto', 'medio', 'amplo'],
    'infraestrutura' => ['', 'sim', 'parcial', 'nao'],
    'modelo_interesse' => ['station', 'smart', 'smart_compact', 'delivery'],
];

$requiredText = ['nome', 'email', 'telefone', 'cidade_residencia', 'cidade_interesse', 'uf_interesse'];
$lead = [];
foreach ($requiredText as $field) {
    $lead[$field] = text_value($field, 160);
    if ($lead[$field] === '') respond(422, ['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']);
}

if (!filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) respond(422, ['success' => false, 'message' => 'Informe um e-mail válido.']);
if (!preg_match('/^[A-Z]{2}$/', $lead['uf_interesse'])) respond(422, ['success' => false, 'message' => 'Informe uma UF válida.']);

$cpf = preg_replace('/\D/', '', text_value('cpf', 20)) ?? '';
if (!valid_cpf($cpf)) respond(422, ['success' => false, 'message' => 'Informe um CPF válido.']);

foreach ($allowed as $field => $values) {
    $value = text_value($field, 80);
    if (!in_array($value, $values, true)) respond(422, ['success' => false, 'message' => 'Revise as respostas obrigatórias antes de enviar.']);
    $lead[$field] = $value;
}

if (text_value('aceite_lgpd', 10) !== 'sim') respond(422, ['success' => false, 'message' => 'É necessário autorizar o tratamento dos dados para a análise.']);

$lead['experiencia_setor'] = text_value('experiencia_setor');
$lead['detalhes_ponto'] = text_value('detalhes_ponto');
$lead['cpf_hash'] = hash('sha256', $cpf);
$lead['cpf_final'] = substr($cpf, -4);
$lead['submitted_at'] = gmdate('c');
$lead['source'] = 'franquia/franquia-form.html';
$lead['protocol'] = 'FRQ-' . gmdate('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

// A pasta fica dois níveis acima de /franquia, fora do Document Root esperado.
$privateDirectory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'choppon-private' . DIRECTORY_SEPARATOR . 'franchise-leads';
if (!is_dir($privateDirectory) && !mkdir($privateDirectory, 0700, true) && !is_dir($privateDirectory)) {
    respond(500, ['success' => false, 'message' => 'O recebimento está temporariamente indisponível. Tente novamente mais tarde.']);
}
@chmod($privateDirectory, 0700);
$temporaryFile = tempnam($privateDirectory, 'pending-');
if ($temporaryFile === false) respond(500, ['success' => false, 'message' => 'Não foi possível registrar sua solicitação.']);
$finalFile = $privateDirectory . DIRECTORY_SEPARATOR . strtolower($lead['protocol']) . '.json';
$written = file_put_contents($temporaryFile, json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
if ($written === false || !rename($temporaryFile, $finalFile)) {
    @unlink($temporaryFile);
    respond(500, ['success' => false, 'message' => 'Não foi possível registrar sua solicitação.']);
}
@chmod($finalFile, 0600);
$_SESSION['franchise_last_submit'] = $now;
respond(201, ['success' => true, 'protocol' => $lead['protocol']]);
?>
