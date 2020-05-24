<?php

namespace PHPCuong\CustomerAccount\Controller\Customer\Ajax;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Json\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Forgotpassword
 *
 * @category Magento
 * @package  PHPCuong_CustomerAccount
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @link     http://github.com/santanaluc94
 */
class Forgotpassword extends \Magento\Customer\Controller\AbstractAccount implements HttpPostActionInterface
{
    /**
     * Session
     *
     * @var Session
     */
    protected $session;

    /**
     * Account Management Interface
     *
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * Raw Factory
     *
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * Data
     *
     * @var Data
     */
    protected $helper;

    /**
     * Customer Repository Interface
     *
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Json Factory
     *
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Manager Interface
     *
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Forgot password constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     * @param RawFactory $resultRawFactory
     * @param Data $helper
     * @param CustomerRepositoryInterface $customerRepository
     * @param JsonFactory $resultJsonFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        RawFactory $resultRawFactory,
        Data $helper,
        CustomerRepositoryInterface $customerRepository,
        JsonFactory $resultJsonFactory,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->resultRawFactory = $resultRawFactory;
        $this->helper = $helper;
        $this->customerRepository = $customerRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Forgot customer password action
     */
    public function execute()
    {
        $resultRaw = $this->resultRawFactory->create();
        $httpBadRequestCode = 400;

        try {
            $credentials = $this->helper->jsonDecode(
                $this->getRequest()->getContent()
            );
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (
            !$credentials ||
            $this->getRequest()->getMethod() !== 'POST' ||
            !$this->getRequest()->isXmlHttpRequest()
        ) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $email = $credentials['email'];
        $customer = $this->customerRepository->get($email);

        if (isset($email) && $customer->getId()) {
            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET,
                    $customer->getWebsiteId()
                );
            } catch (NoSuchEntityException $e) {
                $response = [
                    'errors' => true,
                    'message' => $this->messageManager->addError($e->getMessage())
                ];
            } catch (\Exception $exception) {
                $response = [
                    'errors' => true,
                    'message' => $this->messageManager->addError($e->getMessage())
                ];
            }
        }
        $response = [
            'errors' => false,
            'message' => __(
                'If there is an account associated with %1
                you will receive an email with a link to reset your password.',
                $email
            )
        ];

        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}
