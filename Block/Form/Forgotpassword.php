<?php

namespace PHPCuong\CustomerAccount\Block\Form;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Form;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Forgotpassword
 *
 * @category Magento
 * @package  PHPCuong_CustomerAccount
 * @author   Lucas Teixeira dos Santos Santana <santanaluc94@gmail.com>
 * @link     http://github.com/santanaluc94
 */
class Forgotpassword extends Template
{
    /**
     * Session
     *
     * @var Session
     */
    protected $session;

    /**
     * Http Context
     *
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * Registration
     *
     * @var Registration
     */
    protected $registration;

    /**
     * @param Context $context
     * @param Session $session
     * @param HttpContext $httpContext
     * @param Registration $registration
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $session,
        HttpContext $httpContext,
        Registration $registration,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $session;
        $this->httpContext = $httpContext;
        $this->registration = $registration;
    }

    /**
     * Return registration
     *
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl(): string
    {
        return $this->getUrl('phpcuong/customer_ajax/forgotpassword');
    }

    /**
     * Check if autocomplete is disabled on storefront
     *
     * @return bool
     */
    public function isAutocompleteDisabled(): bool
    {
        return !$this->_scopeConfig->isSetFlag(
            Form::XML_PATH_ENABLE_AUTOCOMPLETE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Checking customer login status
     *
     * @return bool
     */
    public function customerIsAlreadyLoggedIn(): bool
    {
        return (bool) $this->httpContext->getValue(
            \Magento\Customer\Model\Context::CONTEXT_AUTH
        );
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('customer/account/login');
    }
}
