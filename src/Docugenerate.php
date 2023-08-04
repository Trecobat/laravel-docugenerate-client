<?php

namespace Trecobat\LaravelDocugenerateClient;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Http;

/**
 * Classe permettant de générer un document sur l'API docugenerate.
 */
class Docugenerate
{
    private string $apiKey;
    private string $templateId;
    private array $data;
    private ?string $documentName;
    private ?string  $outputName;
    private ?string  $output_format;
    private bool $singleFile = true;
    private bool $pageBreak = true;


    /**
     * Construction du client
     */
    public function __construct(string $apiKey, string $templateId)
    {
        $this->apiKey = $apiKey;
        $this->templateId = $templateId;
    }

    /**
     * Associaation des données permettant de générer mon doc prérempplies.
     *
     * @param array $data
     * @return $this
     */
    public function data(array $data){
        $this->data($data);
        return $this;
    }

    /**
     * Génération du document
     *
     * @param string $name
     * @return DocugenerateResponsePostDocument
     */
    public function generate( string $name = "" ){


        $client = new Client();
        $headers = [
            'Authorization' => '69c3ed719089f1c33af8d43b4deb133a'
        ];
        $options = [
            'multipart' => [
                [
                    'name' => 'template_id',
                    'contents' => '4VCizRDagJE13PxNfA9m'
                ],
                [
                    'name' => 'data',
                    'contents' => '[{ "non_client": "John", "non_prenom": "Doe" ,"date_naissance_client":"17/03/1981","ville_naissance_soussigne":"Angers","ville_client":"Le Relecq Kerhuon"}]'
                ],
                [
                    'name' => 'name',
                    'contents' => 'testPydViaPostman'
                ],
                [
                    'name' => 'output_format',
                    'contents' => '.pdf'
                ]
            ]];
            $request = new Request('POST', 'https://api.docugenerate.com/v1/document', $headers);
            $res = $client->sendAsync($request, $options)->wait();
            return new DocugenerateResponsePostDocument( $res->getBody() );
    }

    public function __toString(): string
    {
        return "DOCUGENERATE: \r\n\t apiKey => $this->apiKey";
    }


}
