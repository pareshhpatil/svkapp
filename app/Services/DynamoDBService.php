<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class DynamoDBService
{
    protected $client;
    protected $marshaler;
    protected $table;

    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'region' => config('services.aws.region', 'ap-south-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);

        $this->marshaler = new Marshaler();
        $this->table = config('services.aws.dynamodb_table'); // Optional
    }

    public function getItem(string $keyName, int $keyValue, string $tableName = null): ?array
    {
        $tableName = $tableName ?? $this->table;

        $key = $this->marshaler->marshalJson(json_encode([
            $keyName => $keyValue
        ]));

        $result = $this->client->getItem([
            'TableName' => $tableName,
            'Key' => $key,
        ]);

        return isset($result['Item']) ? $this->marshaler->unmarshalItem($result['Item']) : null;
    }

    public function getItemsByRideIds(array $rideIds, $tableName = 'ride_track_live')
    {
        $keys = array_map(function ($rideId) {
            return ['data' => ['N' => (string) $rideId]];
        }, $rideIds);

        $params = [
            'RequestItems' => [
                $tableName => [
                    'Keys' => $keys
                ]
            ]
        ];

        $result = $this->client->batchGetItem($params);

        $items = $result['Responses'][$tableName] ?? [];

        return array_map(function ($item) {
            return $this->marshaler->unmarshalItem($item);
        }, $items);
    }

    public function getAllRows(string $keyName, int $dataKey, string $tableName = null): ?array
    {
        $tableName = $tableName ?? $this->table;


        $key = $this->marshaler->marshalJson(json_encode([
            $keyName => $dataKey
        ]));

        $result = $this->client->query([
            'TableName' => $tableName,
            'KeyConditionExpression' => '#data = :dataVal',
            'ExpressionAttributeNames' => [
                '#data' => 'data',  // Alias for the reserved word 'data'
            ],
            'ExpressionAttributeValues' => [
                ':dataVal' => ['N' => (string)$dataKey],
            ],
        ]);

        return $items = array_map([$this->marshaler, 'unmarshalItem'], $result['Items']);
    }

    public function scan(string $tableName = null): array
    {
        $tableName = $tableName ?? $this->table;

        $result = $this->client->scan([
            'TableName' => $tableName,
        ]);

        $items = [];
        foreach ($result['Items'] as $item) {
            $items[] = $this->marshaler->unmarshalItem($item);
        }

        return $items;
    }
}
