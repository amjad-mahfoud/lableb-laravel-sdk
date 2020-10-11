<?php

namespace Amjad\Lableb;

class LablebDocumentHelper
{
    /**
     * Converts document dates into ISO8601 format
     * 
     * @param document - the actual document
     * 
     * @return Array - the converted document
     */
    public function stringifyDocument($document)
    {
        $ret = [];
        foreach ($document as $key => $value) {
            if (empty($value)) continue;

            if (substr($key, -2) == "dt") {
                $ret[$key] = $this->toISOString($value);
            } else if (substr($key, -3) == "dta") {
                $ret[$key] = [];
                foreach ($value as $date) {
                    $ret[$key][] = $this->toISOString($date);
                }
            } else {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }

    /**
     * Maps an array of docs to stringified docs
     * 
     * @param docs - an array of documents
     * 
     * @return Array
     */
    public function stringifyDocuments($docs)
    {
        $ret = [];
        foreach ($docs as $doc) {
            $ret[] = $this->stringifyDocument($doc);
        }
        return $ret;
    }


    /**
     * Forms and adds feedbackUrl to the passed documents
     * 
     * @param url - Lableb's feedback url api
     * @param documents - an array of documents
     * @param params - additional params to include in the feedbackUrl url
     * 
     * @return Array
     */
    function addSearchFeedbackUrls($url, $documents, $params)
    {
        $ret = [];

        foreach ($documents as $order => $document) {
            $qs = array_merge($params, [
                'item_id' => $document['id'],
                'item_order' => $order + 1,
            ]);

            if (!empty($document['url'])) {
                $qs['url'] = $document['url'];
            }

            $ret[] = array_merge($document, [
                "feedbackUrl" => $url . "?" . http_build_query($qs)
            ]);
        }

        return $ret;
    }

    /**
     * Converts a DateTime object or a date string into date string of ISO8601 format
     * 
     * @param date - DateTime or date string
     * 
     * @return String
     */
    function toISOString($date)
    {
        $str = '';
        if (is_string($date)) {
            $str = \date_create($date)->format(\DateTime::ATOM);
        } else {
            $str = $date->format(\DateTime::ATOM);
        }
        return explode('+', $str)[0] . 'Z';
    }
}
