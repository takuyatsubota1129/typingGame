import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
app.use(cors());

const server = createServer(app);
const io = new Server(server, {
  cors: {
    origin: "http://localhost:8080", // Viteサーバーのアドレス
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
