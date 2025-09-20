<?php
header('Content-Type: application/json');

$signed_request = $_POST['signed_request'];
$data = parse_signed_request($signed_request);
$user_id = $data['user_id'];

// بدء حذف البيانات المرتبطة بالمستخدم
$status_url = 'https://anwer4u2010.github.io/socialpublisherbot-policy/deletion-status.html?id=' . $user_id;
$confirmation_code = 'del_' . $user_id;

$response = array(
  'url' => $status_url,
  'confirmation_code' => $confirmation_code
);

echo json_encode($response);

function parse_signed_request($signed_request) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);
  $secret = e9c9066ac2e54d0af18ae932a6c02796; // ضع هنا مفتاح التطبيق السري

  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  $expected_sig = hash_hmac('sha256', $payload, $secret, true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}
?>
