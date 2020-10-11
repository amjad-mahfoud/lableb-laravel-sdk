<?php

namespace Amjad\Lableb;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LablebSDK
{
    private $searchToken;
    private $projectName;

    public function __construct($projectName)
    {
        $this->searchToken = config('lableb.token');//$searchToken;
        $this->projectName = $projectName;
        $this->helper = new LablebDocumentHelper();
        $this->baseUrl = "https://api-bahuth.lableb.com/api/v1/$this->projectName/";

        $this->client = new Client(["base_uri" => $this->baseUrl]);
        $this->sessionID = (new LablebSessionManager())->getSessionID();
    }

    public function setToken($searchToken)
    {
        $this->searchToken = $searchToken;
    }

    /**
     * Submits a search result click feedback
     * 
     * @param collection - collection name on Lableb
     * @param params - an associative array of feedback parameters
     * @param handler - what search handler was used in the search results
     * 
     * @return Array
     * @throws LablebException
     */
    public function submitSearchFeedback($collection, $params, $handler = 'default')
    {
        try {
            $res = $this->client->request('GET', "collections/$collection/search/$handler/feedback/hits", [
                "query" => array_merge($params, [
                    'token' => $this->searchToken,
                    "session_id" => $this->sessionID
                ])
            ]);

            return ["submitted" => true];
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Searches documents on Lableb
     * 
     * @param collection - collection name on Lableb
     * @param query - an associative array of search query parameters
     * @param handler - an optional parameter which refers to the search handler
     * 
     * @return Array
     * @throws LablebException
     */
    public function search($collection, $query, $handler = "default")
    {
        if (empty($query["q"])) {
            throw new \Exception("\"q\" is required in search query");
        }

        try {
            $qs = $this->buildQueryString(array_merge($query, ["token" => $this->searchToken, "session_id" => $this->sessionID]));
            $response = $this->client->request("GET", "collections/$collection/search/$handler", [
                "query" => $qs,
            ]);

            $body = json_decode($response->getBody(), true)["response"];

            $facets = [];
            $facetsCount = 0;

            if (!empty($body["facets"]) && is_array($body["facets"])) {
                $facetsCount = $body["facets"]["count"];
                foreach ($body["facets"] as $name => $values) {
                    if (is_array($values)) $facets[$name] = $values["buckets"];
                }
            }

            $feedbackUrl = $this->baseUrl . "collections/$collection/search/$handler/feedback/hits";

            return [
                "totalDocuments" => $body["found_documents"],
                "results" => $this->helper->addSearchFeedbackUrls(
                    $feedbackUrl,
                    $body["results"],
                    [
                        'token' => $this->searchToken,
                        'query' => $query['q'],
                        "session_id" => $this->sessionID
                    ]
                ),
                "totalFacets" => $facetsCount,
                "facets" => $facets
            ];
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Handles the Request Exception, it converts the exception into LablebException and then throws it
     * 
     * @param e - Request Exception
     * 
     * @throws LablebException
     */
    private function handleRequestException($e)
    {
        if ($e->hasResponse()) {
            $body = json_decode($e->getResponse()->getBody(), true);
            throw new LablebException($e->getResponse()->getStatusCode());
        } else {
            throw new LablebException(500);
        }
    }


    /**
     * Passed an array of key-value pairs, it converts it into a compatible querystring with Lableb api
     *
     * @param [Array] $qs
     * @return String
     */
    private function buildQueryString($qs)
    {
        $query = "";
        foreach ($qs as $key => $value) {
            if (empty($value))
                continue;
            if (is_array($value)) {
                if (!isset($value[0])) {
                    foreach ($value as $elKey => $elValue) {
                        if (is_array($elValue)) {
                            foreach ($elValue as $el) {
                                $query .= "&" . http_build_query([$key => $elKey . ":\"$el\""]);
                            }
                        } else {
                            $query .= "&" . http_build_query([$key => $elKey . ":\"$elValue\""]);
                        }
                    }
                } else {
                    foreach ($value as $el) {
                        $query .= "&" . http_build_query([$key => $el]);
                    }
                }
            } else {
                $query .= "&" . http_build_query([$key => $value]);
            }
        }

        return substr($query, 1);
    }
}
