const express = require('express');
const flash = require('connect-flash');
const route = express.Router();
const path = require('path');
const app = express();
const session = require('express-session');


app.use('/public',express.static(path.join(__dirname, 'public')));
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, '/views'));

const pool = require("../config/config");
const { error } = require('console');

app.use(session({
    secret: 'secret-key',
    resave: false,
    saveUninitialized: true
}));

app.use(flash());


// Set Course
route.post('/setcourse', (req, res) => {
    const course = req.body.course;
    req.session.course = course;

    res.redirect("/manage/students");
})

// Retrieve Students per course
route.get("/students", (req, res) => {
    
    const course = req.session.course;

    console.log(course);

    const query = `SELECT * FROM students WHERE course = "${course}" `;

    pool.query(query, (error, result) => {
        if(error){
            res.status(500).send("Error Fetching");
            console.log(error);
        }else{
            res.render('students', {lists : result, course });
        }
    });
});


// Enroll Student
// route.post("/StudentEnroll", async(req, res) => {
//     const firstName = req.body.firstName;
//     const lastName = req.body.lastName;
//     const gender = req.body.gender;
//     const email = req.body.email;
//     const course = req.body.course;
//     const level = req.body.level;
    
//     await pool.query(
//         "INSERT INTO students SET firstname = ?, lastname = ?, email = ?, level = ?, gender = ?, course = ?", 
//         [firstName, lastName, email, level, gender, course], 
//         (error, result) => {
//             if(error){
//                 res.status(500).send("Error Enrolling Student");
//                 console.log(error);
//             }else{              
//                 res.redirect("/manage/StudentEnroll");
//             }
//         }
//     );
// });



//Enrolling Student
route.post("/StudentEnroll", async (req, res) => {
    const firstName = req.body.firstName;
    const lastName = req.body.lastName;
    const gender = req.body.gender;
    const email = req.body.email;
    const course = req.body.course;
    const level = req.body.level;

    // Use parameterized query to prevent SQL injection
    const existingStudents = `SELECT * FROM students WHERE email = ?`;

    await pool.query(existingStudents, [email], async (error, result) => {
        if (error) {
            console.log("Error checking for existing students:", error);
            return res.status(500).send("Error checking existing students"); 
        }

        if (result.length > 0) {
            req.flash('error', 'This email is already used!');
            return res.redirect("/manage/StudentEnroll"); 
        }



        // If the email doesn't exist, insert the new student
        await pool.query(
            "INSERT INTO students (firstname, lastname, email, level, gender, course) VALUES (?, ?, ?, ?, ?, ?)",
            [firstName, lastName, email, level, gender, course],
            (insertError, insertResult) => {
                if (insertError) {
                    res.status(500).send("Error Enrolling Student");
                    console.log(insertError);
                } else {
                    req.session.course = course;   
                    req.flash('success', 'Enrolling Student Successfully');      
                    res.redirect("/manage/StudentEnroll");
                }
            }
        );
    });
});



route.get("/students", (req, res) =>{
    res.render("students");
})

// Update Students
route.get("/students/update/:id/:course", async (req, res) => {

    const {id, course} = req.params;

    await pool.query(
        "SELECT * FROM students WHERE id = ?",
        [id],
        (error, results) => {
            if(error){
                res.status(500).send("Error Updating Students");
                console.log(error);
            }else{
                res.render("update", {student: results[0]});
            }
        }
    );
});

route.put("/students/update/:id/:course", async (req, res) => {
    const id =  req.params.id;
    const firstName =  req.body.firstName;
    const lastName = req.body.lastName;
    const email = req.body.email;
    const level = req.body.yearlevel;
    const gender = req.body.gender;
    const course = req.body.course;

    await pool.query(
        "UPDATE students SET firstname = ?, lastname = ?, email = ?, level = ?, gender = ?, course = ? WHERE id = ?",
        [firstName, lastName, email, level, gender, course, id],
        (error, results) => {
            if(error && email.length > 0){
                req.flash('error', 'Error in updating student!');
                console.log(error);
                res.status(500).redirect("/manage/students");
            }else{
                req.flash('success', 'Student Information Updated Successfully');
                req.session.course = course;
                res.redirect("/manage/students");
            }
        }
    );
});

// Delete Student
route.delete("/students/:id/:course", async (req, res) =>{

    const {id, course} = req.params;

    await pool.query(
        "DELETE FROM  students WHERE id = ?",
        [id],

        (error, results) => {
            if(error){
                console.log(error);
                res.status(500).send("Error Deleting Student");
            }else{
                req.session.course = course;
                req.flash('success', 'Student Successfully Deleted!');
                res.redirect("/manage/students");
            }
        }
    )
});



route.get("/StudentEnroll", (req, res) => {
    res.render("EnrollForm");
});

route.get("/",  (req, res) => {
    res.render("StudentManagement");
});

route.get("/setcourse", (req, res) => {
    res.render("setcourse");
})


module.exports = route