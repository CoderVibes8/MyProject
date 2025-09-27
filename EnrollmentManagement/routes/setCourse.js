const express = require('express');
const route = express.Router();
const path = require('path');
const app = express();

app.use('/public',express.static(path.join(__dirname, 'public')));
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, '/views'));

const pool = require("../config/config");




module.exports = route;