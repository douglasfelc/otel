<?php

require 'vendor/autoload.php';

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Metrics\MeterProviderInterface;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

// ─── Configuração do Prometheus ─────────────────────────────────────────────
$registry = new CollectorRegistry(new InMemory());
$requestsCounter = $registry->getOrRegisterCounter('app', 'requests_total', 'Total de requisições recebidas');
$errorsCounter = $registry->getOrRegisterCounter('app', 'errors_total', 'Total de erros lançados');

// ─── Configuração do OpenTelemetry (Meter) ──────────────────────────────────
$meterProvider = Globals::meterProvider();
/** @var MeterProviderInterface $meterProvider */
$meter = $meterProvider->getMeter('app');

$otelRequestsCounter = $meter->createCounter('requests_total', 'Total de requisições recebidas');
$otelErrorsCounter = $meter->createCounter('errors_total', 'Total de erros lançados');

// ─── Incrementa as métricas de requisição ───────────────────────────────────
$requestsCounter->inc();
$otelRequestsCounter->add(1);

// ─── Rota /metrics para Prometheus ──────────────────────────────────────────
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/metrics') {
    header('Content-Type: ' . RenderTextFormat::MIME_TYPE);
    $renderer = new RenderTextFormat();
    echo $renderer->render($registry->getMetricFamilySamples());
    exit;
}

// ─── Simulação de erro com throw ────────────────────────────────────────────
try {
    // Simulando um erro:
    throw new Exception("Simulação de erro na aplicação");

} catch (Throwable $e) {
    // Incrementa as métricas de erro
    $errorsCounter->inc();
    $otelErrorsCounter->add(1);

    // Retorna erro HTTP
    http_response_code(500);
    echo "Erro capturado: " . $e->getMessage();
    exit;
}

// Em caso de sucesso (não chegou a acontecer no exemplo acima)
echo "Métricas disponíveis em /metrics";
