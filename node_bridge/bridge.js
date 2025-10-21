const express = require("express"); // laravelnya node.js
const { SerialPort } = require("serialport");//untuk ngirim dan menerima data ke arduino
const { ReadlineParser } = require("@serialport/parser-readline");

const app = express();
const port = 4000;

// Sesuaikan COM port Arduino kamu
const serial = new SerialPort({ path: "COM13", baudRate: 9600 });
const parser = serial.pipe(new ReadlineParser({ delimiter: "\r\n" }));

serial.on("open", () => {
  console.log("Serial port opened:", "COM13"); // ini maksudnya kasih tau kalo arduino dengan port 13 sudah siap
});

parser.on("data", (data) => {
  console.log("Arduino:", data); // nah ini itu maksudnya munculin tulisan yang ada di arduino di cmd saat run nanti kan ada tu di cmd kayak misalnya tekan enter untuk membuka plang gitu dan maksudnya
});

// Middleware debug semua request
app.use((req, res, next) => {
  console.log(` Request masuk: ${req.method} ${req.url}`);
  next();
});

// Endpoint root (tes)
app.get("/", (req, res) => {
  res.send("Bridge jalan ");
});

// Endpoint open-gate
app.get("/open-gate", (req, res) => {
  serial.write("OPEN\n", (err) => {
    if (err) {
      console.error("Error kirim ke Arduino:", err.message);
      return res
        .status(500)
        .json({ success: false, message: "Gagal kirim ke Arduino" });
    }
    console.log("Perintah OPEN dikirim ke Arduino");
    res.json({ success: true, message: "Perintah OPEN dikirim ke Arduino" });
  });
});

app.listen(port, () => {
  console.log(`Bridge running at http://localhost:${port}`); // nah ini jalankan server di post 4000, sesuai dengan yang di atas 
});
