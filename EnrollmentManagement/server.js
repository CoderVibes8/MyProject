const express = require('express');
const flash = require('connect-flash');
const app = express();
const path = require('path');
const methodOverride = require('method-override');
const session = require('express-session');

app.use(session({
    secret: 'secret-key',
    resave: false,
    saveUninitialized: true
}));

app.use(flash());

app.use((req, res, next) => {
    res.locals.error = req.flash('error');
    res.locals.success = req.flash('success');
    next();
});

app.use('/public', express.static(path.join(__dirname, 'public')));
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, '/views'));

const PORT = process.env.PORT || 5000;

app.use(express.static('public'));

// ✅ Body parsers
app.use(express.json()); // parse application/json
app.use(express.urlencoded({ extended: true })); // parse application/x-www-form-urlencoded

app.use(methodOverride("_method"));

app.use('/manage', require('./routes/manage'));
app.use("/dashboard", require('./routes/chart'));

// Render the main EJS file
app.get('/', (req, res) => {
    res.render('Home');
});

app.listen(PORT);
console.log(`Server is running at Port http://localhost:${PORT}`);
