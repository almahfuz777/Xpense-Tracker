<?php
// app/controllers/CurrencyConverterController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class CurrencyConverterController extends Controller{

    public function index() {
        $this->requireLogin();
        $data = [
            'pageTitle' => 'Currency Converter | XpenseTracker',
            'page' => 'currencyConverter'
        ];
        $this->loadView('dashboard/currencyConverter', $data);
    }

    public function convert() {
        $base = $_GET['base'] ?? 'USD';
        $target = $_GET['target'] ?? 'BDT';
        $amount = floatval($_GET['amount'] ?? 1);

        // Fetch exchange rates
        $url = EXCHANGE_API_URL . urlencode($base);
        $response = @file_get_contents($url);

        if (!$response) {
            echo json_encode(["error" => "Failed to fetch exchange rates"]);
            exit;
        }

        $data = json_decode($response, true);

        if (!isset($data['result']) || $data['result'] !== 'success') {
            echo json_encode(['error' => 'API response invalid or failed.']);
            exit;
        }

        if (!isset($data['conversion_rates'][$target])) {
            echo json_encode(['error' => 'Invalid target currency.']);
            exit;
        }

        // Convert currency
        $rate = $data['conversion_rates'][$target];
        $convertedAmount = round($amount * $rate, 2);

        echo json_encode([
            "converted_amount" => round($convertedAmount, 2),
            'rate' => $rate,
            'target_currency' => $target
        ]);
        exit;
    }
}

// Instantiate and dispatch the controller
$currencyConverter = new CurrencyConverterController();
$currencyConverter->dispatch();
