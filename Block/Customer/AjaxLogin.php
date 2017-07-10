<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-07-10 11:01:03
 * @Last Modified by:   nquangcuong
 * @Last Modified time: 2017-07-10 14:53:06
 * @website: http://giaphugroup.com
 */

namespace PHPCuong\CustomerAjaxLogin\Block\Customer;

class AjaxLogin extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerUrl = $customerUrl;
        $this->httpContext = $httpContext;
    }

    /**
     * Retrieve password forgotten url
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->_customerUrl->getForgotPasswordUrl();
    }

    /**
     * Retrieve ajaxlogin url
     *
     * @return string
     */
    public function getAjaxLoginUrl()
    {
        return $this->getUrl('phpcuong/customer/ajaxlogin');
    }

    /**
     * Checking customer login status
     *
     * @return bool
     */
    public function customerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }
}
