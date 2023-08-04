<?php

namespace Trecobat\LaravelDocugenerateClient;

use GuzzleHttp\Promise\Promise;

class DocugenerateResponsePostDocument
{
    private array $response;
    /**
     {
    "id": "1QgtWZEVJRIajmAQ5smb",
    "template_id": "1moguLIQFZKrUrMxhyCqXtS3XHQ9",
    "created": 1651830624284,
    "name": "Monthly Invoices",
    "data_length": 2,
    "filename": "Invoices.docx",
    "format": ".docx",
    "document_uri": "https://storage.googleapis.com/docugenerate.appspot.com/templates/8fOQ0LgUB8V2ZqqUM763/Invoice.docx"
    }
     */

    public function __construct(String $response)
    {
        $this->response = json_decode( $response, true );
    }

    /**
     * @return array|mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * @return String
     */
    public function getId(){
        return $this->response['id'];
    }

    /**
     * @return String
     */
    public function getTemplateId(){
        return $this->response['template_id'];
    }

    /**
     * @return String
     */
    public function getCreated(){
        return $this->response['created'];
    }

    /**
     * @return String
     */
    public function getName(){
        return $this->response['name'];
    }

    /**
     * @return String
     */
    public function getDataLength(){
        return $this->response['data_length'];
    }

    /**
     * @return String
     */
    public function getFilename(){
        return $this->response['filename'];
    }

    /**
     * @return String
     */
    public function getFormat(){
        return $this->response['format'];
    }

    /**
     * @return String
     */
    public function getDocUri(){
        return $this->response['document_uri'];
    }
}
