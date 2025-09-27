const express = require("express");
const route = express.Router();
const path = require("path");
const app = express();
const pool = require("../config/config");

app.use("/public", express.static(path.join(__dirname, "public")));
app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "/views"));

// GET /dashboard - initial render
route.get("/", (req, res) => {
  const pieQuery = `
    SELECT COUNT(*) AS total, course 
    FROM students 
    WHERE level = 1 
    GROUP BY course
  `;

  const defaultReportQuery = `
    SELECT COUNT(*) AS total, course 
    FROM students 
    WHERE YEAR(reg_date) = YEAR(CURDATE()) 
    AND MONTH(reg_date) = MONTH(CURDATE())
    GROUP BY course
  `;

  pool.query(pieQuery, (pieError, pieResults) => {
    if (pieError) {
      console.error("Pie Query Error:", pieError);
      return res.status(500).send("Error Fetching Pie Data");
    }

    pool.query(defaultReportQuery, (reportError, reportResults) => {
      if (reportError) {
        console.error("Report Query Error:", reportError);
        return res.status(500).send("Error Fetching Report Data");
      }

      const pieData = pieResults.map(row => ({
        value: row.total,
        name: row.course
      }));

      res.render("dashboard", {
        pieData,
        reportData: reportResults || []
      });
    });
  });
});

// POST /dashboard - filter data
route.post("/", (req, res) => {
  const { month, year, level } = req.body;

  let query = `
    SELECT COUNT(*) as total, course 
    FROM students 
    WHERE 1=1
  `;
  let params = [];

  if (month) {
    query += " AND MONTH(reg_date) = ?";
    params.push(month);
  }
  if (year) {
    query += " AND YEAR(reg_date) = ?";
    params.push(year);
  }
  if (level) {
    query += " AND level = ?";
    params.push(level);
  }

  query += " GROUP BY course";

  pool.query(query, params, (error, results) => {
    if (error) {
      console.error("Report Query Error:", error);
      return res.json({ success: false, message: error.sqlMessage });
    }
    res.json({ success: true, data: results });
  });
});

module.exports = route;
