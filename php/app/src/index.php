<?php

require 'vendor/autoload.php';

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Metrics\MeterProviderInterface;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

// Configuração do Prometheus
$registry = new CollectorRegistry(new InMemory());
$counter = $registry->getOrRegisterCounter('app', 'requests_total', 'Total de requisições recebidas');

// Configuração do OpenTelemetry
$meterProvider = Globals::meterProvider();
$meter = $meterProvider->getMeter('app');
$requestCounter = $meter->createCounter('requests_total', 'Total de requisições recebidas');

// Incrementa as métricas
$counter->inc();
$requestCounter->add(1);

// Verifica se a URI é '/metrics' e expõe as métricas no formato Prometheus
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/metrics') {
    header('Content-Type: ' . RenderTextFormat::MIME_TYPE);
    $renderer = new RenderTextFormat();
    echo $renderer->render($registry->getMetricFamilySamples());
    exit;
}

// Exibe mensagem padrão para qualquer outra requisição
echo "Métricas disponíveis em /metrics";
