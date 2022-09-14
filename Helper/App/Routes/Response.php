<?php

namespace Helper\App\Routes;

use Helper\App\Routes\Types\HTTP;
use Exception;
use Helper\Twig\Page;

class Response
{
    private const TYPE_HTML = 'text/html';
    private const TYPE_JSON = 'application/json';
    private const TYPE_XML = 'application/xml';
    private const TYPE_TEXT = 'text/plain';
    private const TYPE_PDF = 'application/pdf';
    private const TYPE_ZIP = 'application/zip';
    private const TYPE_CSV = 'text/csv';
    private const TYPE_XLS = 'application/vnd.ms-excel';
    private const TYPE_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    private const TYPE_DOC = 'application/msword';
    private const TYPE_DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    private const TYPE_PPT = 'application/vnd.ms-powerpoint';
    private const TYPE_PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    private const TYPE_ODT = 'application/vnd.oasis.opendocument.text';
    private const TYPE_ODS = 'application/vnd.oasis.opendocument.spreadsheet';
    private const TYPE_ODP = 'application/vnd.oasis.opendocument.presentation';

    private int $statusCode;
    private string $content;
    private array $headers;
    private string $response;
    private string $contentType;


    public function __construct(mixed $data = '')
    {
        // si le type de $data est un objet de type Exception
        if (is_a($data, Exception::class)) {
            $this->statusCode = $data->getCode();
            $this->content = $data->getMessage();
            $this->headers = [];
            $this->contentType = self::TYPE_TEXT;
        } else if (is_a($data, Page::class)) {
            /* @var Page $data */
            $this->statusCode = $data->getStatusCode();
            $this->content = $data->display();
            $this->headers = $data->getHeaders();
            $this->contentType = self::TYPE_HTML;
        } else {
            $this->statusCode = HTTP::OK;
            $this->content = $data;
            $this->headers = [];
            $this->contentType = self::TYPE_JSON;
        }
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function respond() : void
    {
        $this->response = $this->buildResponse();
        $this->sendResponse();
    }

    private function sendResponse(): void
    {
        header('Content-Type: ' . $this->contentType);
        header('HTTP/1.1 ' . $this->statusCode);
        foreach ($this->headers as $header) {
            header($header);
        }
        echo $this->response;
    }

    private function buildResponse(): bool|string
    {
        if ($this->contentType === self::TYPE_HTML){
            return $this->content;
        }
        return json_encode([
            'status' => $this->statusCode,
            'data' => $this->content
        ]);
    }
}