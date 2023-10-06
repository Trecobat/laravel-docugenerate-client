<?php

namespace Trecobat\LaravelDocugenerateClient;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Classe permettant de générer un document sur l'API docugenerate.
 */
class Docugenerate
{
    private string $apiKey;
    private string $apiVerion = "v1";
    private string $templateId;
    private array $data;
    //private ?string $documentName;
    private ?string  $outputName = null;
    private ?string  $outputFormat = ".pdf";
    private bool $singleFile = true;
    private bool $pageBreak = true;

    /**
     * Construction du client
     */
    public function __construct(string $apiKey, string $templateId = null)
    {
        $this->apiKey = $apiKey;
        $this->templateId = $templateId;
    }

    public function getTags() {
        if(!$this->templateId) throw new \Exception("Vous devez spécifier l'id du Template Docugenerate pour pouvoir récupérer ses champs.");
        $client = new Client();
        $headers = [
            'Authorization' => $this->apiKey
        ];
        //$client->header('Authorization : ' . $this->apiKey);
        $request = new Request('GET', 'https://api.docugenerate.com/'.$this->apiVerion.'/template/' . $this->templateId, $headers);
        $res = $client->sendAsync($request)->wait();
        return json_decode( $res->getBody(), true )['tags']['valid'];
    }

    /**
     * Association des données permettant de générer mon doc prérempplies.
     * Possibilité de l'appeler autant de fois que l'on souhaite de doc pré-remplis
     *
     * @param array $data
     * @return $this
     */
    public function addData(array $data){
        $this->data[] = $data;
        return $this;
    }


    /**
     * Génération du document
     *
     * @param string $name
     * @return DocugenerateResponsePostDocument
     */
    public function generate( string $name = null ){

        $this->outputName = $name;
        $client = new Client();
        $headers = [
            'Authorization' => $this->apiKey
        ];
        $options = [
            'multipart' => [
                [
                    'name' => 'template_id',
                    'contents' => $this->templateId
                ],
                [
                    'name' => 'data',
                    'contents' => json_encode($this->data)
                ],
                [
                    'name' => 'output_format',
                    'contents' => $this->outputFormat
                ]
            ]
        ];
        if( $this->outputName ){
            $options = [
                'multipart' => [
                    [
                        'name' => 'output_name',
                        'contents' => $this->outputName
                    ]
                ]
            ];
        }

        $request = new Request('POST', 'https://api.docugenerate.com/'.$this->apiVerion.'/document', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        return new DocugenerateResponsePostDocument( $res->getBody() );
    }

    /**
     * Génération et téléchargement du document dans le navigateur
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($name = null){
        $doc = $this->generate($name);
        Log::debug("Ecriture du document généré en local pour pourvoir le télécharger.");
        Storage::disk('local')->put('/tmp/'.$doc->getFilename(), file_get_contents($doc->getDocUri()));
        return response()->download( Storage::path('/tmp/'.$doc->getFilename()));
    }

    /**
     * Représentation d'une objet DocuGerenate sous forme de string.
     */
    public function __toString(): string
    {
        return "DOCUGENERATE: \r\n\t".
            "templateId => $this->templateId, outputName => $this->outputName\t , outputFormat => $this->outputFormat\r\n\t".
            "singleFile => ".($this->singleFile ? "OUI" : "NON" ).", pageBreak => ".($this->pageBreak ? "OUI" : "NON" )."\t \r\n\t".
            "data => " . json_encode($this->data)
            ;
    }


}
