# Commitime (Backend)

This is the **Laravel 11 backend** for **Commitime** – a smart screen time monitoring application designed to help children develop healthier screen habits, while empowering parents with insightful reports and control features.

---

##  Project Overview

**Commitime** allows users (especially children under 18) to use devices responsibly by monitoring screen time, analyzing behavior using AI, and sending detailed reports to parents.

It features:
- User registration with age-based logic
- Automatic parent-child linking
- AI-powered reflection questions & emotion detection
- Screen distance analysis via camera (AI service)
- Daily and weekly screen time reports
- Email notifications to parents
- RESTful API with authentication

---

##  Key Features

### Parent-Child Account Logic
- Children under 18 register without a password
- Parent email is **required** and validated
- Daily screen time reports are automatically sent to the parent

###  Secure Authentication
- Uses **Laravel Sanctum** for API token-based auth
- Role-based separation for adults and minors

### 🤖 AI Integration (via Flask)
A separate Flask microservice provides:
- Reflection question generation based on usage patterns
- Emotion analysis from user input
- Activity suggestions based on emotional state
- Camera-based screen distance detection (too close warning)

###  Screen Time & Usage Reports
- Monitors daily usage duration and time of day
- Records app-specific usage
- Automatically emails the report to parents for minors

###  Emailing with Mailtrap (Dev)
- Email confirmation of registration
- Daily reports to parents (customizable via Laravel notifications)

---

## Tech Stack
- Laravel 11
- MySQL
- Sanctum for API Auth
- Python Flask (AI microservice)
- Postman (API testing)
- Mailtrap (email testing)




  
