# AgriDoc

## Overview

AgriDoc is a simple web-based project built to help identify crop diseases.
The user can upload an image and provide some basic details, and the system returns suggestions based on the input using the OpenAI API.

---

## Features

* Accepts crop image and related details
* Generates basic disease-related suggestions
* Stores user input data
* Simple and easy to use

---

## Tech Used

* HTML, CSS, JavaScript
* PHP
* MySQL
* XAMPP
* OpenAI API

---

## How it works

User uploads crop image and enters details → data is sent to backend → API processes the input → response is generated and displayed

---

## How to run

Clone the repository:

```bash id="2f9q6k"
git clone https://github.com/your-username/agridoc.git
cd agridoc
```

Start XAMPP and place the project in the `htdocs` folder

Import the database into MySQL

Add your API key in the backend file

Open in browser:

```id="aqx41j"
http://localhost/agridoc
```

---

## Project Structure

```id="o7oz1h"
agridoc/
│── index.html  
│── style.css  
│── script.js  
│── analyze.php  
│── database.sql  
```

---

## Note

This project was built to explore API integration and basic backend development while solving a simple real-world problem.

---

## Author

Dhruv Parmar

---

