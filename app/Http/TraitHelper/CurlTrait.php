<?php


namespace App\Http\TraitHelper;


trait CurlTrait
{
    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    private function delete(string $url, array $data = []): array
    {
        $data = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: DELETE'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        // curl 异常
        if (curl_errno($ch)) {
            return [
                'success' => false,
                'status' => 1478,
                'msg' => curl_error($ch)
            ];
        }
        curl_close($ch);

        if (is_string($response)) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $type
     * @return array
     */
    private function put(string $url, array $data = [], $type = 'PATCH'): array
    {

        $data = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: ' . $type));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        // curl 异常
        if (curl_errno($ch)) {
            return [
                'success' => false,
                'status' => 1478,
                'msg' => curl_error($ch)
            ];
        }
        curl_close($ch);
        if (is_string($response)) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * @param $url
     * @param array $data
     * @return array
     */
    public function post($url, array $data = []): array
    {
        $post_data = http_build_query($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 20s 超时
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);

        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        // post参数
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($curl);

        // curl 出错
        if (curl_errno($curl)) {
            return [
                'success' => false,
                'status' => 1478,
                'msg' => curl_error($curl)
            ];
        }
        if (is_string($response)) {
            return json_decode($response, true);
        }
        curl_close($curl);
        return $response;
    }

    /**
     * @param string $url
     * @param array $url_data
     * @return array|mixed
     */
    private function get(string $url, array $url_data = [])
    {
        // 追加参数
        if ($url_data) {
            $url_data = http_build_query($url_data);
            $url .= '?' . $url_data;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 20s 超时
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);

        $list_stat = curl_exec($curl);

        // curl 出错
        if (curl_errno($curl)) {
            return [
                'success' => false,
                'status' => 1478,
                'msg' => curl_error($curl)
            ];
        }
        if (is_string($list_stat)) {
            return json_decode($list_stat, true);
        }
        curl_close($curl);
        return $list_stat;
    }

}