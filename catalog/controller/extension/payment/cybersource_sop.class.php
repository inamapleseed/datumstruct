<?php
/**
 * @author Qphoria@gmail.com
 * @web http://www.opencartguru.com/
 *
 * @usage
 *		$params = array(
 *			'xxx' => 'value1',
 *			'yyy' => 'value2',
 *			'zzz' => 'value3',
 *		);
 *
 *		$payclass = New PayClass();
 *		$payclass->sendPayment($params);
 */

class cybersource_sop {

	private $_log = '';
	private $_url = 'https://secureacceptance.cybersource.com/silent/pay';
	private $_testurl = 'https://testsecureacceptance.cybersource.com/silent/pay';

	public function __construct($logpath = '') {
		if ($logpath && is_dir($logpath) && is_writable($logpath)) {	$this->_log = $logpath .  basename(__FILE__, '.php') . '.log'; }
	}

	public function buildOutput($params) {

		$url = $this->_url;
		if (isset($params['test'])) {
			unset($params['test']);
			$url = $this->_testurl;
		}

		$data  = 'Redirecting...';
		$data .= '<form action="'.$url.'" id="payform" method="post">';
		foreach ($params as $key => $value) {
			$data .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
		}
		$data .= '<input type="submit" value="-->" />';
		$data .= '</form>';
		$data .= '<script type="text/javascript">';
		$data .= 'document.forms["payform"].submit();';
		$data .= '</script>';
		$this->writeLog($data);
		return $data;
	}

	public function sendPayment($params) {
		$url = $this->_url;
		if (isset($params['test'])) {
			$url = $this->_testurl;
			unset($params['test']);
		}

		$data = '';
		foreach ($params as $key => $value) {
			$data .= "&$key=$value";
		}
		$data = trim($data,"&");

		$this->writeLog($data);
		return $this->parseResult($this->curl_post($url, $data));
	}

	public function parseResult($response) {


		if (isset($response['error'])) {
			return $response;
		}

		$response = $response['data'];

		if (!$response) {
			$this->writeLog(__FUNCTION__ . ' ERROR: Empty Response');
		} else {
			$this->writeLog($response);
		}

		$xml = New DOMDocument();
		$xml->loadXML($response);
		$fields = $xml->getElementsByTagName('FIELD');

		foreach ($fields as $field) {
			$res[$field->getAttribute('KEY')] = $field->nodeValue;
		}

		return array('data' => $res);
	}

	private function curl_post($url, $data) {
		$ch = curl_init($url);
		//curl_setopt($ch, CURLOPT_PORT, 443);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		if (curl_error($ch)) {
			$response['error'] = curl_error($ch) . '(' . curl_errno($ch) . ')';
		} else {
			$response['data'] = curl_exec($ch);
		}

		curl_close($ch);
		return $response;
	}

	private function curl_get ($url, $data) {
		$ch = curl_init($url . $data);
		curl_setopt($ch, CURLOPT_PORT, 443);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

		$response = array();

		if (curl_error($ch)) {
			$response['error'] = curl_error($ch) . '(' . curl_errno($ch) . ')';
		} else {
			$response['data'] = curl_exec($ch);
		}

		curl_close($ch);

		return $response;
	}

	private function writeLog($msg) {
		if ($this->_log) {
			$msg = (str_repeat('-', 70) . "\r\n" . $msg . "\r\n" . str_repeat('-', 70) . "\r\n");
			file_put_contents($this->_log, $msg, FILE_APPEND);
		}
	}

	public function sign ($params, $secret_key) {
		return $this->signData($this->buildDataToSign($params), $secret_key);
	}

	private function signData($data, $secretKey) {
		return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
	}

	private function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return $this->commaSeparate($dataToSign);
	}

	private function commaSeparate ($dataToSign) {
		return implode(",",$dataToSign);
	}
}
?>