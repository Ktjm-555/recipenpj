document.addEventListener('DOMContentLoaded', function() {
    // DOMが読み込まれた後に処理を始める

    setInterval(resultLog, 1000);
    //  resultlogを0.1秒毎に実行する。
    
    function resultLog() {
        // letは変数を宣言
        let preFS = document.getElementById('preFilesize');
        // 任意のHTMLタグで指定したIDにマッチするドキュメント要素を取得
        let aftFS = document.getElementById('aftFilesize');
      
        if (preFS.value === aftFS.value) {

            let xhr = new XMLHttpRequest();
  
          // 非同期通信をスタート
            xhr.open('GET', 'chatlog.php?ajax=' + "OFF", true);
            xhr.send(null);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) { 
                    if (xhr.status === 200) { 
                        aftFS.value = xhr.responseText;
                    }
                }
            }

        } else {          
            let chatArea = document.getElementById('chat-area');
            let xhr = new XMLHttpRequest();
            
            // 非同期通信をスタート
            xhr.open('GET', 'chatlog.php?ajax=' + "ON", true);
            xhr.send(null);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) { 
                    // データ取得したら
                    if (xhr.status === 200) { 
                        // 通信が成功したら
                        chatArea.insertAdjacentHTML('afterbegin', xhr.responseText);
                        // chatAreaの後にxhr.responseText を挿入　（htmlに）

                        let chatAreaHeight = chatArea.scrollHeight;
                        chatArea.scrollTop = chatAreaHeight;
                    }
                } else { 
                    chatArea.textContent = '';
                }
            }
        };
    };
}, false);

