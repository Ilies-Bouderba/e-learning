# E-Learning Platform

A simple **university e-learning system** with role-based access for **Admins, Teachers, and Students**, supporting courses, chapters, attachments, exams, comments, and progress tracking. Designed as a **MVP-ready project** for learning and university usage.

---

## **Table of Contents**
- [Features](#features)  
- [Technology Stack](#technology-stack)  
- [Database Schema](#database-schema)  
- [Installation](#installation)  
- [Usage](#usage)  
- [Project Structure](#project-structure)  
- [Future Improvements](#future-improvements)  

---

## **Features**

**Role-Based Access:**
- Admin: manage users
- Teacher: create courses, chapters, attachments, exams, announcements; track student progress  
- Student: enroll in courses, view chapters and attachments, take exams, comment on courses, track progress  

**Course Structure:**
- Courses → Chapters → Attachments (PDF, video, other)  
- Announcements per course  
- Exams linked to courses  
- Simplified progress tracking (mark chapters completed)  

**Progress Tracking:**
- Students mark chapters as completed  
- Course progress calculated from completed chapters  
- Exam attempts recorded with scores  

**Comments & Interaction:**
- Students can comment on courses  

---

## **Technology Stack**

- **Backend:** Laravel  
- **Frontend:** HTML, CSS, JS
- **Database:** SQL server
- **Authentication:** Role-based login (Admin / Teacher / Student)  
- **Additional**: AI scoring for exams  

---

## **Database Schema**

![Database Schema](docs/images/db_schema.png)  

**Main Entities:**
- Users (admin, teacher, student)  
- Courses  
- Chapters → Attachments  
- Exams  
- Announcements  
- StudentCourses (enrollments)  
- StudentProgress (chapter completion)  
- StudentExam (exam attempts)  
- Comments  

**Relationships:**  
- Teacher → Courses  
- Courses → Chapters, Exams, Announcements, Comments  
- Chapters → Attachments → StudentProgress  
- Students → Enrollments, Exam attempts, Comments  

> Full diagram is in `docs/database_Schema.svg`.  

---

## **Installation**

1. Clone the repository:  
```bash
git clone https://github.com/yourusername/e-learning-platform.git
cd e-learning-platform
```

2. Install dependencies:  
```bash
composer install
npm install
npm run dev
```
3. Configure ```.env``` with your database credentials.

4. Run migrations to create tables:
```bash
php artisan migrate
```

5. Start the server:
```bash
php artisan serve
```
