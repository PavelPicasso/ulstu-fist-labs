const WebSocket = require("ws");
require("pidcrypt/seedrandom")
var pidCrypt = require("pidcrypt")
require("pidcrypt/aes_cbc")
const port = 5000;
const server = new WebSocket.Server({ port });

// подключённые клиенты
let clients = {};
let aes = new pidCrypt.AES.CBC();

generateUUID = () => {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace( /[xy]/g, ( c ) => {
      let r = Math.random() * 16 | 0; 
      return ( c == 'x' ? r : ( r & 0x3 | 0x8 ) ).toString( 16 );
  } );
};

sendMessage = (message) => {
  server.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(message);
    }
  });
}



function  Geffe(L1, L2, L3, n) {
  let holder = "";
  for(let i = 0; i < n; i++) {
          L1 = (L1 << 1) | (((L1 >> 29)^ (L1 >> 28) ^ (L1 >> 25) ^ (L1 >> 23)) & 1);
          L2 = (L2 << 1) | (((L2 >> 30)^ (L2 >> 27)) & 1);
          L3 = (L3 << 1) | (((L3 >> 31)^ (L3 >> 30) ^ (L3 >> 29) ^ (L3 >> 28) ^ (L3 >> 26) ^ (L3 >> 24)) & 1 );
        
          holder += ((((L3 >> 32) & 1 )*((L1 >> 30) & 1)) ^ ((((L3 >> 32) & 1) ^ 1) * ((L2 >> 31) & 1)) );
  }
  return holder;
}

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min; //Максимум не включается, минимум включается
}

function StringBin(mystring) {    
  let mybitseq = "";
  let bin = "";
  let end = mystring.length;
  for(let i = 0 ; i < end; i++){
      bin = mystring[i].charCodeAt().toString(2);
      mybitseq += bin;
  }
  return mybitseq;
}

function roughScale(x, base) {
  const parsed = parseInt(x, base);
  if (isNaN(parsed)) { return 0 }
  return parsed;
}

function BinString(bin_string) {
  let mybitseq = '';
  let hex_seq = bin_string[0];
  let end = bin_string.length;
  let char = '';
  for(let i = 1 ; i <= end; i++) {
      if(hex_seq.length % 7 == 0) {
          char = String.fromCharCode(roughScale(hex_seq, 2));
          hex_seq = '';
          mybitseq += char;

      }
      
      hex_seq += bin_string[i];
      if (hex_seq === '100000') {
          hex_seq = "";
          mybitseq += ' ';
          continue;
      }
  }
  return mybitseq;
}




server.on("connection", ws => {
  let id = generateUUID();
  clients[id] = ws;

  ws.on("message", message => {
    if (message === 'disconnect') {
      ws.close()
    } else {
      let res = message.split('|');
      switch (res[0]) {
          case 'encrypt':
            let text = StringBin(res[1]);
            let len = text.length;
            let l1 = getRandomInt(1, 29); // 29
            let l2 = getRandomInt(1, 30); // 30
            let l3 = getRandomInt(1, 31); // 31
            let sequence = Geffe(l1, l2, l3, len);

            let crypted = '';
            for(let i = 0; i < len; i++) {
                crypted += (text[i] - 0) ^ (sequence[i] - 0);
            }
            sendMessage('encrypt: ' + crypted + ' pass: ' + sequence);
            break;

          case 'decrypt':
            let size = res[1].length;
            let bin_sequence = res[1];
            let geff = res[2];
            let bin_text = '';
            for(let i = 0; i < size; i++) {
                bin_text += (bin_sequence[i] - 0) ^ (geff[i] - 0);
            }
            let decrypted = BinString(bin_text);
            sendMessage(decrypted);
            break;
      
          default:
            server.clients.forEach(client => {
              if (client.readyState === WebSocket.OPEN) {
                client.send(message);
              }
            });
            break;
      }
    }
  });

  ws.on('close', function() {
    server.clients.forEach((client) => {
      if (client.readyState === WebSocket.OPEN) {
        client.send(`соединение закрыто c ${id}`);
      }
    });
    delete clients[id];
  });

  ws.send(`Welcome ${id}`);
});