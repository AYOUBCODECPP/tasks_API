const express = require('express');
const app = express();
const port = 7000;


app.use(express.json());



app.listen(port, () => {
  console.log(`Server is running at http://localhost:${port}`);
});
