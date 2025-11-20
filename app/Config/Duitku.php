<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Duitku extends BaseConfig
{
    public $merchantCode = 'DS16902';
    public $apiKey = '792f56c9e2277927191c4c4924f06b40';
    public $sandboxMode = true;
    
    // Sandbox URLs
    public $sandboxUrl = 'https://sandbox.duitku.com/webapi/api/merchant';
    public $sandboxCallbackUrl = 'https://sandbox.duitku.com/webapi/api/merchant/callback';
    
    // Production URLs (untuk nanti)
    public $productionUrl = 'https://passport.duitku.com/webapi/api/merchant';
    public $productionCallbackUrl = 'https://passport.duitku.com/webapi/api/merchant/callback';
    
    // Get active URL based on mode
    public function getBaseUrl()
    {
        return $this->sandboxMode ? $this->sandboxUrl : $this->productionUrl;
    }
}
