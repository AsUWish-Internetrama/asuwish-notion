<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BlockHelper
{
    protected $_url;
    protected $_data = [
        'properties' => [],
        'children' => []
    ];

    /**
     * Construct function
     * 
     * @param HttpClientInterface $client
     * @param ParameterBagInterface $params
     */
    public function __construct(
        HttpClientInterface $client,
        ParameterBagInterface $params
    ) {
        $this->_client = $client;
        $this->_params = $params;
    }

    /**
     * Setter url function
     */
    public function setUrl($url) // /pages
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * Setter property function
     */
    public function setProperty($property)
    {
        $data = [];
        switch ($property['type']) {
            /** Title property block */
            case 'Text':
                $data = [
                    'title' => [
                        [
                            'text' => [
                                'content' => $property['data']
                            ]
                        ]
                    ]
                ];
                break;

            /** Text property block */
            case 'RichText':
                $data = [
                    'rich_text' => [
                        [
                            'text' => [
                                'content' => $property['data']
                            ]
                        ]
                    ]
                ];
                break;

            /** Select property block */
            case 'Select':
                $data = [
                    'select' => ['name' => $property['data']]
                ];
                break;

            /** Date property block */
            case 'Date': 
                $data = [
                    'start' => $property['data']
                ];
                break;

            /** Number property block */
            case 'Number':
                $data = [
                    'number' => $property['data']
                ];
                break;

            /** Url property block */
            case 'Url':
                $data = [
                    'url' => $property['data']
                ];
                break;
            
            /** Email property block */
            case 'Email':
                $data = [
                    'email' => $property['data']
                ];
                break;

            /** Checkbox property block */
            case 'Checkbox':
                $data = [
                    'checkbox' => $property['data']
                ];
                break;
        }

        $this->_data['properties'][] = [$property['title'] => $data];
        return $this;
    }

    /**
     * Setter children function
     */
    public function setChildren($children)
    {
        $data = [
            [
                'object' => 'block',
                'type' => 'heading_2',
                'heading_2' => [
                    'text' => [
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $children['title']
                            ]
                        ]
                    ]
                ]
            ],
            [
                'object' => 'block',
                'type' => 'paragraph',
                'paragraph' => [
                    'text' => [
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $children['data']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->_data['children'] = $data;
        return $this;
    }

    /**
     * Reset all params for other request
     */
    public function resetData()
    {
        $this->_url = null;
        $this->_data = [
            'properties' => [],
            'children' => []
        ];
        return $this;
    }

    /**
     * Execute call api for send page
     */
    public function sendRequest()
    {
        $properties = [];
        if (is_array($this->_data['properties']) && count($this->_data['properties']) >= 1) {
            foreach ($this->_data['properties'] as $key => $property) {
                $properties[key($property)] = array_values($property)[0];
            }
        }
        
        $response = $this->_client->request('POST', $this->_params->get('api_url').$this->_url, [
            'headers' => [
                'Accept' => 'application/json',
                'Notion-Version' => $this->_params->get('api_version'),
                'Authorization' => 'Bearer '.$this->_params->get('api_key')
            ],
            'json' => [
                'parent' => [
                    'database_id' => $this->_params->get('api_database_id')
                ],
                'properties' => $properties,
                'children' => $this->_data['children'] ? $this->_data['children'] : []
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            return true;
        }
        return $response->getStatusCode();
    }
}