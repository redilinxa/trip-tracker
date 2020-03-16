<?php


namespace App\Controller\API;

use App\Service\API\APIResponseFormatter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BaseController extends AbstractFOSRestController
{
    protected $apiResponseFormatter;

    public function __construct(APIResponseFormatter $APIResponseFormatter)
    {
        $this->apiResponseFormatter = $APIResponseFormatter;
    }

    protected function successView($data = "", $message = "", $header_code = 200)
    {
        $data = $this->apiResponseFormatter->format($header_code, $data, $message, 200);
        return $this->view($data, $header_code);
    }

    /**
     * @param \Exception $exception
     * @return \FOS\RestBundle\View\View
     */
    protected function failView(\Exception $exception)
    {
        $data = $this->formatException($exception);
        return  $this->view($this->formatException($exception), $data['code']);
    }

    /**
     * @param \Exception $exception
     * @return \FOS\RestBundle\View\View
     */
    protected function invalidView($data = "", $message = "", $header_code = JsonResponse::HTTP_BAD_REQUEST)
    {
        $data = $this->apiResponseFormatter->format($header_code, $data, $message, JsonResponse::HTTP_BAD_REQUEST);
        return $this->view($data, $header_code);
    }

    /**
     * @param \Exception $exception
     * @return array
     */
    private function formatException(\Exception $exception)
    {
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof AccessDeniedException) {
            $statusCode = $exception->getCode();
        } else {
            $statusCode = $exception->getCode() ? JsonResponse::HTTP_BAD_REQUEST : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->apiResponseFormatter->format($statusCode, null, $exception->getMessage(), $exception->getCode());
    }

    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}