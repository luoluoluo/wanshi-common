<?php
/**
 * 文件服务client
 */
namespace Wanshi\FileClient;
use GuzzleHttp\Client;
class FileClient{
    private $appid;
    private $appsecret;
    private $time;
    public function __construct($appid, $appsecret, $domain){
        $this->time     = time();
        $this->appid    = $appid;
        $this->appsecret= $appsecret;
        $this->domain   = trim($domain, '/');
        $this->client   = new Client([
            'base_uri' => $this->domain,
            'timeout'  => 20,
            'exceptions' => false,
        ]);
    }

    /*
     * 上传
     */
    public function create($file){
        return $this->request('/file', 'POST', [
            'multipart' => [
                [
                    'name'     => 'sign',
                    'contents' => $this->sign('/file', 'POST', ['file'=>$file])
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($file, 'r')
                ]
            ]
        ]);
    }

    /**
     * 删除文件
     */
    public function delete($ids){
        $url = '/file';
        return $this->request($url, 'DELETE', [
            'form_params' => [
                'ids'  => $ids,
                'sign' => $this->sign($url, 'DELETE', ['ids' => $ids])
            ]
        ]);
    }

    //获取文件链接
    public function url($id, $data=[]){
        $url = '/file/' . $id;
        $sign = $this->sign($url, 'GET', $data);
        $data['sign'] = $sign;
        return $this->domain . $url . '?' . http_build_query($data);
    }

    //签名
    private function sign($path, $method='GET', $data = []){
        $dataStr = '';
        if(!empty($data)){
            ksort($data);
            foreach($data as $k=>$v){
                //文件
                if(is_file($v)){
                    $v = md5_file($v);
                }
                $dataStr .= sprintf('[%s:%s]', $k, $v);
            }
        }
        return $this->appid . '-' . md5(trim($path, '/') . strtoupper($method) . $dataStr . $this->appsecret . $this->time) . '-' . $this->time;
    }

    private function request($path, $method, $data){
        $method     = strtoupper($method);
        $response   = $this->client->request($method, $path, $data);
        $status     = $response->getStatusCode();
        $body       = $response->getBody()->getContents();
        if ( ($status >= 200 && $status < 300)|| ($status == 304) ) {
            return [true, $body];
        } else {
            return [false, $body];
        }
    }
}
