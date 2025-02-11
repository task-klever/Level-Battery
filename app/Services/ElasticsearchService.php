<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        // Get Elasticsearch host from the .env file
        $elasticsearchHost = env('ELASTICSEARCH_HOST', 'localhost:9200'); // default to localhost:9200 if not set in .env

        // Set the Elasticsearch host dynamically from the .env file
        $this->client = ClientBuilder::create()->setHosts([$elasticsearchHost])->build();
    }

    public function createIndex()
    {
        try {
            $params = [
                'index' => 'products',
                'body'  => [
                    'mappings' => [
                        'properties' => [
                            'product_name' => ['type' => 'text'],
                            'product_slug' => ['type' => 'keyword'],
                            'product_content_short' => ['type' => 'text'],
                            'product_status' => ['type' => 'keyword'],
                            'product_content' => ['type' => 'text'],
                            'seo_title' => ['type' => 'text'],
                            'seo_meta_description' => ['type' => 'text'],

                            'width' => ['type' => 'text'], 'height' => ['type' => 'text'], 'rim' => ['type' => 'text'], 'runflat' => ['type' => 'text'], 'load_speed' => ['type' => 'text'], 
                            'year' => ['type' => 'integer'],
                            'brand_id' => ['type' => 'integer'], 
                            'pattern_id' => ['type' => 'integer'], 
                            'oem_id' => ['type' => 'integer'], 
                            'origin_id' => ['type' => 'integer']
                        ]
                    ]
                ]
            ];
    
            return $this->client->indices()->create($params);
        } catch (\Exception $e) {
            // Handle exception, log error, or return failure
            \Log::error('Error creating index in Elasticsearch: ' . $e->getMessage());
            return null; // or handle as needed
        }
    }

    public function indexProduct($product)
    {
        $params = [
            'index' => 'products',
            'id'    => $product->id,
            'body'  => [
                'product_name' => $product->product_name,
                'product_slug' => $product->product_slug,
                'product_content_short' => $product->product_content_short,
                'product_status' => $product->product_status,
                'product_content' => $product->product_content,
                'seo_title' => $product->seo_title,
                'seo_meta_description' => $product->seo_meta_description,

                'width' => $product->width, 'height' => $product->height, 
                'rim' => $product->rim, 'runflat' => $product->runflat, 
                'load_speed' => $product->load_speed, 
                'year' => $product->year,
                'brand_id' => $product->brand_id, 
                'pattern_id' => $product->pattern_id, 
                'oem_id' => $product->oem_id, 
                'origin_id' => $product->origin_id
            ]
        ];

        return $this->client->index($params);
    }

    public function searchProducts($query = null, $filters = [])
    {
        $finalQuery = [
            'bool' => [
                'must' => [],
                'filter' => [] // Initialize filter array
            ]
        ];

        // Only add a multi_match query if $query is not null
        if ($query !== null) {
            $finalQuery['bool']['must'][] = [
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['product_name', 'product_content_short', 'product_content', 'seo_title', 'seo_meta_description']
                ]
            ];
        }
    
        // Add filters if provided
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $finalQuery['bool']['filter'][] = [
                    'term' => [$field => $value] // Add each filter as a term query
                ];
            }
        }

        try {
            return $this->client->search([
                'index' => 'products',
                'body' => [
                    'query' => $finalQuery // Use the constructed finalQuery
                ]
            ]);
        } catch (\Exception $e) {
            // Handle exception, log error, or return failure
            \Log::error('Error searching products in Elasticsearch: ' . $e->getMessage());
            return []; // or handle as needed
        }
    }

    /**
     * Remove product from Elasticsearch index
     */
    public function removeProductFromElasticsearch($productId)
    {
        // Assuming you have an 'id' field to uniquely identify products in Elasticsearch
        $params = [
            'index' => 'products', // Specify the Elasticsearch index
            'id' => $productId,   // The ID of the product to delete
        ];

        try {
            $this->client->delete($params);
        } catch (\Exception $e) {
            // Handle exception, log error, or return failure
            \Log::error('Error removing product from Elasticsearch: ' . $e->getMessage());
        }
    }

    public function getAllIndexedProductIds()
    {
        $params = [
            'index' => 'products',  // Elasticsearch index
            'size'  => 10000,  // Adjust based on your needs, or implement pagination
            '_source' => false,  // Only get the _id field, no need for the full source
        ];

        try {
            $response = $this->client->search($params);
            $productIds = [];

            // Collect all product IDs from the search results
            foreach ($response['hits']['hits'] as $hit) {
                $productIds[] = $hit['_id'];
            }

            return $productIds;
        } catch (\Exception $e) {
            \Log::error('Error fetching indexed product IDs: ' . $e->getMessage());
            return [];
        }
    }

}
