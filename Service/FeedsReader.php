<?php

namespace EXS\FeedsChaturbateBundle\Service;

use GuzzleHttp\Client;

/**
 * Class FeedsReader
 *
 * @package EXS\FeedsChaturbateBundle\Service
 */
class FeedsReader
{
    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var int
     */
    private $defaultTtl;

    /**
     * FeedsReader constructor.
     *
     * @param \Memcached $memcached
     * @param Client     $httpClient
     * @param int        $defaultTtl
     */
    public function __construct(\Memcached $memcached, Client $httpClient, $defaultTtl = 300)
    {
        $this->memcached = $memcached;
        $this->httpClient = $httpClient;
        $this->defaultTtl = $defaultTtl;
    }

    /**
     * @return array
     */
    public function getLivePerformers()
    {
        if (
            (false === $performers = $this->memcached->get($this->getCacheKey()))
            || empty($performers)
        ) {
            $performers = $this->refreshLivePerformers();
        }

        return $performers;
    }

    /**
     * @param int $ttl
     *
     * @return array
     */
    public function refreshLivePerformers($ttl = null)
    {
        $performers = [];

        try {
            $response = $this->httpClient->get('http://chaturbate.com/affiliates/api/onlinerooms/?format=json&wm=pl1vV', [
                'headers' => ['Accept' => 'application/json'],
                'timeout' => 10.0,
                'http_errors' => false,
            ]);

            if (200 === $response->getStatusCode()) {
                /**
                 * Regular Replace.
                 * CAREFUL: takes more CPU time
                 */
                $regexRemove = array(
                    'room_subject' => array('/\[\s*\d+\s+(tokens|left|remaining)[^\]]*\]/i', ''),
                );

                /**
                 * List of characters to remove.
                 */
                $removeCharacters = array(
                    'room_subject' => array("'", '"', ']', '[', ')', '(', '@', '%', '=', '+'),
                    'location' => array("'", '"', ']', '[', ')', '(', '@', '%', '=', '+'),
                    'display_name' => array("'", '"', ']', '[', ')', '(', '@', '%', '=', '+'),
                );

                $responseContent = $response->getBody()->getContents();

                $performers = json_decode($responseContent, true);

                if (is_array($performers) && !empty($performers)) {
                    foreach ($performers as $i => $model) {
                        foreach (array(
                            'block_from_states', 'chat_room_url_revshare', 'block_from_countries',
                            'current_show', 'recorded', 'iframe_embed_revshare', 'chat_room_url', 'iframe_embed',
                            'image_url_360x270', 'image_url', 'is_new', 'birthday'
                        ) as $to_delete) {
                            if (isset($model[$to_delete])) {
                                unset($performers[$i][$to_delete]);
                                unset($model[$to_delete]);
                            }
                        }

                        foreach ($regexRemove as $attribute => $regex) {
                            list($regexPattern, $replacement) = $regex;
                            if (isset($model[$attribute])) {
                                $performers[$i][$attribute] = preg_replace($regexPattern, $replacement, $model[$attribute]);
                                $model[$attribute] = $performers[$i][$attribute];
                            }
                        }

                        foreach ($removeCharacters as $attribute => $charactersToRemove) {
                            if (isset($model[$attribute])) {
                                $performers[$i][$attribute] = str_replace($charactersToRemove, ' ', $model[$attribute]);
                                $model[$attribute] = $performers[$i][$attribute];
                            }
                        }
                    }

                    $this->memcached->set($this->getCacheKey(), $performers, $ttl ?: $this->defaultTtl);
                }
            }
        } catch (\Exception $e) {
            $performers = [];
        }

        return $performers;
    }

    /**
     * @return string
     */
    private function getCacheKey()
    {
        return sprintf('ChaturbateLivePerformers');
    }
}
