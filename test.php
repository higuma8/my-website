<?php
if (mail('higumakingyo@gmail.com', 'テスト送信', 'これはテストです')) {
  echo "送信成功";
} else {
  echo "送信失敗";
}
?>