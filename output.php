<?php
	include 'db_con.php';
	global $koneksi;

	$koneksi->set_charset('utf8mb4');
	$TOKEN = "1060994406:AAF7lYNVfZqlzTazBVUOpkKDSlExq2l5GJQ";
	$usernamebot= "@taprojectbot";
	$debug = false;
	function request_url($method)
	{
		global $TOKEN;
		return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
	}

	function send_reply($chatid, $text)
	{
		global $debug;
		
		$data = array(
			'chat_id' => $chatid,
			'text' => $text,
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendMessage'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_photo($chatid)
	{
		global $debug;
		
		$data = array(
			'chat_id' => $chatid,
			'photo' => 'https://cdn2.iconfinder.com/data/icons/social-flat-buttons-3/512/telegram-512.png',
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendPhoto'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_location($chatid)
	{
		global $debug;
		
		$data = array(
			'chat_id' => $chatid,
			'latitude' => '-8.607585',
			'longitude' => '115.184939',
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendLocation'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_document($chatid)
	{
		global $debug;
		$file_url = 'https://www.researchgate.net/profile/Nyoman_Putra_Sastra/publication/331995985_A_New_Framework_for_Information_System_Development_on_Instant_Messaging_for_Low_Cost_Solution/links/5d31ac2ba6fdcc2462ec1d23/A-New-Framework-for-Information-System-Development-on-Instant-Messaging-for-Low-Cost-Solution.pdf';
		$data = array(
			'caption'  => 'file',
			'chat_id' => $chatid,
			'document' => $file_url,
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendDocument'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_sticker($chatid,$fileid)
	{
		global $debug;
		
		$data = array(
			
			'chat_id' => $chatid,
			'sticker' => $fileid,
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendSticker'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_contact($chatid)
	{
		global $debug;
		
		$data = array(
			
			'chat_id' => $chatid,
			'first_name' => 'Gedhe',
			'phone_number' => '+6287730534612',
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendContact'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_video($chatid,$fileid)
	{
		global $debug;
		
		$data = array(
			
			'chat_id' => $chatid,
			'video' => $fileid,
			
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendVideo'), false, $context);
		if ($debug)
		print_r($result);
    }

    function send_voice($chatid,$fileid)
	{
		global $debug;
		
		$data = array(
			
			'chat_id' => $chatid,
			'voice' => $fileid,
			
		);
		
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencodedrn",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents(request_url('sendVoice'), false, $context);
		if ($debug)
		print_r($result);
    }

    
	while(true)	{
		global $koneksi;
    	$result = mysqli_query($koneksi, "SELECT *FROM tb_outbox WHERE flag = '1'");
		while ( $row = mysqli_fetch_assoc($result)) {
			echo "-";
			$id_outbox = $row["id_outbox"];
			$chat_id = $row["chat_id"];

			if ($row["type"] == 'msg') {
				$text = $row["out_msg"];
				send_reply($chat_id, $text);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'img') {
				send_photo($chat_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'loc') {
				send_location($chat_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'file') {
				send_document($chat_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'stc') {
				$file_id = $row["out_msg"];
				send_sticker($chat_id, $file_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'ctc') {
				send_contact($chat_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'vdo') {
				$file_id = $row["out_msg"];
				send_video($chat_id,$file_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'vic') {
				$file_id = $row["out_msg"];
				send_voice($chat_id,$file_id);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

			elseif ($row["type"] == 'unk'){
				$text = "Format Pesan Belum Diketahui";
				send_reply($chat_id, $text);
				mysqli_query($koneksi, "UPDATE tb_outbox set flag = '2',tgl = NOW() where id_outbox = $id_outbox");
			}

        }
	}
	
	

?>