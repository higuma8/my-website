<?php
// contact_process.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームデータの取得
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $company = $_POST['company'] ?? ''; // 会社名（任意）
    $phone = $_POST['phone'] ?? ''; // 電話番号（任意）
    
    // バリデーション
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'お名前は必須です。';
    }
    
    if (empty($email)) {
        $errors[] = 'メールアドレスは必須です。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が正しくありません。';
    }
    
    if (empty($subject)) {
        $errors[] = '件名は必須です。';
    }
    
    if (empty($message)) {
        $errors[] = 'お問い合わせ内容は必須です。';
    }
    
    // エラーがない場合、メール送信
    if (empty($errors)) {
        // 送信先メールアドレス
        $to = 'higumakingyo@gmail.com'; // ここを実際のメールアドレスに変更
        
        // メール件名
        $mail_subject = '[お問い合わせ] ' . $subject;
        
        // メール本文
        $mail_body = "お問い合わせフォームから新しいメッセージが届きました。\n\n";
        $mail_body .= "【お名前】\n" . $name . "\n\n";
        if (!empty($company)) {
            $mail_body .= "【会社名】\n" . $company . "\n\n";
        }
        $mail_body .= "【メールアドレス】\n" . $email . "\n\n";
        if (!empty($phone)) {
            $mail_body .= "【電話番号】\n" . $phone . "\n\n";
        }
        $mail_body .= "【件名】\n" . $subject . "\n\n";
        $mail_body .= "【お問い合わせ内容】\n" . $message . "\n\n";
        $mail_body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
        
        // メールヘッダー
        $headers = [
            'From: higumakingyo@gmail.com', // ドメインのメールに変更
            'Reply-To: ' . $email,
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        // メール送信
        if (mail($to, $mail_subject, $mail_body, implode("\r\n", $headers))) {
            // 送信成功 - 自動返信メールも送信
            send_auto_reply($email, $name);
            
            // 完了ページにリダイレクト
            header('Location: contact_complete.php');
            exit;
        } else {
            $errors[] = 'メールの送信に失敗しました。時間をおいて再度お試しください。';
        }
    }
}

// 自動返信メール関数
function send_auto_reply($email, $name) {
    $subject = 'お問い合わせありがとうございます';
    
    $body = $name . " 様\n\n";
    $body .= "この度は、お問い合わせいただきありがとうございます。\n";
    $body .= "以下の内容でお問い合わせを受け付けいたしました。\n\n";
    $body .= "内容を確認次第、担当者よりご連絡させていただきます。\n";
    $body .= "しばらくお待ちください。\n\n";
    $body .= "※このメールは自動送信です。\n";
    $body .= "※「@your-domain.com」からのメールを受信できるように設定してください。\n\n";
    $body .= "3日以上経過しても連絡が無い場合は、\n";
    $body .= "お手数ですが再度ご連絡ください。\n\n";
    $body .= "────────────────────\n";
    $body .= "お問い合わせ窓口\n";
    $body .= "Email: higumakingyo@gmail.com\n";
    $body .= "────────────────────\n";
    
    $headers = [
        'From: higumakingyo@gmail.com',
        'Content-Type: text/plain; charset=UTF-8'
    ];
    
    mail($email, $subject, $body, implode("\r\n", $headers));
}
?>