import express from 'express';
import { createServer } from 'https'; // http から https に変更
import { Server } from 'socket.io';
import cors from 'cors';
import fs from 'fs'; // fs モジュールをインポート

const app = express();
app.use(cors());

const server = createServer({
  key: fs.readFileSync('/etc/pki/tls/private/server.key'), // 秘密鍵の読み込み
  cert: fs.readFileSync('/etc/pki/tls/certs/server.crt') // 証明書の読み込み
}, app);

const io = new Server(server, {
  cors: {
    // origin: "http://localhost:8080", // Viteサーバーのアドレス
    origin: "http://192.168.0.184",
    methods: ["GET", "POST"]
  }
});

let stagePlayers = {};

io.on('connection', (socket) => {
  console.log('A user connected');

  socket.on('joinStage', (stageId) => {
    socket.stageId = stageId;
    if (!stagePlayers[stageId]) {
      stagePlayers[stageId] = new Set();
    }
    stagePlayers[stageId].add(socket.id);
    io.emit('playerCount', { stageId, count: stagePlayers[stageId].size });
  });

  socket.on('leaveStage', () => {
    if (socket.stageId && stagePlayers[socket.stageId]) {
      stagePlayers[socket.stageId].delete(socket.id);
      io.emit('playerCount', { stageId: socket.stageId, count: stagePlayers[socket.stageId].size });
      socket.stageId = null;
    }
  });

  socket.on('disconnect', () => {
    console.log('User disconnected');
    if (socket.stageId && stagePlayers[socket.stageId]) {
      stagePlayers[socket.stageId].delete(socket.id);
      io.emit('playerCount', { stageId: socket.stageId, count: stagePlayers[socket.stageId].size });
    }
  });
});

const port = process.env.SOCKET_PORT || 3000;
server.listen(port, '0.0.0.0', () => {
  console.log(`Socket.IO server running on port ${port}`);
});
