<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Psr\Log\LogLevel;
use App\Services\MqttService;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


class ImportExcelDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //mosquitto_sub -h localhost -t "chat"

    protected $filePath;
    protected $userEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $userEmail)
    {
        $this->filePath = $filePath;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get full file path
        $fullPath = Storage::path($this->filePath);

        // Import the file using the file path
        Excel::import(new UsersImport, $fullPath);

        // Delete temporary file after processing
        Storage::delete($this->filePath);

        try
        {
            // $message = "Archivo subido correctamente";
            $message = "Archivo subido correctamente por: " . $this->userEmail;

            $client = new MqttClient("127.0.0.1", 1883, 'test-publisher', MqttClient::MQTT_3_1, null);
            $client->connect(null, true);
            $client->publish('chat', $message, MqttClient::QOS_EXACTLY_ONCE);
            $client->loop(true, true);
            $client->disconnect();

        } catch (MqttClientException $e) {
            Log::error('Error al publicar en MQTT: ' . $e->getMessage());
        }
    }
}
