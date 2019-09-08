<?php
/**
 * Created by PhpStorm.
 * User: OneDouble
 * Date: 2016/11/8
 * Time: 下午3:35
 */

class Wx_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private $appid = 'wxbe243342b268e138';
    private $appSercert = 'f8a0fecf7b3e26c0089d06203ff6d632';
    private $sessionKey = 'KP3JAU54iOdnol9xnKNA3g==';

    public  $OK = 0;
    public  $IllegalAesKey = -41001;
    public  $IllegalIv = -41002;
    public  $IllegalBuffer = -41003;
    public  $DecodeBase64Error = -41004;
    
    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData( $sessionKey= '', $encryptedData, $iv, &$data )
    {
        $this->sessionKey = $sessionKey;

        if (strlen($this->sessionKey) != 24) {
            return $this->IllegalAesKey;
        }
        $aesKey=base64_decode($this->sessionKey);

        if (strlen($iv) != 24) {
            return $this->IllegalIv;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode( $result );
        if( $dataObj  == NULL )
        {
            return $this->IllegalBuffer;
        }
        if( $dataObj->watermark->appid != $this->appid )
        {
            return $this->IllegalBuffer;
        }
        $data = $result;
        return $this->OK;
    }

    public function getSessionKey($code)
    {
        $url = $this->_buildUrl($code);
        $sessionInfo = $this->curl_get($url);

        return $sessionInfo;
    }

    private function _buildUrl($code)
    {
        return $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->appSercert}&js_code={$code}&grant_type=authorization_code";
    }
    public  function curl_get($url)
    {
        $data = file_get_contents($url);

        return json_decode($data);
    }
}

?>