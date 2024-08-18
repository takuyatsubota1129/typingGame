const canvas = document.querySelector("canvas");
const ctx = canvas.getContext("2d");
const colors = [
  "#b4b2b5",
  "#dfd73f",
  "#6ed2dc",
  "#66cf5d",
  "#c542cb",
  "#d0535e",
  "#3733c9"
];

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

function texts(color) {
  ctx.font = "20vh Bungee Outline";
  ctx.shadowBlur = 30;
  ctx.shadowColor = color;
  ctx.fillStyle = color;
  ctx.setTransform(1, -0.15, 0, 1, 0, -10);
  ctx.fillText("Glitch", window.innerWidth / 2, window.innerHeight / 2 - 5);

  ctx.fillStyle = "white";
  ctx.shadowBlur = 30;
  ctx.shadowColor = color;
  ctx.fillText("Glitch", window.innerWidth / 2, window.innerHeight / 2);

  ctx.font = "18vh Bungee Inline";
  ctx.shadowBlur = 30;
  ctx.shadowColor = color;
  ctx.fillStyle = "#fff";
  ctx.setTransform(1, -0.15, 0, 1, 0, -10);
  ctx.fillText("Effect", window.innerWidth / 2, window.innerHeight / 2 + window.innerHeight / 10);

  ctx.textAlign = "center";
  ctx.textBaseline = "middle";
}

function glitch() {
  requestAnimationFrame(glitch);

  ctx.fillStyle = "#1a191c";
  ctx.fillRect(0, 0, window.innerWidth, window.innerHeight);

  texts(colors[Math.floor(Math.random() * colors.length)]);
  ctx.shadowBlur = 0;
  ctx.shadowColor = "none";
  ctx.setTransform(1, 0, 0, 1, 0, 0);

  for (let i = 0; i < 1000; i++) {
    ctx.fillStyle = `rgba(255, 255, 255, ${Math.random() * 0.01})`;
    ctx.fillRect(
      Math.floor(Math.random() * window.innerWidth),
      Math.floor(Math.random() * window.innerHeight),
      Math.floor(Math.random() * 30) + 1,
      Math.floor(Math.random() * 30) + 1
    );

    ctx.fillStyle = `rgba(0,0,0,${Math.random() * 0.1})`;
    ctx.fillRect(
      Math.floor(Math.random() * window.innerWidth),
      Math.floor(Math.random() * window.innerHeight),
      Math.floor(Math.random() * 25) + 1,
      Math.floor(Math.random() * 25) + 1
    );
  }
}

glitch();

window.addEventListener('resize', () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});
