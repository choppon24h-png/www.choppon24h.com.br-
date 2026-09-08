<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, max-age=0');

function respond(int $status, array $payload): void { http_response_code($status); echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); exit; }
function value(string $key, int $max = 160): string { $raw = trim((string)($_POST[$key] ?? '')); $normal = preg_replace('/\s+/u', ' ', $raw) ?? ''; return function_exists('mb_substr') ? mb_substr($normal, 0, $max) : substr($normal, 0, $max); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, ['success' => false, 'message' => 'Método não permitido.']);
if (value('website', 100) !== '') respond(400, ['success' => false, 'message' => 'Não foi possível concluir o envio.']);

session_start();
$now = time();
if (isset($_SESSION['delivery_last_submit']) && $now - (int)$_SESSION['delivery_last_submit'] < 45) respond(429, ['success' => false, 'message' => 'Aguarde alguns segundos antes de enviar novamente.']);

$lead = [];
foreach (['name', 'whatsapp', 'city', 'state', 'email'] as $field) { $lead[$field] = value($field); if ($lead[$field] === '') respond(422, ['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']); }
if (!filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) respond(422, ['success' => false, 'message' => 'Informe um e-mail válido.']);
if (!preg_match('/^[A-Z]{2}$/', $lead['state'])) respond(422, ['success' => false, 'message' => 'Informe uma UF válida.']);
if (strlen(preg_replace('/\D/', '', $lead['whatsapp']) ?? '') < 10) respond(422, ['success' => false, 'message' => 'Informe um WhatsApp válido.']);
foreach (['events_experience' => ['sim', 'nao'], 'delivery_experience' => ['sim', 'nao'], 'objective' => ['renda_extra', 'novo_negocio', 'eventos', 'expandir', 'outro']] as $field => $allowed) { $lead[$field] = value($field, 40); if (!in_array($lead[$field], $allowed, true)) respond(422, ['success' => false, 'message' => 'Revise as respostas obrigatórias antes de enviar.']); }
if (value('lgpd', 10) !== 'sim') respond(422, ['success' => false, 'message' => 'É necessário autorizar o tratamento dos dados para a análise.']);

$lead['submitted_at'] = gmdate('c');
$lead['source'] = 'delivery/index.html';
$lead['protocol'] = 'DLV-' . gmdate('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
$privateDirectory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'choppon-private' . DIRECTORY_SEPARATOR . 'delivery-leads';
if (!is_dir($privateDirectory) && !mkdir($privateDirectory, 0700, true) && !is_dir($privateDirectory)) respond(500, ['success' => false, 'message' => 'O recebimento está temporariamente indisponível. Tente novamente mais tarde.']);
@chmod($privateDirectory, 0700);
$temporaryFile = tempnam($privateDirectory, 'pending-');
if ($temporaryFile === false) respond(500, ['success' => false, 'message' => 'Não foi possível registrar sua solicitação.']);
$finalFile = $privateDirectory . DIRECTORY_SEPARATOR . strtolower($lead['protocol']) . '.json';
$written = file_put_contents($temporaryFile, json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
if ($written === false || !rename($temporaryFile, $finalFile)) { @unlink($temporaryFile); respond(500, ['success' => false, 'message' => 'Não foi possível registrar sua solicitação.']); }
@chmod($finalFile, 0600);
$_SESSION['delivery_last_submit'] = $now;
respond(201, ['success' => true, 'protocol' => $lead['protocol']]);
?>
